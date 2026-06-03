<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    /** @use HasFactory<\Database\Factories\MaintenanceFactory> */
    use BelongsToTenant;
    use HasFactory;

    public const TYPE_PREVENTIVE = 'preventive';
    public const TYPE_CORRECTIVE = 'corrective';

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'tenant_id',
        'equipment_id',
        'date',
        'type',
        'category',
        'service_item',
        'equipment_system',
        'status',
        'odometer_hours',
        'description',
        'cost',
        'parts_cost',
        'labor_cost',
        'performed_by',
        'vendor',
        'invoice_number',
        'warranty_expiry',
        'next_maintenance_date',
        'next_maintenance_odometer',
    ];

    protected function casts(): array
    {
        return [
            'date'                 => 'date',
            'next_maintenance_date' => 'date',
            'warranty_expiry'      => 'date',
            'cost'                 => 'decimal:2',
            'parts_cost'           => 'decimal:2',
            'labor_cost'           => 'decimal:2',
        ];
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->whereDate('date', '>=', now()->subDays($days));
    }

    public function scopeUpcomingDate(Builder $query, int $days = 15): Builder
    {
        return $query
            ->whereNotNull('next_maintenance_date')
            ->whereBetween('next_maintenance_date', [now()->toDateString(), now()->addDays($days)->toDateString()]);
    }

    public function scopeUpcomingOdometer(Builder $query, int $threshold = 1000): Builder
    {
        return $query
            ->whereNotNull('next_maintenance_odometer')
            ->whereRaw('(next_maintenance_odometer - odometer_hours) <= ?', [$threshold])
            ->whereRaw('(next_maintenance_odometer - odometer_hours) >= 0');
    }

    public function scopeUpcoming(Builder $query, int $days = 15, int $threshold = 1000): Builder
    {
        return $query->where(function (Builder $innerQuery) use ($days, $threshold): void {
            $innerQuery
                ->upcomingDate($days)
                ->orWhere(function (Builder $odometerQuery) use ($threshold): void {
                    $odometerQuery->upcomingOdometer($threshold);
                });
        });
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
}
