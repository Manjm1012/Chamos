<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use App\Models\MaintenanceItem;
use Filament\Widgets\ChartWidget;

class CostsByCategoryChart extends ChartWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Cost by System (Last 6 Months)';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();

        $results = Maintenance::query()
            ->whereNotNull('category')
            ->where('date', '>=', $sixMonthsAgo)
            ->selectRaw('category, SUM(cost) as total_cost, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total_cost')
            ->get();

        $labels  = [];
        $costs   = [];
        $counts  = [];
        $catLabels = MaintenanceItem::categoryLabels();
        $colors  = [
            '#e74c3c', '#e67e22', '#f39c12', '#27ae60', '#2980b9',
            '#8e44ad', '#16a085', '#2c3e50', '#d35400', '#7f8c8d',
            '#c0392b', '#1abc9c', '#34495e',
        ];

        foreach ($results as $i => $row) {
            $labels[] = $catLabels[$row->category] ?? $row->category;
            $costs[]  = (float) $row->total_cost;
            $counts[] = $row->count;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Total Cost (USD)',
                    'data'            => $costs,
                    'backgroundColor' => array_slice($colors, 0, count($costs)),
                    'borderWidth'     => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'ticks' => [
                        'callback' => 'function(value){ return "$" + value.toLocaleString(); }',
                    ],
                ],
            ],
        ];
    }
}
