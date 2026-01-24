<?php

use App\Http\Controllers\PatternAnalysisController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pattern Analysis & Alert Management API Routes (DFD Processes 5.2, 5.3, 6.1)
|--------------------------------------------------------------------------
|
| These routes handle the pattern analysis and alert management workflow:
| - Process 5.2: Analyze Patterns (from External APIs & Dashboard Metrics)
| - Process 5.3: Analyze Patterns (General)
| - Process 6.1: Manage Alerts
|
*/

Route::prefix('patterns')->middleware('api')->group(function () {
    
    // Process 5.2: Analyze Patterns from External APIs
    Route::post('/analyze-api', [PatternAnalysisController::class, 'analyzeApiPatterns'])->name('patterns.analyze.api');
    
    // Process 5.2: Analyze Patterns from Dashboard Metrics
    Route::post('/analyze-dashboard', [PatternAnalysisController::class, 'analyzeDashboardPatterns'])->name('patterns.analyze.dashboard');
    
    // Process 5.3: Analyze Patterns (General)
    Route::post('/analyze', [PatternAnalysisController::class, 'analyzePatterns'])->name('patterns.analyze.general');
    
    // Process 6.1: Manage Alerts
    Route::post('/manage-alerts', [PatternAnalysisController::class, 'manageAlerts'])->name('patterns.manage.alerts');
    
    // Complete pattern analysis and alert management workflow
    Route::post('/complete-workflow', [PatternAnalysisController::class, 'completeWorkflow'])->name('patterns.complete.workflow');
    
    // Pattern analysis results
    Route::get('/analysis-results', [PatternAnalysisController::class, 'getAnalysisResults'])->name('patterns.analysis.results');
    Route::get('/analysis-results/{analysisId}', [PatternAnalysisController::class, 'getAnalysisResult'])->name('patterns.analysis.result.show');
});

Route::prefix('alerts')->middleware('api')->group(function () {
    
    // Alert management
    Route::get('/active', [PatternAnalysisController::class, 'getActiveAlerts'])->name('alerts.active');
    Route::post('/{alertId}/resolve', [PatternAnalysisController::class, 'resolveAlert'])->name('alerts.resolve');
    Route::get('/statistics', [PatternAnalysisController::class, 'getAlertStatistics'])->name('alerts.statistics');
});
