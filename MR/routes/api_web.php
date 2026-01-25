<?php

use App\Http\Controllers\Api\Frontend\AlertDataController;
use App\Http\Controllers\Api\Frontend\ApiDataController;
use App\Http\Controllers\Api\Frontend\ClientDataController;
use App\Http\Controllers\Api\Frontend\DashboardDataController;
use App\Http\Controllers\Api\Frontend\OperationDataController;
use App\Http\Controllers\Api\Frontend\SystemDataController;
use App\Http\Controllers\Api\Frontend\TokenDataController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\SecurityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Web Routes (for frontend data fetching)
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth'])->group(function () {
    // Dashboard level statistics and notifications
    Route::get('/dashboard/statistics', [SystemDataController::class, 'dashboardStatistics']);
    Route::get('/dashboard/charts', [SystemDataController::class, 'dashboardCharts']);
    Route::get('/dashboard/system-status', [SystemDataController::class, 'systemStatus']);
    Route::get('/notifications', [SystemDataController::class, 'notifications']);
    Route::get('/monitoring/sessions', [SystemDataController::class, 'monitoringSessions']);
    Route::get('/cameras', [SystemDataController::class, 'cameras']);

    // Clients API
    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientDataController::class, 'index']);
        Route::get('/statistics', [ClientDataController::class, 'statistics']);
        Route::delete('/{client}', [ClientController::class, 'destroy']);
    });

    // Operations API
    Route::prefix('operations')->group(function () {
        Route::get('/', [OperationDataController::class, 'index']);
        Route::get('/statistics', [OperationDataController::class, 'statistics']);
        Route::get('/recent', [OperationDataController::class, 'recent']);
        Route::delete('/{operation}', [OperationController::class, 'destroy']);
    });

    // APIs API
    Route::prefix('apis')->group(function () {
        Route::get('/', [ApiDataController::class, 'index']);
        Route::get('/statistics', [ApiDataController::class, 'statistics']);
        Route::post('/{api}/test', [ApiController::class, 'test']);
        Route::delete('/{api}', [ApiController::class, 'destroy']);
    });

    // Dashboards API
    Route::prefix('dashboards')->group(function () {
        Route::get('/', [DashboardDataController::class, 'index']);
        Route::get('/statistics', [DashboardDataController::class, 'statistics']);
        Route::post('/{dashboard}/duplicate', [DashboardDataController::class, 'duplicate']);
    });

    // Alerts API
    Route::prefix('alerts')->group(function () {
        Route::get('/', [AlertDataController::class, 'index']);
        Route::get('/statistics', [AlertDataController::class, 'statistics']);
        Route::post('/{alert}/acknowledge', [AlertDataController::class, 'acknowledge']);
        Route::post('/{alert}/resolve', [AlertDataController::class, 'resolve']);
        Route::post('/{alert}/dismiss', [AlertDataController::class, 'dismiss']);
    });

    // Security API
    Route::prefix('security')->group(function () {
        Route::get('/status', [SecurityController::class, 'systemStatus']);
        Route::get('/audit-log', [SecurityController::class, 'auditLog']);
        Route::post('/generate-token', [SecurityController::class, 'generateToken']);
        Route::post('/check', [SecurityController::class, 'securityCheck']);
    });

    // Tokens API
    Route::prefix('tokens')->group(function () {
        Route::get('/', [TokenDataController::class, 'index']);
        Route::get('/statistics', [TokenDataController::class, 'statistics']);
        Route::get('/recent', [TokenDataController::class, 'recent']);
    });
});
