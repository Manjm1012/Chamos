<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:120'],
        ]);

        $tenant = TenantContext::tenant();

        $user = \App\Models\User::query()
            ->where('email', $validated['email'])
            ->where('tenant_id', $tenant?->id)
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are invalid.'],
            ]);
        }

        if (! in_array($user->role, ['admin', 'operative'], true)) {
            return response()->json([
                'message' => 'This user cannot access the fleet API.',
            ], 403);
        }

        $abilities = $user->role === 'admin'
            ? ['*']
            : ['equipment:read', 'equipment:write', 'maintenance:read', 'maintenance:write'];

        $token = $user->createToken(
            $validated['device_name'] ?? 'api-token',
            $abilities,
        );

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token->plainTextToken,
            'abilities' => $abilities,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'tenant_id' => $user->tenant_id,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('tenant');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'tenant' => [
                'id' => $user->tenant?->id,
                'name' => $user->tenant?->name,
                'slug' => $user->tenant?->slug,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        $bearerToken = $request->bearerToken();

        if ($bearerToken && str_contains($bearerToken, '|')) {
            [$tokenId] = explode('|', $bearerToken, 2);

            PersonalAccessToken::query()->whereKey((int) $tokenId)->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
