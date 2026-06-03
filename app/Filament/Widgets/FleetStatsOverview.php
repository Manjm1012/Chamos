<?php

namespace App\Filament\Widgets;

use App\Models\Equipment;
use App\Models\Maintenance;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FleetStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'mb-4',
        ];
    }

    protected function getStats(): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $totalEquipment = Equipment::query()->count();
        $monthlyMaintenances = Maintenance::query()
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->count();
        $monthlyCost = Maintenance::query()
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->sum('cost');

        $mostMaintained = Equipment::query()
            ->withCount('maintenances')
            ->orderByDesc('maintenances_count')
            ->first();

        return [
            Stat::make('Total equipment', number_format($totalEquipment))
                ->description('Active + non-active units'),
            Stat::make('Maintenances this month', number_format($monthlyMaintenances))
                ->description('Records in current month'),
            Stat::make('Total monthly cost', '$' . number_format((float) $monthlyCost, 2))
                ->description('Preventive and corrective cost'),
            Stat::make(
                'Most maintained unit',
                $mostMaintained?->unit_number ? "{$mostMaintained->unit_number} ({$mostMaintained->maintenances_count})" : 'N/A',
            )->description('Unit with the highest maintenance count'),
        ];
    }
}
