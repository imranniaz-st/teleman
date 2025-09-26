<?php

use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

//STRIPE START
Route::get('/stripe/setup', [StripeController::class, 'index'])->name('dashboard.stripe.setup');
Route::post('/stripe/update', [StripeController::class, 'update'])->name('dashboard.stripe.update');

Route::get('stripe', [StripeController::class, 'stripe'])->name('stripe.hostpage');
Route::post('stripe', [StripeController::class, 'stripePost'])->name('stripe.post');
//STRIPE END
