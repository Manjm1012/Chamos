<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    /** @use HasFactory<\Database\Factories\EquipmentFactory> */
    use BelongsToTenant;
    use HasFactory;

    public const TYPE_TRUCK = 'truck';
    public const TYPE_TRAILER = 'trailer';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_OUT_OF_SERVICE = 'out_of_service';

    protected $table = 'equipment';

    protected $fillable = [
        'tenant_id',
        'type',
        'unit_number',
        'brand',
        'model',
        'status',
    ];

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class)->orderByDesc('date');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
