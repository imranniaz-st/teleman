<?php

use App\Http\Controllers\CronJobController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified', 'can:admin'], 'prefix' => 'dashboard'], function () {

    Route::get('/cron-jobs', [CronJobController::class, 'index'])
         ->name('dashboard.cron.jobs');

    Route::get('/cron-job/stop/campaign-{campaign_id}/group-{group_id?}/provider-{provider_id}/{slug?}', [CronJobController::class, 'destroy'])
         ->name('dashboard.cron.jobs.destroy');

});
