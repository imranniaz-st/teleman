<?php

use App\Http\Controllers\DocumentKycController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {

    Route::group(['middleware' => ['can:adminCustomer']], function () {
        Route::get('/kyc/verification', [DocumentKycController::class, 'index'])
                ->name('dashboard.kyc.index');
    });

    Route::group(['middleware' => ['can:customer']], function () {
        Route::post('/kyc/document/upload', [DocumentKycController::class, 'store'])
                ->name('dashboard.kyc.store');
    });

    Route::group(['middleware' => ['can:admin']], function () {
        Route::get('/kyc/review/document/{user_id}/{slug?}', [DocumentKycController::class, 'review_document'])
                ->name('dashboard.kyc.review.document');

        Route::get('/kyc/review/document/{user_id}/{slug?}/approved', [DocumentKycController::class, 'approved'])
                ->name('dashboard.kyc.review.approved');

        Route::get('/kyc/review/document/{user_id}/{slug?}/rejected', [DocumentKycController::class, 'rejected'])
                ->name('dashboard.kyc.review.rejected');

        Route::get('/kyc/review/document/{user_id}/{slug?}/destroy', [DocumentKycController::class, 'destroy'])
                ->name('dashboard.kyc.review.destroy');
    });

});

