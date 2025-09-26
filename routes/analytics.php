<?php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'analytics'], function () {
    Route::get('/', [AnalyticsController::class, 'index'])->name('dashboard.analytics.index'); // Dashboard > Analytics > Index
    Route::get('/ajax', [AnalyticsController::class, 'index_ajax'])->name('dashboard.analytics.index.ajax'); // Dashboard > Analytics > Index AJAX

    Route::get('/{account_sid}/{phone}', [AnalyticsController::class, 'analytic'])->name('dashboard.analytics.show'); // Dashboard > Analytics > Show
    Route::get('/{account_sid}/{phone}/ajax', [AnalyticsController::class, 'analytic_ajax'])->name('dashboard.analytics.show.ajax'); // Dashboard > Analytics > Show AJAX
});
