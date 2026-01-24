<?php

use App\Http\Controllers\ActionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Action Processing API Routes (DFD Processes 1.1, 1.2, 2.1, 2.2)
|--------------------------------------------------------------------------
|
| These routes handle the core action processing workflow:
| - Process 1.1: Receive Action
| - Process 1.2: Extract Metadata  
| - Process 2.1: Validate Client Data
| - Process 2.2: Validate Operands Data
|
*/

Route::prefix('actions')->middleware('api')->group(function () {
    
    // Process 1.1: Receive Action from client
    Route::post('/receive', [ActionController::class, 'receive'])->name('actions.receive');
    
    // Process 2.1 & 2.2: Validate action data
    Route::post('/{actionId}/validate', [ActionController::class, 'validate'])->name('actions.validate');
    
    // Get action details with metadata and validation
    Route::get('/{actionId}', [ActionController::class, 'show'])->name('actions.show');
    
    // Get action acknowledgment
    Route::get('/{actionId}/acknowledgment', [ActionController::class, 'acknowledgment'])->name('actions.acknowledgment');
});
