<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Widgets\ChartWidget;

class MaintenanceTypeChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'mb-4',
        ];
    }

    protected ?string $heading = 'Preventive vs Corrective';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $preventive = Maintenance::query()->where('type', Maintenance::TYPE_PREVENTIVE)->count();
        $corrective = Maintenance::query()->where('type', Maintenance::TYPE_CORRECTIVE)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Maintenance distribution',
                    'data' => [$preventive, $corrective],
                    'backgroundColor' => ['#27ae60', '#d35400'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['Preventive', 'Corrective'],
        ];
    }
}
