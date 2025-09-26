<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RenewController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['install']], function () {

// FrontendController
    Route::get('/', [FrontendController::class, 'index'])
     ->name('frontend');

    // FrontendController
    Route::get('/pricing', [FrontendController::class, 'pricing'])
     ->name('frontend.pricing');

    // RegisterController
    Route::get('/new/subscription/{slug}', [RegisterController::class, 'create'])
     ->name('register.new.subscriber');

    // RegisterController
    Route::post('/new/subscription/store', [RegisterController::class, 'store'])
     ->name('register.new.subscriber.store');

    // RegisterController
    Route::get('/check.domain', [RegisterController::class, 'check_domain'])
     ->name('check.domain');

    // RenewController
    Route::get('/renew/subscription', [RenewController::class, 'create'])
     ->name('renew.new.subscriber');

    // RenewController
    Route::post('/renew/subscription/store', [RenewController::class, 'store'])
     ->name('renew.subscriber.store');

    // RenewController
    Route::get('/success', [RegisterController::class, 'success'])
     ->name('renew.subscriber.success');

    // RenewController
    Route::get('/failed', [RegisterController::class, 'failed'])
     ->name('renew.subscriber.failed');

    // RenewController
    Route::get('/renew/success', [RenewController::class, 'success'])
     ->name('renew.subscriber.success.renew');

    // RenewController
    Route::get('/payment/gateways', [FrontendController::class, 'gateways'])
     ->name('frontend.payment.gateways');

    // NewsletterController
    Route::post('/newsletter/store', [NewsletterController::class, 'store'])
     ->name('frontend.newsletter.store');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('email/verification/user', [DashboardController::class, 'emailVerificationWithCode'])->name('email.verification.with.code'); // email verification
    Route::get('email/verification/resend', [DashboardController::class, 'emailVerificationCodeResend'])->name('email.verification.resend'); // email verification code send to email
    Route::get('email/verification/code', [DashboardController::class, 'emailVerificationCode'])->name('email.verification.code'); // email verification code send to email
    Route::get('email/verification/code/match', [DashboardController::class, 'emailVerificationMatch'])->name('email.verification.code.match'); // email verification code match
    });

    Route::get('generate/new/password', [DashboardController::class, 'generateNewPassword'])->name('email.generate.new.password'); // email verification code match

    Route::get('sitemap', [FrontendController::class, 'sitemap'])->name('sitemap'); // sitemap

    Route::get('content/json/editor', [EditorController::class, 'frontendJsonEditor'])->name('frontend.json.editor'); // ajax
    Route::any('content/json/upload', [EditorController::class, 'frontendJsonupload'])->name('frontend.json.upload'); // ajax

// Blogs
    Route::get('/blogs', [PageController::class, 'blogs'])
     ->name('frontend.page.blogs');

    Route::get('/p/{slug?}', [PageController::class, 'frontend_index'])
     ->name('frontend.page.index');

});
