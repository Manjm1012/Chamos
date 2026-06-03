<?php

namespace Tests\Feature\Api;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_tenant_context_and_credentials(): void
    {
        $tenant = Tenant::factory()->create([
            'slug' => 'tenant-a',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'api.user@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'operative',
        ]);

        $response = $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->postJson('/api/v1/auth/login', [
                'email' => $user->email,
                'password' => 'secret123',
                'device_name' => 'phpunit',
            ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'token_type',
                'access_token',
                'abilities',
                'user' => ['id', 'email', 'role', 'tenant_id'],
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_fails_with_wrong_tenant_header(): void
    {
        $tenantA = Tenant::factory()->create(['slug' => 'tenant-a']);
        Tenant::factory()->create(['slug' => 'tenant-b']);

        $user = User::factory()->create([
            'tenant_id' => $tenantA->id,
            'email' => 'tenant.user@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this
            ->withHeader('X-Tenant', 'tenant-b')
            ->postJson('/api/v1/auth/login', [
                'email' => $user->email,
                'password' => 'secret123',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_me_endpoint_requires_authentication(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'tenant-a']);

        $response = $this
            ->withHeader('X-Tenant', $tenant->slug)
            ->getJson('/api/v1/auth/me');

        $response->assertUnauthorized();
    }
}
