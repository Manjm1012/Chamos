<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenantId = Tenant::query()->where('slug', 'main-ops')->value('id');

        $records = [
            ['type' => Equipment::TYPE_TRUCK, 'unit_number' => 'TRK-1001', 'brand' => 'Kenworth', 'model' => 'T680', 'status' => Equipment::STATUS_ACTIVE],
            ['type' => Equipment::TYPE_TRUCK, 'unit_number' => 'TRK-1002', 'brand' => 'Freightliner', 'model' => 'Cascadia', 'status' => Equipment::STATUS_ACTIVE],
            ['type' => Equipment::TYPE_TRUCK, 'unit_number' => 'TRK-1003', 'brand' => 'Volvo', 'model' => 'VNL', 'status' => Equipment::STATUS_MAINTENANCE],
            ['type' => Equipment::TYPE_TRUCK, 'unit_number' => 'TRK-1004', 'brand' => 'International', 'model' => 'LT', 'status' => Equipment::STATUS_ACTIVE],
            ['type' => Equipment::TYPE_TRAILER, 'unit_number' => 'TRL-2001', 'brand' => 'Utility', 'model' => '3000R', 'status' => Equipment::STATUS_ACTIVE],
            ['type' => Equipment::TYPE_TRAILER, 'unit_number' => 'TRL-2002', 'brand' => 'Great Dane', 'model' => 'Champion', 'status' => Equipment::STATUS_ACTIVE],
            ['type' => Equipment::TYPE_TRAILER, 'unit_number' => 'TRL-2003', 'brand' => 'Wabash', 'model' => 'Duraplate', 'status' => Equipment::STATUS_OUT_OF_SERVICE],
            ['type' => Equipment::TYPE_TRAILER, 'unit_number' => 'TRL-2004', 'brand' => 'Hyundai', 'model' => 'Dry Van', 'status' => Equipment::STATUS_MAINTENANCE],
        ];

        foreach ($records as $record) {
            $record['tenant_id'] = $tenantId;
            Equipment::query()->updateOrCreate(['unit_number' => $record['unit_number']], $record);
        }

        Equipment::factory(10)->create(['tenant_id' => $tenantId]);
    }
}
