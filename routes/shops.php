<?php

use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard/shop'], function () {
    Route::get('/', [ShopController::class, 'index'])->name('shop.index')->middleware('can:adminCustomer');
    Route::post('/store', [ShopController::class, 'store'])->name('shop.store')->middleware('can:admin');
    Route::post('/update/{id}', [ShopController::class, 'update'])->name('shop.update')->middleware('can:admin');
    Route::get('/destroy/{id}/{slug}', [ShopController::class, 'destroy'])->name('shop.destroy')->middleware('can:admin');
    Route::get('/purchase/{id}/{slug}', [ShopController::class, 'purchase'])->name('shop.purchase')->middleware('can:customer');
    Route::get('/purchased/numbers', [ShopController::class, 'purchased_numbers'])->name('shop.purchased.numbers')->middleware('can:adminCustomer');
    Route::get('/revoke/{id}/{slug}', [ShopController::class, 'revoke_number'])->name('shop.revoke')->middleware('can:customer');
    Route::get('/renew/{id}/{slug}', [ShopController::class, 'renew_number'])->name('shop.renew')->middleware('can:customer');
    Route::get('/accept/{id}/{slug}', [ShopController::class, 'accept'])->name('shop.accept')->middleware('can:admin');
    Route::get('/new-orders', [ShopController::class, 'new_orders'])->name('shop.ordered.numbers')->middleware('can:admin');
    Route::get('/renew-orders', [ShopController::class, 'renew_orders'])->name('shop.renew.numbers')->middleware('can:adminCustomer');
    Route::get('/configurable', [ShopController::class, 'configurable'])->name('shop.configurable.numbers')->middleware('can:admin');
});
