<?php

namespace Database\Seeders;

use App\Models\MaintenanceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // ── PREVENTIVE MAINTENANCE ───────────────────────────────────────
            [MaintenanceItem::CAT_PREVENTIVE, 'PM A Service',                     'both'],
            [MaintenanceItem::CAT_PREVENTIVE, 'PM B Service',                     'both'],
            [MaintenanceItem::CAT_PREVENTIVE, 'PM C Service',                     'both'],
            [MaintenanceItem::CAT_PREVENTIVE, 'Federal Annual Inspection',         'both'],
            [MaintenanceItem::CAT_PREVENTIVE, 'DOT Inspection',                   'both'],
            [MaintenanceItem::CAT_PREVENTIVE, 'Preventive Maintenance Inspection', 'both'],
            [MaintenanceItem::CAT_PREVENTIVE, 'Roadside Inspection Repair',        'both'],
            [MaintenanceItem::CAT_PREVENTIVE, 'Pre-Trip Inspection',               'truck'],
            [MaintenanceItem::CAT_PREVENTIVE, 'Post-Trip Inspection',              'truck'],

            // ── ENGINE ───────────────────────────────────────────────────────
            [MaintenanceItem::CAT_ENGINE, 'Engine Oil Change',             'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Engine Oil Filter Replacement', 'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Fuel Filter Replacement',       'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Primary Fuel Filter Replacement',   'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Secondary Fuel Filter Replacement', 'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Air Filter Replacement',        'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Cabin Air Filter Replacement',  'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Coolant Flush',                 'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Coolant Replacement',           'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Radiator Inspection',           'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Water Pump Replacement',        'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Thermostat Replacement',        'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Belt Replacement',              'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Belt Tensioner Replacement',    'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Hose Replacement',              'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Turbocharger Inspection',       'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Turbocharger Replacement',      'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Intake System Inspection',      'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Exhaust Leak Repair',           'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Engine Diagnostic',             'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Engine Overhaul',               'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Injector Replacement',          'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Valve Adjustment (Overhead)',   'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Compression Test',              'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Oil Leak Repair',               'truck'],
            [MaintenanceItem::CAT_ENGINE, 'Coolant Leak Repair',           'truck'],

            // ── EMISSIONS / AFTERTREATMENT ────────────────────────────────────
            [MaintenanceItem::CAT_EMISSIONS, 'DPF Cleaning',               'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'DPF Replacement',            'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'SCR Inspection',             'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'DOC Inspection',             'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'Forced Regeneration',        'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'DEF Filter Replacement',     'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'DEF Pump Replacement',       'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'DEF Injector Replacement',   'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'NOx Sensor Replacement',     'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'ACM Diagnostic',             'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'EGR Valve Replacement',      'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'EGR Cooler Replacement',     'truck'],
            [MaintenanceItem::CAT_EMISSIONS, 'Aftertreatment Diagnostic',  'truck'],

            // ── TRANSMISSION & DRIVELINE ──────────────────────────────────────
            [MaintenanceItem::CAT_TRANSMISSION, 'Transmission Service',            'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Transmission Oil Replacement',    'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Transmission Filter Replacement', 'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Clutch Adjustment',               'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Clutch Replacement',              'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Driveline Inspection',            'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'U-Joint Replacement',             'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Carrier Bearing Replacement',     'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'PTO Service',                     'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Differential Service',            'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Differential Oil Replacement',    'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Power Divider Service',           'truck'],
            [MaintenanceItem::CAT_TRANSMISSION, 'Axle Seal Replacement',           'truck'],

            // ── BRAKE SYSTEM ──────────────────────────────────────────────────
            [MaintenanceItem::CAT_BRAKES, 'Brake Inspection',           'both'],
            [MaintenanceItem::CAT_BRAKES, 'Brake Pad Replacement',      'truck'],
            [MaintenanceItem::CAT_BRAKES, 'Brake Shoe Replacement',     'both'],
            [MaintenanceItem::CAT_BRAKES, 'Brake Drum Replacement',     'both'],
            [MaintenanceItem::CAT_BRAKES, 'Brake Rotor Replacement',    'truck'],
            [MaintenanceItem::CAT_BRAKES, 'Brake Chamber Replacement',  'both'],
            [MaintenanceItem::CAT_BRAKES, 'Slack Adjuster Replacement', 'both'],
            [MaintenanceItem::CAT_BRAKES, 'Air Leak Repair',            'both'],
            [MaintenanceItem::CAT_BRAKES, 'ABS Diagnostic',             'both'],
            [MaintenanceItem::CAT_BRAKES, 'ABS Sensor Replacement',     'both'],
            [MaintenanceItem::CAT_BRAKES, 'Air Dryer Service',          'truck'],
            [MaintenanceItem::CAT_BRAKES, 'Air Compressor Service',     'truck'],
            [MaintenanceItem::CAT_BRAKES, 'Brake Valve Replacement',    'both'],
            [MaintenanceItem::CAT_BRAKES, 'Wheel Seal Replacement',     'both'],

            // ── SUSPENSION & STEERING ─────────────────────────────────────────
            [MaintenanceItem::CAT_SUSPENSION, 'Suspension Inspection',      'both'],
            [MaintenanceItem::CAT_SUSPENSION, 'Airbag Replacement',         'both'],
            [MaintenanceItem::CAT_SUSPENSION, 'Shock Absorber Replacement', 'truck'],
            [MaintenanceItem::CAT_SUSPENSION, 'Torque Rod Replacement',     'both'],
            [MaintenanceItem::CAT_SUSPENSION, 'Bushing Replacement',        'both'],
            [MaintenanceItem::CAT_SUSPENSION, 'King Pin Replacement',       'truck'],
            [MaintenanceItem::CAT_SUSPENSION, 'Tie Rod Replacement',        'truck'],
            [MaintenanceItem::CAT_SUSPENSION, 'Steering Gear Inspection',   'truck'],
            [MaintenanceItem::CAT_SUSPENSION, 'Steering Pump Replacement',  'truck'],
            [MaintenanceItem::CAT_SUSPENSION, 'Alignment Service',          'both'],

            // ── WHEELS & TIRES ────────────────────────────────────────────────
            [MaintenanceItem::CAT_WHEELS, 'Tire Replacement',          'both'],
            [MaintenanceItem::CAT_WHEELS, 'Tire Rotation',             'both'],
            [MaintenanceItem::CAT_WHEELS, 'Tire Repair',               'both'],
            [MaintenanceItem::CAT_WHEELS, 'Tire Pressure Inspection',  'both'],
            [MaintenanceItem::CAT_WHEELS, 'Wheel Balancing',           'both'],
            [MaintenanceItem::CAT_WHEELS, 'Hub Service',               'both'],
            [MaintenanceItem::CAT_WHEELS, 'Bearing Service',           'both'],
            [MaintenanceItem::CAT_WHEELS, 'Rim Replacement',           'both'],

            // ── ELECTRICAL ────────────────────────────────────────────────────
            [MaintenanceItem::CAT_ELECTRICAL, 'Battery Replacement',      'both'],
            [MaintenanceItem::CAT_ELECTRICAL, 'Alternator Replacement',   'truck'],
            [MaintenanceItem::CAT_ELECTRICAL, 'Starter Replacement',      'truck'],
            [MaintenanceItem::CAT_ELECTRICAL, 'Wiring Repair',            'both'],
            [MaintenanceItem::CAT_ELECTRICAL, 'Lighting Repair',          'both'],
            [MaintenanceItem::CAT_ELECTRICAL, 'Fuse Replacement',         'both'],
            [MaintenanceItem::CAT_ELECTRICAL, 'Electrical Diagnostic',    'both'],
            [MaintenanceItem::CAT_ELECTRICAL, 'Sensor Replacement',       'both'],

            // ── HVAC ──────────────────────────────────────────────────────────
            [MaintenanceItem::CAT_HVAC, 'A/C Service',                      'truck'],
            [MaintenanceItem::CAT_HVAC, 'Heater Repair',                    'truck'],
            [MaintenanceItem::CAT_HVAC, 'Blend Door Actuator Replacement',  'truck'],
            [MaintenanceItem::CAT_HVAC, 'Heater Core Replacement',          'truck'],
            [MaintenanceItem::CAT_HVAC, 'Refrigerant Recharge',             'truck'],

            // ── FIFTH WHEEL ───────────────────────────────────────────────────
            [MaintenanceItem::CAT_FIFTH_WHEEL, 'Fifth Wheel Inspection',    'truck'],
            [MaintenanceItem::CAT_FIFTH_WHEEL, 'Fifth Wheel Grease Service','truck'],
            [MaintenanceItem::CAT_FIFTH_WHEEL, 'Fifth Wheel Repair',        'truck'],
            [MaintenanceItem::CAT_FIFTH_WHEEL, 'Lock Jaw Adjustment',       'truck'],

            // ── TRAILER MAINTENANCE ───────────────────────────────────────────
            [MaintenanceItem::CAT_TRAILER, 'Trailer Inspection',             'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Brake Service',          'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer ABS Repair',             'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Suspension Repair',      'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Airbag Replacement',     'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Hub Service',            'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Wheel Seal Replacement', 'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Tire Replacement',       'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Alignment',              'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Door Repair',            'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Floor Repair',           'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Roof Repair',            'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Trailer Light Repair',           'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Landing Gear Repair',            'trailer'],
            [MaintenanceItem::CAT_TRAILER, 'Mud Flap Replacement',           'trailer'],

            // ── REEFER / THERMO KING ──────────────────────────────────────────
            [MaintenanceItem::CAT_REEFER, 'Reefer Preventive Maintenance',  'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Oil Change',              'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Oil Filter Replacement',  'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Fuel Filter Replacement', 'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Air Filter Replacement',  'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Coolant Service',         'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Belt Replacement',        'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Battery Replacement',     'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Compressor Inspection',   'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Compressor Replacement',  'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Condenser Cleaning',             'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Evaporator Cleaning',            'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Refrigerant Leak Repair',        'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Refrigeration Diagnostic',       'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Temperature Sensor Replacement', 'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Electrical Repair',       'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Unit Calibration',        'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Fuel System Service',     'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Alternator Replacement',  'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Starter Replacement',     'trailer'],
            [MaintenanceItem::CAT_REEFER, 'Reefer Engine Overhaul',         'trailer'],

            // ── EMERGENCY / ROAD SERVICE ──────────────────────────────────────
            [MaintenanceItem::CAT_EMERGENCY, 'Mobile Repair Service',      'both'],
            [MaintenanceItem::CAT_EMERGENCY, 'Emergency Roadside Repair',  'both'],
            [MaintenanceItem::CAT_EMERGENCY, 'Towing Service',             'both'],
            [MaintenanceItem::CAT_EMERGENCY, 'Lockout Service',            'truck'],
            [MaintenanceItem::CAT_EMERGENCY, 'Jump Start Service',         'both'],
            [MaintenanceItem::CAT_EMERGENCY, 'Tire Change (Roadside)',     'both'],
            [MaintenanceItem::CAT_EMERGENCY, 'Fuel Delivery',              'truck'],
        ];

        $now = now();
        $rows = [];

        foreach ($items as $i => [$category, $name, $appliesTo]) {
            $rows[] = [
                'category'   => $category,
                'name'       => $name,
                'applies_to' => $appliesTo,
                'is_active'  => true,
                'sort_order' => $i,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Clear and reload the catalog (catalog is static reference data, never user-generated)
        DB::table('maintenance_items')->delete();

        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('maintenance_items')->insert($chunk);
        }

        $this->command->info('✅ Maintenance catalog: ' . count($rows) . ' items loaded.');
    }
}
