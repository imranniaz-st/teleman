<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DialerController;
use App\Http\Controllers\CallHistoryController;

Route::group(['middleware' => ['auth', 'otp.verified', 'kyc.verified'], 'prefix' => 'dashboard'], function () {
    Route::get('/dialer/{department?}/{department_slug?}', [DialerController::class, 'index'])
            ->middleware('incoming.number.checker')
            ->name('dialer.index');

    /**
     * Call Duration
     */
    Route::any('/dialer/call-duration/store', [DialerController::class, 'store'])->name('dialer.call-duration.store');
    Route::any('/dialer/check/country-code/exists-in-package', [DialerController::class, 'country_code_exists_in_package'])->name('dialer.country.code.exists.in.package');

    Route::get('/inbound-call/{my_number?}/{department?}/{department_slug?}', [DialerController::class, 'dialerpad'])
         ->middleware('incoming.number.checker')
         ->name('dialerpad');

    Route::post('/create-call-history', [DialerController::class, 'createCallHistory'])->name('create.call.hostory');
    Route::get('/web-dialer/token', [DialerController::class, 'dialer_token'])->name('dialer.token');
    
    // OPEN AI
    Route::get('/analyze-the-call-record/{file_name}', [DialerController::class, 'analyze_the_call_record'])->name('analyze.the.call.record');
    
    // Call History
    Route::get('/call/history', [CallHistoryController::class, 'index'])->name('dashboard.call.history');

});

Route::any('/handle_call', [DialerController::class, 'handle_call'])->name('dialer.handle_call');

Route::any('/recording', [DialerController::class, 'recording'])->name('dialer.recording');
