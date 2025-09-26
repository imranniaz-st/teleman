<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'otp.verified'], function () {
    Route::group(['middleware' => ['auth'], 'prefix' => 'profile'], function () {
        Route::get('/', [CustomerController::class, 'index'])
            ->name('dashboard.profile.information')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/billing', [CustomerController::class, 'billing'])
            ->name('dashboard.profile.billing')->middleware('can:customer')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/billing-history', [CustomerController::class, 'billingHistory'])
            ->name('dashboard.profile.billing.history')->middleware('can:everyone')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/billing-history/destroy/{id}', [CustomerController::class, 'billingHistoryDelete'])
            ->name('dashboard.profile.billing.history.destroy')->middleware('can:admin')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/invoice/{invoice}', [CustomerController::class, 'invoice'])
            ->name('dashboard.profile.billing.invoice')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/my/subscription', [CustomerController::class, 'mySubscription'])
            ->name('dashboard.profile.billing.subscription')->middleware('can:customer')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/account/report/{domain?}', [CustomerController::class, 'accountReport'])
            ->name('dashboard.profile.account.report')->middleware('can:everyone')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::post('/update', [CustomerController::class, 'update'])
            ->name('dashboard.profile.update')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/change-password', [CustomerController::class, 'changePassword'])
            ->name('dashboard.profile.password')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::post('/update-password', [CustomerController::class, 'updatePassword'])
        ->name('dashboard.profile.updatePassword')->missing(function (Request $request) {
            return Redirect::route('homepage');
        });

        Route::get('/payment-histories/export', [CustomerController::class, 'export'])
        ->name('dashboard.payment.histories.export')->missing(function (Request $request) {
            return Redirect::route('homepage');
        });
    });
});
