<?php

use App\Http\Controllers\SquadController;
use Illuminate\Support\Facades\Route;

// backend
Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {
    Route::get('/squad', [SquadController::class, 'index'])->name('squad.index');
    Route::get('/squad/store', [SquadController::class, 'store'])->name('squad.store');
});

// Frontend
Route::get('/squad/success', [SquadController::class, 'payment_success'])->name('squad.success');
Route::get('/squad/cancel', [SquadController::class, 'payment_cancel'])->name('squad.cancel');
