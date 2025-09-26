<?php

use App\Http\Controllers\SystemCurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard'], function () {
    Route::group(['middleware' => ['auth', 'can:admin', 'otp.verified'], 'prefix' => 'currency'], function () {
        Route::get('/', [SystemCurrencyController::class, 'index'])
            ->name('dashboard.currency.index')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::post('/store', [SystemCurrencyController::class, 'store'])
            ->name('dashboard.currency.store')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/update/{id}', [SystemCurrencyController::class, 'update'])
            ->name('dashboard.currency.update')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/default-currency/{code}', [SystemCurrencyController::class, 'defaultCurrency'])
            ->name('dashboard.currency.default')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });

        Route::get('/destroy/{id}', [SystemCurrencyController::class, 'destroy'])
            ->name('dashboard.currency.destroy')->missing(function (Request $request) {
                return Redirect::route('homepage');
            });
    });

    /**
     * CURRENCY
     */
    Route::get('/switch/currency', function (Request $request) {
        setcurr($request->currency);

        return back();
    })->name('switch.currency');
});
