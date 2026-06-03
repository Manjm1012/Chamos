<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Maintenance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $descriptions = [
            'Engine oil and filter change',
            'Brake adjustment and calibration',
            'Tire replacement and balancing',
            'Electrical system diagnostics',
            'Full chassis and safety inspection',
        ];

        foreach (Equipment::query()->get() as $equipment) {
            $baseOdometer = fake()->numberBetween(30_000, 450_000);
            $maintenanceCount = fake()->numberBetween(4, 9);

            for ($index = 0; $index < $maintenanceCount; $index++) {
                $maintenanceDate = now()->subMonths($maintenanceCount - $index)->subDays(fake()->numberBetween(0, 20));
                $baseOdometer += fake()->numberBetween(7_000, 18_000);

                Maintenance::query()->create([
                    'tenant_id' => $equipment->tenant_id,
                    'equipment_id' => $equipment->id,
                    'date' => $maintenanceDate,
                    'type' => fake()->randomElement([Maintenance::TYPE_PREVENTIVE, Maintenance::TYPE_CORRECTIVE]),
                    'status' => $index === $maintenanceCount - 1
                        ? fake()->randomElement([
                            Maintenance::STATUS_IN_PROGRESS,
                            Maintenance::STATUS_PENDING,
                            Maintenance::STATUS_COMPLETED,
                        ])
                        : Maintenance::STATUS_COMPLETED,
                    'odometer_hours' => $baseOdometer,
                    'description' => fake()->randomElement($descriptions),
                    'cost' => fake()->randomFloat(2, 120, 3200),
                    'performed_by' => fake()->randomElement(['In-house workshop', 'Authorized vendor', 'Night shift technician']),
                    'next_maintenance_date' => fake()->boolean(75) ? $maintenanceDate->copy()->addDays(fake()->numberBetween(30, 90)) : null,
                    'next_maintenance_odometer' => fake()->boolean(70) ? $baseOdometer + fake()->numberBetween(4_000, 15_000) : null,
                ]);
            }
        }
    }
}
