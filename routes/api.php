<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EquipmentController;
use App\Http\Controllers\Api\V1\MaintenanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware(['tenant'])
    ->group(function (): void {
        Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:api-auth');

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::get('auth/me', [AuthController::class, 'me']);
            Route::post('auth/logout', [AuthController::class, 'logout']);

            Route::apiResource('equipment', EquipmentController::class);
            Route::get('equipment/{equipment}/maintenances', [MaintenanceController::class, 'byEquipment']);
            Route::apiResource('maintenances', MaintenanceController::class);
        });
    });
