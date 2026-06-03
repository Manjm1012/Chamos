<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement([Equipment::TYPE_TRUCK, Equipment::TYPE_TRAILER]);

        return [
            'tenant_id' => Tenant::factory(),
            'type' => $type,
            'unit_number' => strtoupper($type === Equipment::TYPE_TRUCK ? 'TRK-' : 'TRL-') . fake()->unique()->numberBetween(1000, 9999),
            'brand' => fake()->optional(0.9)->randomElement(['Kenworth', 'Freightliner', 'Volvo', 'International', 'Utility']),
            'model' => fake()->optional(0.8)->bothify('Model-##??'),
            'status' => fake()->randomElement([
                Equipment::STATUS_ACTIVE,
                Equipment::STATUS_ACTIVE,
                Equipment::STATUS_MAINTENANCE,
                Equipment::STATUS_OUT_OF_SERVICE,
            ]),
        ];
    }
}
