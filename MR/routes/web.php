<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerformanceMetricController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\ApiTokenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Client Routes
Route::prefix('clients')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/{client}/statistics', [ClientController::class, 'statistics'])->name('clients.statistics');
    Route::get('/{client}/dashboard', [ClientController::class, 'dashboard'])->name('clients.dashboard');
});

// Operation Routes
Route::prefix('operations')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [OperationController::class, 'index'])->name('operations.index');
    Route::get('/create', [OperationController::class, 'create'])->name('operations.create');
    Route::post('/', [OperationController::class, 'store'])->name('operations.store');
    Route::get('/{operation}', [OperationController::class, 'show'])->name('operations.show');
    Route::get('/{operation}/edit', [OperationController::class, 'edit'])->name('operations.edit');
    Route::put('/{operation}', [OperationController::class, 'update'])->name('operations.update');
    Route::delete('/{operation}', [OperationController::class, 'destroy'])->name('operations.destroy');
    Route::get('/{operation}/statistics', [OperationController::class, 'statistics'])->name('operations.statistics');
    Route::get('/{operation}/performance-metrics', [OperationController::class, 'performanceMetrics'])->name('operations.performance-metrics');
    Route::put('/{operation}/status', [OperationController::class, 'updateStatus'])->name('operations.update-status');
    Route::get('/active', [OperationController::class, 'active'])->name('operations.active');
});

// API Routes
Route::prefix('apis')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ApiController::class, 'index'])->name('apis.index');
    Route::get('/create', [ApiController::class, 'create'])->name('apis.create');
    Route::post('/', [ApiController::class, 'store'])->name('apis.store');
    Route::get('/{api}', [ApiController::class, 'show'])->name('apis.show');
    Route::get('/{api}/edit', [ApiController::class, 'edit'])->name('apis.edit');
    Route::put('/{api}', [ApiController::class, 'update'])->name('apis.update');
    Route::delete('/{api}', [ApiController::class, 'destroy'])->name('apis.destroy');
    Route::get('/{api}/statistics', [ApiController::class, 'statistics'])->name('apis.statistics');
    Route::get('/{api}/performance-logs', [ApiController::class, 'performanceLogs'])->name('apis.performance-logs');
    Route::put('/{api}/status', [ApiController::class, 'updateStatus'])->name('apis.update-status');
    Route::post('/{api}/test', [ApiController::class, 'test'])->name('apis.test');
    Route::get('/monitored', [ApiController::class, 'monitored'])->name('apis.monitored');
});

// Dashboard Routes
Route::prefix('dashboards')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboards.index');
    Route::get('/create', [DashboardController::class, 'create'])->name('dashboards.create');
    Route::post('/', [DashboardController::class, 'store'])->name('dashboards.store');
    Route::get('/{dashboard}', [DashboardController::class, 'show'])->name('dashboards.show');
    Route::get('/{dashboard}/edit', [DashboardController::class, 'edit'])->name('dashboards.edit');
    Route::put('/{dashboard}', [DashboardController::class, 'update'])->name('dashboards.update');
    Route::delete('/{dashboard}', [DashboardController::class, 'destroy'])->name('dashboards.destroy');
});

// Performance Metrics Routes
Route::prefix('performance-metrics')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [PerformanceMetricController::class, 'index'])->name('performance-metrics.index');
    Route::get('/create', [PerformanceMetricController::class, 'create'])->name('performance-metrics.create');
    Route::post('/', [PerformanceMetricController::class, 'store'])->name('performance-metrics.store');
    Route::get('/{performanceMetric}', [PerformanceMetricController::class, 'show'])->name('performance-metrics.show');
    Route::get('/{performanceMetric}/edit', [PerformanceMetricController::class, 'edit'])->name('performance-metrics.edit');
    Route::put('/{performanceMetric}', [PerformanceMetricController::class, 'update'])->name('performance-metrics.update');
    Route::delete('/{performanceMetric}', [PerformanceMetricController::class, 'destroy'])->name('performance-metrics.destroy');
});

// Alert Routes
Route::prefix('alerts')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::post('/', [AlertController::class, 'store'])->name('alerts.store');
    Route::get('/{alert}', [AlertController::class, 'show'])->name('alerts.show');
    Route::get('/{alert}/edit', [AlertController::class, 'edit'])->name('alerts.edit');
    Route::put('/{alert}', [AlertController::class, 'update'])->name('alerts.update');
    Route::delete('/{alert}', [AlertController::class, 'destroy'])->name('alerts.destroy');
    Route::put('/{alert}/resolve', [AlertController::class, 'resolve'])->name('alerts.resolve');
});

// Security Routes
Route::prefix('security')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [SecurityController::class, 'index'])->name('security.index');
    Route::get('/create', [SecurityController::class, 'create'])->name('security.create');
    Route::post('/', [SecurityController::class, 'store'])->name('security.store');
    Route::get('/{security}', [SecurityController::class, 'show'])->name('security.show');
    Route::get('/{security}/edit', [SecurityController::class, 'edit'])->name('security.edit');
    Route::put('/{security}', [SecurityController::class, 'update'])->name('security.update');
    Route::delete('/{security}', [SecurityController::class, 'destroy'])->name('security.destroy');
    Route::get('/settings', [SecurityController::class, 'settings'])->name('security.settings');
    Route::put('/settings', [SecurityController::class, 'updateSettings'])->name('security.update-settings');
    Route::get('/audit-log', [SecurityController::class, 'auditLog'])->name('security.audit-log');
    Route::get('/statistics', [SecurityController::class, 'statistics'])->name('security.statistics');
    Route::post('/validate-password', [SecurityController::class, 'validatePassword'])->name('security.validate-password');
    Route::post('/generate-token', [SecurityController::class, 'generateToken'])->name('security.generate-token');
    Route::post('/security-check', [SecurityController::class, 'securityCheck'])->name('security.security-check');
});

// API Token Routes
Route::prefix('tokens')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ApiTokenController::class, 'index'])->name('tokens.index');
    Route::get('/create', [ApiTokenController::class, 'create'])->name('tokens.create');
    Route::post('/', [ApiTokenController::class, 'store'])->name('tokens.store');
    Route::get('/{token}', [ApiTokenController::class, 'show'])->name('tokens.show');
    Route::get('/{token}/edit', [ApiTokenController::class, 'edit'])->name('tokens.edit');
    Route::put('/{token}', [ApiTokenController::class, 'update'])->name('tokens.update');
    Route::delete('/{token}', [ApiTokenController::class, 'destroy'])->name('tokens.destroy');
    Route::post('/{token}/revoke', [ApiTokenController::class, 'revoke'])->name('tokens.revoke');
    Route::get('/statistics', [ApiTokenController::class, 'statistics'])->name('tokens.statistics');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
