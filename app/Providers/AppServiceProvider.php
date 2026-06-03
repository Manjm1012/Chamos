<?php

namespace App\Providers;

use App\Models\Equipment;
use App\Models\Maintenance;
use App\Policies\EquipmentPolicy;
use App\Policies\MaintenancePolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Equipment::class, EquipmentPolicy::class);
        Gate::policy(Maintenance::class, MaintenancePolicy::class);

        RateLimiter::for('api-auth', function (Request $request): Limit {
            $tenant = (string) $request->header('X-Tenant', 'no-tenant');
            $ip = (string) $request->ip();

            return Limit::perMinute(5)->by($tenant . '|' . $ip);
        });
    }
}
