<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Load action processing routes
require __DIR__.'/api_actions.php';

// Load performance monitoring routes
require __DIR__.'/api_performance.php';

// Load dashboard routes
require __DIR__.'/api_dashboard.php';

// Load pattern analysis and alert management routes
require __DIR__.'/api_patterns.php';

// Load web API routes for frontend
require __DIR__.'/api_web.php';

// Load existing system routes (performance monitoring, dashboards, etc.)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
