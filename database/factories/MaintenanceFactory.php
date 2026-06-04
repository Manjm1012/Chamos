<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\Maintenance;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Maintenance>
 */
class MaintenanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tenant = Tenant::factory();
        $odometer = $this->faker->numberBetween(10_000, 600_000);
        $nextOdometer = $odometer + $this->faker->numberBetween(8_000, 20_000);
        $date = $this->faker->dateTimeBetween('-18 months', 'now');

        return [
            'tenant_id' => $tenant,
            'equipment_id' => Equipment::factory()->state([
                'tenant_id' => $tenant,
            ]),
            'date' => $date,
            'type' => $this->faker->randomElement([Maintenance::TYPE_PREVENTIVE, Maintenance::TYPE_CORRECTIVE]),
            'status' => $date > now()
                ? Maintenance::STATUS_PENDING
                : $this->faker->randomElement([
                    Maintenance::STATUS_COMPLETED,
                    Maintenance::STATUS_COMPLETED,
                    Maintenance::STATUS_IN_PROGRESS,
                    Maintenance::STATUS_PENDING,
                ]),
            'odometer_hours' => $odometer,
            'description' => $this->faker->randomElement([
                'Engine oil and filter change',
                'Brake adjustment and inspection',
                'Tire replacement on drive axle',
                'Electrical diagnostics and repair',
                'Full preventive inspection',
            ]),
            'cost' => $this->faker->randomFloat(2, 80, 2500),
            'performed_by' => $this->faker->randomElement(['In-house workshop', 'External provider', 'Shift mechanic A', 'Shift mechanic B']),
            'next_maintenance_date' => $this->faker->optional(0.75)->dateTimeBetween($date, '+90 days'),
            'next_maintenance_odometer' => $this->faker->optional(0.7)->numberBetween($odometer + 2_000, $nextOdometer),
        ];
    }
}
