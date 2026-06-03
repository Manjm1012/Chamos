<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Widgets\ChartWidget;

class MonthlyCostsChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;
    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'mb-4',
        ];
    }

    protected ?string $heading = 'Monthly Maintenance Costs';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $labels = [];
        $values = [];

        for ($offset = 5; $offset >= 0; $offset--) {
            $monthStart = now()->subMonths($offset)->startOfMonth();
            $monthEnd = now()->subMonths($offset)->endOfMonth();

            $labels[] = $monthStart->format('M Y');
            $values[] = (float) Maintenance::query()
                ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->sum('cost');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cost (USD)',
                    'data' => $values,
                    'borderColor' => '#f07f1f',
                    'backgroundColor' => 'rgba(240, 127, 31, 0.2)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
