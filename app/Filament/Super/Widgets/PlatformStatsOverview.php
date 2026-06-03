<?php

namespace App\Filament\Super\Widgets;

use App\Models\Equipment;
use App\Models\Maintenance;
use App\Models\Tenant;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $activeTenants   = Tenant::query()->where('is_active', true)->count();
        $totalTenants    = Tenant::query()->count();
        $totalUsers      = User::query()->whereIn('role', ['admin', 'operative'])->count();
        $totalEquipment  = Equipment::withoutGlobalScopes()->count();
        $totalMaint      = Maintenance::withoutGlobalScopes()->count();

        return [
            Stat::make('Active Tenants', $activeTenants)
                ->description("$totalTenants total registered")
                ->color('success'),
            Stat::make('Platform Users', $totalUsers)
                ->description('Admin + Operative roles')
                ->color('primary'),
            Stat::make('Total Equipment', $totalEquipment)
                ->description('Across all tenants')
                ->color('warning'),
            Stat::make('Total Maintenances', $totalMaint)
                ->description('All historical records')
                ->color('info'),
        ];
    }
}