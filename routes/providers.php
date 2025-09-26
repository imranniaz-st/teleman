<?php

use App\Http\Controllers\ProviderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified', 'kyc.verified']], function () {
    Route::get('/providers', [ProviderController::class, 'index'])->name('dashboard.provider.index');
    Route::post('/provider/store', [ProviderController::class, 'store'])->name('dashboard.provider.store');

    Route::group(['middleware' => 'can:admin'], function(){
        Route::get('/provider/edit/{id}/{slug?}', [ProviderController::class, 'show'])->name('dashboard.provider.edit');
        Route::post('/provider/update/{id}/{slug?}', [ProviderController::class, 'update'])->name('dashboard.provider.update');
        Route::get('/provider/destroy/{id}/{slug?}', [ProviderController::class, 'provider_destroy'])->name('dashboard.provider.destroy');
    });
    
    Route::get('/provider/accounts', [ProviderController::class, 'accounts'])->name('dashboard.provider.accounts');
    Route::get('/provider/accounts/ajax', [ProviderController::class, 'accounts_ajax'])->name('dashboard.provider.accounts.ajax');

    Route::get('/provider/account/call-logs/{sid}', [ProviderController::class, 'call_logs'])->name('dashboard.provider.call.logs');
    Route::get('/provider/account/call-logs/{sid}/ajax', [ProviderController::class, 'call_logs_ajax'])->name('dashboard.provider.call.logs.ajax');

    Route::get('/provider/call_log/{sid}/{account_sid}', [ProviderController::class, 'single_call_log'])->name('dashboard.provider.single.call.log');
    Route::any('/test/call/{id}/{provider}', [ProviderController::class, 'initiateTestCall'])->name('test.initiate_call');
    Route::get('/download/recording/{call_sid}/{account_sid}', [ProviderController::class, 'download_recording'])->name('dashboard.provider.download_recording');
    Route::get('/call/destroy/{call_sid}', [ProviderController::class, 'destroy'])->name('dashboard.provider.call.destroy');
    Route::get('/export/{account_sid}', [ProviderController::class, 'export_calls_csv'])->name('dashboard.provider.call.export');

    Route::get('/provider/export', [ProviderController::class, 'export'])->name('dashboard.provider.export');

    Route::get('/provider/phone/make-default/{provider_id}/{slug}', [ProviderController::class, 'make_default'])->name('dashboard.provider.set.default');
});
