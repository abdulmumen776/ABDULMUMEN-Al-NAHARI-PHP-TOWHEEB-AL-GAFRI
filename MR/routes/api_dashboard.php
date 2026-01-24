<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard API Routes (DFD Processes 4.1, 4.2, 4.3, 4.4)
|--------------------------------------------------------------------------
|
| These routes handle the dashboard generation and rendering workflow:
| - Process 4.1: Format Metrics Data
| - Process 4.2: Generate Dashboard Metrics
| - Process 4.3: Render Dashboard Components
| - Process 4.4: Render Dashboard Components (Alerts)
|
*/

Route::prefix('dashboard')->middleware('api')->group(function () {
    
    // Dashboard listing and management
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/{dashboard}', [DashboardController::class, 'show'])->name('dashboard.show');
    
    // Process 4.1: Format Metrics Data
    Route::post('/format-metrics', [DashboardController::class, 'formatMetrics'])->name('dashboard.format.metrics');
    
    // Process 4.2: Generate Dashboard Metrics
    Route::post('/generate-metrics', [DashboardController::class, 'generateMetrics'])->name('dashboard.generate.metrics');
    
    // Process 4.3: Render Dashboard Components
    Route::post('/{dashboardId}/render-components', [DashboardController::class, 'renderComponents'])->name('dashboard.render.components');
    
    // Process 4.4: Render Alerts
    Route::post('/render-alerts', [DashboardController::class, 'renderAlerts'])->name('dashboard.render.alerts');
    
    // Complete dashboard generation workflow
    Route::post('/{dashboardId}/generate-complete', [DashboardController::class, 'generateComplete'])->name('dashboard.generate.complete');
});
