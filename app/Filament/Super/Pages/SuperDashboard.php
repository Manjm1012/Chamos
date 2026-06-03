<?php

namespace App\Filament\Super\Pages;

use App\Filament\Super\Widgets\PlatformStatsOverview;
use Filament\Pages\Dashboard;

class SuperDashboard extends Dashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Platform Overview';

    public function getColumns(): int|array
    {
        return ['md' => 2, 'xl' => 4];
    }

    public function getWidgets(): array
    {
        return [
            PlatformStatsOverview::class,
        ];
    }
}