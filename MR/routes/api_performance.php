<?php

use App\Http\Controllers\PerformanceMonitoringController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Performance Monitoring API Routes (DFD Processes 3.1, 3.2, 3.3, 3.4)
|--------------------------------------------------------------------------
|
| These routes handle the performance monitoring workflow:
| - Process 3.1: Monitor Server Performance Data
| - Process 3.2: Monitor API Performance
| - Process 3.3: Aggregate Performance Data
| - Process 3.4: Calculate Performance Data
|
*/

Route::prefix('performance')->middleware('api')->group(function () {
    
    // Process 3.1: Monitor Server Performance Data
    Route::post('/monitor/server', [PerformanceMonitoringController::class, 'monitorServer'])->name('performance.monitor.server');
    
    // Process 3.2: Monitor API Performance
    Route::post('/monitor/api', [PerformanceMonitoringController::class, 'monitorApi'])->name('performance.monitor.api');
    Route::post('/monitor/apis', [PerformanceMonitoringController::class, 'monitorAllApis'])->name('performance.monitor.all');
    
    // Process 3.3: Aggregate Performance Data
    Route::post('/aggregate', [PerformanceMonitoringController::class, 'aggregatePerformance'])->name('performance.aggregate');
    
    // Process 3.4: Calculate Performance Data
    Route::post('/calculate', [PerformanceMonitoringController::class, 'calculateDataset'])->name('performance.calculate');
    
    // Complete monitoring workflow
    Route::post('/complete', [PerformanceMonitoringController::class, 'completeMonitoring'])->name('performance.complete');
    
    // Performance datasets
    Route::get('/datasets', [PerformanceMonitoringController::class, 'getDatasets'])->name('performance.datasets');
    Route::get('/datasets/{datasetId}', [PerformanceMonitoringController::class, 'getDataset'])->name('performance.dataset.show');
});
