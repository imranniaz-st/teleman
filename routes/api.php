<?php

use App\Http\Controllers\Api\ExpiryController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\ApiCampaignController;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['check.expiry']], function () {

     // check-expiry
    Route::post('/check-expiry', [ExpiryController::class, 'checkExpiry'])
          ->name('api.check.expiry'); // API > Check Expiry

    // user_subscription_data
    Route::get('/user-subscription-data', [SubscriptionController::class, 'user_subscription_data'])
          ->name('user.subscription.data'); // User > Subscription > Data

    // user_emails_limit
    Route::get('/user-emails-limit', [SubscriptionController::class, 'user_emails_limit'])
          ->name('user.emails.limit'); // User > Subscription > Emails Limit

    // user_email_limit_check
    Route::get('/user-email-limit-check', [SubscriptionController::class, 'user_email_limit_check'])
          ->name('user.email.limit.check'); // User > Subscription > Email Limit Check

    // user_email_limit_left
    Route::get('/user-email-limit-left', [SubscriptionController::class, 'user_email_limit_left'])
          ->name('user-email-limit-left'); // User > Subscription > Email Limit Left

    // user_email_limit_decrement
    Route::post('/user-email-limit-decrement', [SubscriptionController::class, 'user_email_limit_decrement'])
          ->name('user.email.limit.decrement'); // User > Subscription > Email Limit Decrement

    // payment history
    Route::get('/payment-histories', [SubscriptionController::class, 'user_payment_histories'])
          ->name('user.payment.histories'); // User > Subscription > Payment Histories

    /**
     * BRANCH
     */

    // user_sms_limit
    Route::get('/user-sms-limit', [SubscriptionController::class, 'user_sms_limit'])
          ->name('user.sms.limit'); // User > Subscription > SMS Limit

    // user_sms_limit_check
    Route::get('/user-sms-limit-check', [SubscriptionController::class, 'user_sms_limit_check'])
          ->name('user.sms.limit.check'); // User > Subscription > SMS Limit Check

    // user_sms_limit_left
    Route::get('/user-sms-limit-left', [SubscriptionController::class, 'user_sms_limit_left'])
          ->name('user-sms-limit-left'); // User > Subscription > SMS Limit Left

    // user_sms_limit_decrement
    Route::post('/user-sms-limit-decrement', [SubscriptionController::class, 'user_sms_limit_decrement'])
          ->name('user.sms.limit.decrement'); // User > Subscription > SMS Limit Decrement

    /**
     * BRANCH::ENDS
     */

    // userSubscriptionDateEndIn
    Route::get('/user-subscription-date-endin', [SubscriptionController::class, 'user_subscription_date_endin'])
          ->name('user.subscription.date.endin'); // User > Subscription > Date End In

    // user block
    Route::get('/user-restriction', [SubscriptionController::class, 'restriction'])
          ->name('user.restriction'); // User > Subscription > Restriction

    


});

Route::post('/react-base-url', function () {
    return env('REACT_BASE_URL'); // React Base URL
});

/**
 * WordPress
 */

Route::group(['middleware' => ['wordpress']], function () {
      
     /**
     * Campaign
     */
    Route::get('/user-campaign-list', [ApiCampaignController::class, 'index'])
          ->name('api.campaign.list'); // User > Subscription > Restriction
    
    /**
     * Start a campaign
     */
    Route::get('/audio-campaign/{campaign_id}', [ApiCampaignController::class, 'start_campaign'])
          ->name('api.campaign.start.campaign'); // User > Subscription > Restriction
});

// ENDS
