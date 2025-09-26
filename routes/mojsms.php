<?php

use App\Http\Controllers\MojsmsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {
    Route::get('/mojsms', [MojsmsController::class, 'index'])->name('dashboard.addons.mojsms');
    Route::post('/mojsms/store', [MojsmsController::class, 'store'])->name('dashboard.mojsms.store');
});
