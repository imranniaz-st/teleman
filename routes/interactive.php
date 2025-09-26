<?php

use App\Http\Controllers\InteractiveController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified', 'kyc.verified'], 'prefix' => 'dashboard'], function () {
    Route::get('/interactivity', [InteractiveController::class, 'index'])->name('dashboard.interactive.index'); // Route name: dashboard.interactive.index
    Route::get('/interactivity/create/{department_id}', [InteractiveController::class, 'create'])->name('dashboard.interactive.create'); // Route name: dashboard.interactive.create
    Route::post('/interactivity/store', [InteractiveController::class, 'store'])->name('dashboard.interactive.store'); // Route name: dashboard.interactive.create
});

// version 3.0.0
Route::post('/process-response', [InteractiveController::class, 'processResponse'])->name('dialer.processResponse');