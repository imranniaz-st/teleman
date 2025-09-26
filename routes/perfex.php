<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {
    Route::get('perfex', [PerfexController::class, 'index'])->name('perfex.index');
    Route::post('perfex/store', [PerfexController::class, 'store'])->name('perfex.store');
    Route::post('perfex/token', [PerfexController::class, 'generate_token'])->name('perfex.generate.token');
    
    Route::get('perfex/fetch_data', [PerfexController::class, 'fetch_data'])->name('perfex.fetch.data');
    Route::get('perfex/store_data', [PerfexController::class, 'store_to_database'])->name('perfex.fetch.store');
});
