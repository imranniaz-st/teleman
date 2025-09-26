<?php

use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified', 'kyc.verified']], function () {
    Route::get('/agent/status/update', [QueueController::class, 'agent_status_update'])->name('dashboard.agent.status.update');
    Route::get('/get/queue/list', [QueueController::class, 'get_queue_list'])->name('dashboard.get.queue.list');
    Route::get('/download/all/recordings', [QueueController::class, 'download_all_recordings'])->name('dashboard.all.recordings');
});
