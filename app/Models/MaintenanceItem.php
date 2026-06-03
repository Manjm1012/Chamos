<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class MaintenanceItem extends Model
{
    // ── Categories ────────────────────────────────────────────────────────────
    public const CAT_PREVENTIVE      = 'preventive_maintenance';
    public const CAT_ENGINE          = 'engine';
    public const CAT_EMISSIONS       = 'emissions';
    public const CAT_TRANSMISSION    = 'transmission';
    public const CAT_BRAKES          = 'brake_system';
    public const CAT_SUSPENSION      = 'suspension';
    public const CAT_WHEELS          = 'wheels_tires';
    public const CAT_ELECTRICAL      = 'electrical';
    public const CAT_HVAC            = 'hvac';
    public const CAT_FIFTH_WHEEL     = 'fifth_wheel';
    public const CAT_TRAILER         = 'trailer';
    public const CAT_REEFER          = 'reefer';
    public const CAT_EMERGENCY       = 'emergency';

    // ── Applies-to values ────────────────────────────────────────────────────
    public const APPLIES_TRUCK   = 'truck';
    public const APPLIES_TRAILER = 'trailer';
    public const APPLIES_BOTH    = 'both';

    protected $fillable = [
        'category',
        'name',
        'applies_to',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Human-readable category labels.
     *
     * @return array<string, string>
     */
    public static function categoryLabels(): array
    {
        return [
            self::CAT_PREVENTIVE   => 'Preventive Maintenance (PM)',
            self::CAT_ENGINE       => 'Engine System',
            self::CAT_EMISSIONS    => 'Emissions / Aftertreatment',
            self::CAT_TRANSMISSION => 'Transmission & Driveline',
            self::CAT_BRAKES       => 'Brake System',
            self::CAT_SUSPENSION   => 'Suspension & Steering',
            self::CAT_WHEELS       => 'Wheels & Tires',
            self::CAT_ELECTRICAL   => 'Electrical System',
            self::CAT_HVAC         => 'HVAC System',
            self::CAT_FIFTH_WHEEL  => 'Fifth Wheel',
            self::CAT_TRAILER      => 'Trailer Maintenance',
            self::CAT_REEFER       => 'Reefer / Thermo King',
            self::CAT_EMERGENCY    => 'Emergency / Road Service',
        ];
    }

    /**
     * Items grouped by category for a given equipment type.
     *
     * @return array<string, array<string, string>>
     */
    public static function groupedForEquipment(string $equipmentType): array
    {
        $appliesToFilter = $equipmentType === self::APPLIES_TRUCK
            ? [self::APPLIES_TRUCK, self::APPLIES_BOTH]
            : [self::APPLIES_TRAILER, self::APPLIES_BOTH];

        $items = static::query()
            ->where('is_active', true)
            ->whereIn('applies_to', $appliesToFilter)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $grouped = [];
        $labels  = static::categoryLabels();

        foreach ($items as $item) {
            $catLabel = $labels[$item->category] ?? $item->category;
            $grouped[$catLabel][$item->name] = $item->name;
        }

        return $grouped;
    }

    /**
     * Items for a specific category, keyed name => name.
     *
     * @return array<string, string>
     */
    public static function optionsForCategory(string $category, ?string $equipmentType = null): array
    {
        $query = static::query()
            ->where('is_active', true)
            ->where('category', $category)
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($equipmentType) {
            $applies = $equipmentType === self::APPLIES_TRUCK
                ? [self::APPLIES_TRUCK, self::APPLIES_BOTH]
                : [self::APPLIES_TRAILER, self::APPLIES_BOTH];

            $query->whereIn('applies_to', $applies);
        }

        return $query->pluck('name', 'name')->toArray();
    }
}
