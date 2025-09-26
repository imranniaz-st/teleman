<?php

use App\Http\Controllers\SslCommerzPaymentController;
use Illuminate\Support\Facades\Route;

//SSLCOMMERZ START
Route::post('/pay', [SslCommerzPaymentController::class, 'index'])->name('ssl.pay');

Route::post('/success', [SslCommerzPaymentController::class, 'success'])->name('ssl.success');
Route::post('/fail', [SslCommerzPaymentController::class, 'fail'])->name('ssl.fail');
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel'])->name('ssl.cancel');

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn'])->name('ssl.ipn');
//SSLCOMMERZ END
