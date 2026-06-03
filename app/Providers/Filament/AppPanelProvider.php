<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\CostsByEquipmentTable;
use App\Filament\Widgets\CostsByCategoryChart;
use App\Filament\Widgets\FleetStatsOverview;
use App\Filament\Widgets\InspectionAlertsTable;
use App\Filament\Widgets\MaintenanceTypeChart;
use App\Filament\Widgets\MonthlyCostsChart;
use App\Filament\Widgets\TopServiceItemsTable;
use App\Filament\Widgets\UpcomingMaintenancesTable;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                CostsByEquipmentTable::class,
                FleetStatsOverview::class,
                MonthlyCostsChart::class,
                MaintenanceTypeChart::class,
                CostsByCategoryChart::class,
                TopServiceItemsTable::class,
                InspectionAlertsTable::class,
                UpcomingMaintenancesTable::class,
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('English')
                    ->url(fn (): string => route('locale.switch', ['locale' => 'en'])),
                MenuItem::make()
                    ->label('Español')
                    ->url(fn (): string => route('locale.switch', ['locale' => 'es'])),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
