<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {
    Route::get('wp', [WordPressController::class, 'index'])->name('wp.index');
    Route::post('wp/store', [WordPressController::class, 'store'])->name('wp.store');
    Route::post('wp/token', [WordPressController::class, 'generate_token'])->name('wp.generate.token');

    Route::get('wp/fetch_data', [WordPressController::class, 'fetch_data'])->name('wp.fetch.data');
    Route::get('wp/store_data', [WordPressController::class, 'store_to_database'])->name('wp.fetch.store');
});
