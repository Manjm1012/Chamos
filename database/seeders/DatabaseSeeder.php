<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super admin (platform owner — no tenant)
        User::query()->updateOrCreate(
            ['email' => 'super@chamos.local'],
            [
                'tenant_id' => null,
                'name' => 'Super Admin',
                'role' => 'super_admin',
                'password' => Hash::make('superpassword'),
                'email_verified_at' => now(),
            ],
        );

        $tenant = Tenant::query()->updateOrCreate(
            ['slug' => 'main-ops'],
            [
                'name' => 'Main Operations',
                'is_active' => true,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@fleet.local'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Fleet Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'operative@fleet.local'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Fleet Operative',
                'role' => 'operative',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        User::factory(3)->create([
            'tenant_id' => $tenant->id,
            'role' => 'operative',
        ]);

        $this->call([
            EquipmentSeeder::class,
            MaintenanceSeeder::class,
            MaintenanceCatalogSeeder::class,
        ]);
    }
}
