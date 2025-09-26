<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\LimitManagerController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {

    // features
    Route::group(['middleware' => ['auth', 'can:adminCustomer'], 'prefix' => 'clients'], function () {
        Route::get('/', [ClientController::class, 'index'])
             ->name('dashboard.clients.index');

        Route::post('/new-user/store', [ClientController::class, 'store'])
             ->name('dashboard.clients.store');

        Route::get('/restriction/{user_id}/{domain}', [ClientController::class, 'restriction'])
             ->name('dashboard.clients.restriction');

        Route::get('/subscribe/{user_id}/{domain}', [ClientController::class, 'subscribe_unsubscribe'])
             ->name('dashboard.clients.subscribe');

        Route::get('/trash/{user_id}/{domain}', [ClientController::class, 'destroy'])
             ->name('dashboard.clients.destroy');

        Route::get('/expel/{user_id}/{domain}', [ClientController::class, 'expel'])
             ->name('dashboard.clients.expel');

        Route::get('/send/expiry/alert/{domain}', [ClientController::class, 'sendExpiryAlert'])
             ->name('dashboard.clients.send.expiry.alert');

        Route::get('/limit-manager/{user_id}/{slug}', [LimitManagerController::class, 'index'])
             ->name('dashboard.clients.limit.manager');

        Route::post('/limit-manager/{user_id}/{slug}/update', [LimitManagerController::class, 'update'])
             ->name('dashboard.clients.limit.manager.update');

        Route::get('/login-as/{user_id}/{slug}/update', [ClientController::class, 'login_as'])
             ->name('dashboard.clients.login');
    });
});
