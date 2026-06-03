<?php

namespace Tests\Feature\Api;

use App\Models\Equipment;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TenantIsolationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_equipment_from_another_tenant(): void
    {
        $tenantA = Tenant::factory()->create(['slug' => 'tenant-a']);
        $tenantB = Tenant::factory()->create(['slug' => 'tenant-b']);

        $userA = User::factory()->create([
            'tenant_id' => $tenantA->id,
            'role' => 'admin',
        ]);

        Equipment::factory()->create([
            'tenant_id' => $tenantA->id,
            'unit_number' => 'TRK-A001',
        ]);

        Equipment::factory()->create([
            'tenant_id' => $tenantB->id,
            'unit_number' => 'TRK-B001',
        ]);

        Sanctum::actingAs($userA, ['*']);

        $response = $this
            ->withHeader('X-Tenant', $tenantA->slug)
            ->getJson('/api/v1/equipment');

        $response->assertOk();

        $unitNumbers = collect($response->json('data'))->pluck('unit_number')->all();

        $this->assertContains('TRK-A001', $unitNumbers);
        $this->assertNotContains('TRK-B001', $unitNumbers);
    }

    public function test_request_without_tenant_header_uses_authenticated_user_tenant_context(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'tenant-a']);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/v1/equipment');

        $response->assertOk();
    }

    public function test_request_with_inactive_tenant_is_forbidden(): void
    {
        $tenant = Tenant::factory()->create([
            'slug' => 'tenant-a',
            'is_active' => false,
        ]);

        $response = $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->postJson('/api/v1/auth/login', [
                'email' => 'nobody@example.com',
                'password' => 'invalid',
            ]);

        $response->assertForbidden();
    }
}
