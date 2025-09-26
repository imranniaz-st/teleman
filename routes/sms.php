<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SmsController;

// backend
Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {
    Route::post('/sms-campaign/{campaign_id}/{slug?}', [SmsController::class, 'start_campaign'])->name('dashboard.campaign.start.sms');
});
