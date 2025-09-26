<?php

use App\Http\Controllers\RazorpayController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {

    Route::get('razorpay-payment', [RazorpayController::class, 'index'])->name('razorpay.payment.index');
    Route::post('razorpay-payment/setup', [RazorpayController::class, 'setup'])->name('razorpay.payment.setup');
    
});

Route::get('razorpay-payment/gateway', [RazorpayController::class, 'hostpage'])->name('razorpay.hostpage');
Route::post('razorpay-make-payment', [RazorpayController::class, 'MakePayment'])->name('razorpay.make-payment');