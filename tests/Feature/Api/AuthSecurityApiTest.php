<?php

namespace Tests\Feature\Api;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthSecurityApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_is_rate_limited_after_five_attempts_per_minute(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'tenant-rate']);

        User::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'rate@example.com',
            'password' => Hash::make('correct-password'),
        ]);

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $response = $this
                ->withHeader('X-Tenant', $tenant->slug)
                ->postJson('/api/v1/auth/login', [
                    'email' => 'rate@example.com',
                    'password' => 'wrong-password',
                ]);

            $response->assertUnprocessable();
        }

        $limitedResponse = $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->postJson('/api/v1/auth/login', [
                'email' => 'rate@example.com',
                'password' => 'wrong-password',
            ]);

        $limitedResponse->assertStatus(429);
    }

    public function test_token_can_be_revoked_with_logout(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'tenant-revoke']);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'revoke@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $loginResponse = $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->postJson('/api/v1/auth/login', [
                'email' => $user->email,
                'password' => 'secret123',
                'device_name' => 'revocation-test',
            ])
            ->assertOk();

        $token = $loginResponse->json('access_token');

        $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertOk();

        Auth::forgetGuards();

        $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertUnauthorized();
    }

    public function test_expired_token_is_rejected(): void
    {
        Config::set('sanctum.expiration', 1);

        $tenant = Tenant::factory()->create(['slug' => 'tenant-expired']);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $plainTextToken = $user->createToken('expired-test', ['*'])->plainTextToken;

        $tokenId = (int) explode('|', $plainTextToken)[0];

        PersonalAccessToken::query()
            ->whereKey($tokenId)
            ->update([
                'created_at' => Carbon::now()->subMinutes(10),
            ]);

        $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->withToken($plainTextToken)
            ->getJson('/api/v1/auth/me')
            ->assertUnauthorized();
    }
}
