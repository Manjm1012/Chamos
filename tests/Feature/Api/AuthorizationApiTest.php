<?php

namespace Tests\Feature\Api;

use App\Models\Equipment;
use App\Models\Maintenance;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthorizationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_operative_cannot_delete_equipment(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'tenant-a']);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'operative',
        ]);

        $equipment = Equipment::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        Sanctum::actingAs($user, ['equipment:read', 'equipment:write']);

        $response = $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->deleteJson('/api/v1/equipment/' . $equipment->id);

        $response->assertForbidden();
    }

    public function test_admin_can_delete_equipment(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'tenant-a']);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'admin',
        ]);

        $equipment = Equipment::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->deleteJson('/api/v1/equipment/' . $equipment->id);

        $response->assertNoContent();
    }

    public function test_operative_can_create_maintenance_with_valid_ability(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'tenant-a']);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'operative',
        ]);

        $equipment = Equipment::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        Sanctum::actingAs($user, ['maintenance:write']);

        $payload = [
            'equipment_id' => $equipment->id,
            'date' => now()->toDateString(),
            'type' => Maintenance::TYPE_PREVENTIVE,
            'status' => Maintenance::STATUS_IN_PROGRESS,
            'odometer_hours' => 150000,
            'description' => 'Preventive maintenance cycle',
            'cost' => 450.35,
            'performed_by' => 'Workshop A',
        ];

        $response = $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->postJson('/api/v1/maintenances', $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('maintenances', [
            'equipment_id' => $equipment->id,
            'tenant_id' => $tenant->id,
            'type' => Maintenance::TYPE_PREVENTIVE,
            'status' => Maintenance::STATUS_IN_PROGRESS,
        ]);
    }
}
