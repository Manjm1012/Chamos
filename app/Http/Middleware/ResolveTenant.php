<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = null;

        if ($request->user('sanctum')?->tenant_id) {
            $tenant = Tenant::query()->find($request->user('sanctum')->tenant_id);
        }

        if (! $tenant) {
            $tenantKey = $request->header('X-Tenant');

            if ($tenantKey) {
                $tenant = Tenant::query()
                    ->where('slug', $tenantKey)
                    ->orWhere('id', $tenantKey)
                    ->first();
            }
        }

        if (! $tenant) {
            return new JsonResponse([
                'message' => 'Tenant context is required. Send X-Tenant header or authenticate with a tenant user.',
            ], 400);
        }

        if (! $tenant->is_active) {
            return new JsonResponse([
                'message' => 'Tenant is inactive.',
            ], 403);
        }

        TenantContext::setTenant($tenant);

        try {
            return $next($request);
        } finally {
            TenantContext::clear();
        }
    }
}
