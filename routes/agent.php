<?php

use App\Http\Controllers\AgentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified', 'kyc.verified'], 'prefix' => 'dashboard'], function () {

    Route::get('/agents', [AgentController::class, 'index'])
         ->name('dashboard.agents.index');

    Route::post('/agent/store', [AgentController::class, 'store'])
         ->name('dashboard.agent.store');

    Route::post('/agent/{agent_id}/{slug}/update', [AgentController::class, 'update'])
         ->name('dashboard.agent.update');

    Route::get('/agent/{agent_id}/{slug}/destroy', [AgentController::class, 'destroy'])
         ->name('dashboard.agent.destroy');

    Route::get('/agent/{agent_id}/{slug}/change/restriction', [AgentController::class, 'restricted'])
         ->name('dashboard.agent.change_restriction');


});
