<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AutoUpdateController;
use App\Http\Controllers\BraintreeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\MenuBuilderController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SmtpController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\TwilioCallCostController;
use App\Http\Controllers\AddonsController;
use Illuminate\Support\Facades\Route;

/** FEATURES */
Route::group(['middleware' => ['auth', 'otp.verified'], 'prefix' => 'dashboard'], function () {

    // Dashboard
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('backend'); // Dashboard > Index
    });

    // Dashboard
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/developer/feedback', [DashboardController::class, 'developer_feedback'])
            ->name('developer.feedback'); // Dashboard > Index
    });

    // features
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'features'], function () {
        Route::get('/', [FeatureController::class, 'create'])
            ->name('dashboard.features.create'); // Dashboard > Features > Create

        Route::post('/store', [FeatureController::class, 'store'])
            ->name('dashboard.features.store'); // Dashboard > Features > Store

        Route::get('/edit/{id}/{slug?}', [FeatureController::class, 'edit'])
            ->name('dashboard.features.edit'); // Dashboard > Features > Edit

        Route::post('/update/{id}/{slug?}', [FeatureController::class, 'update'])
            ->name('dashboard.features.update'); // Dashboard > Features > Update

        Route::get('/destroy/{id}/{slug?}', [FeatureController::class, 'destroy'])
            ->name('dashboard.features.destroy'); // Dashboard > Features > Destroy

        Route::get('/change/status/{id}/{slug?}', [FeatureController::class, 'changeStatus'])
            ->name('dashboard.features.status');
    });

    // packages
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'packages'], function () {
        Route::get('/', [PackageController::class, 'index'])
            ->name('dashboard.packages.index'); // Dashboard > Packages > Index

        Route::get('/create', [PackageController::class, 'create'])
            ->name('dashboard.packages.create'); // Dashboard > Packages > Create

        Route::post('/store', [PackageController::class, 'store'])
            ->name('dashboard.packages.store'); // Dashboard > Packages > Store

        Route::get('/edit/{id}/{slug?}', [PackageController::class, 'edit'])
            ->name('dashboard.packages.edit'); // Dashboard > Packages > Edit

        Route::post('/update/{id}/{slug?}', [PackageController::class, 'update'])
            ->name('dashboard.packages.update'); // Dashboard > Packages > Update

        Route::get('/destroy/{id}/{slug?}', [PackageController::class, 'destroy'])
            ->name('dashboard.packages.destroy'); // Dashboard > Packages > Destroy

        Route::get('/change/status/{id}/{slug?}', [PackageController::class, 'changeStatus'])
            ->name('dashboard.packages.status'); // Dashboard > Packages > Status
    });

    // smtp
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'smtp'], function () {
        Route::get('/', [SmtpController::class, 'index'])
            ->name('dashboard.smtp.index'); // Dashboard > SMTP > Index

        Route::get('/store', [SmtpController::class, 'store'])
            ->name('dashboard.smtp.store'); // Dashboard > SMTP > Store

        Route::get('/test', [SmtpController::class, 'test'])
            ->name('dashboard.smtp.test'); // Dashboard > SMTP > test
    });

    // newsletters
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'newsletters'], function () {
        Route::get('/', [NewsletterController::class, 'index'])
            ->name('dashboard.newsletters.index'); // Dashboard > Newsletters > Index

        Route::get('/newsletters/export', [NewsletterController::class, 'export'])
            ->name('dashboard.newsletters.export'); // Dashboard > Newsletters > Export
    });

    // sslcommerz
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'sslcommerz'], function () {
        Route::get('/setup', [SslCommerzPaymentController::class, 'setup'])
            ->name('dashboard.sslcommerz.setup'); // Dashboard > SslCommerz > Setup

        Route::get('/update', [SslCommerzPaymentController::class, 'update'])
            ->name('dashboard.sslcommerz.update'); // Dashboard > SslCommerz > Update
    });

    // braintree
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'braintree'], function () {
        Route::get('/setup', [BraintreeController::class, 'setup'])
            ->name('dashboard.braintree.setup'); // Dashboard > Braintree > Setup

        Route::get('/update', [BraintreeController::class, 'update'])
            ->name('dashboard.braintree.update'); // Dashboard > Braintree > Update
    });

    // seo
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'seo'], function () {
        Route::get('/setup', [SeoController::class, 'setup'])
            ->name('dashboard.seo.setup'); // Dashboard > Seo > Setup

        Route::post('/update', [SeoController::class, 'update'])
            ->name('dashboard.seo.update'); // Dashboard > Seo > Update
    });

    // application
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'application'], function () {
        Route::get('/setup', [ApplicationController::class, 'setup'])
            ->name('dashboard.application.setup'); // Dashboard > Application > Setup

        Route::post('/update', [ApplicationController::class, 'update'])
            ->name('dashboard.application.update'); // Dashboard > Application > Update
    });

    // custom styles & scripts
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'application'], function () {
        Route::get('/custom-styles-scripts', [ApplicationController::class, 'custom_styles_scripts'])
            ->name('dashboard.application.custom.styles.scripts'); // Dashboard > Application > custom_styles_scripts > Setup

        Route::post('/custom_styles_scripts/update', [ApplicationController::class, 'custom_styles_scripts_update'])
            ->name('dashboard.application.custom.styles.scripts.update'); // Dashboard > Application > custom_styles_scripts > Update
    });

    // menu builder
    Route::group(['middleware' => ['auth', 'can:admin'], 'prefix' => 'menu-builder'], function () {
        Route::get('/', [MenuBuilderController::class, 'index'])
                ->name('dashboard.menu-builder.index'); // Dashboard > Menu Builder > Index

        Route::get('/create', [MenuBuilderController::class, 'create'])
                ->name('dashboard.menu-builder.create'); // Dashboard > Menu Builder > Create

        Route::post('/store', [MenuBuilderController::class, 'store'])
                ->name('dashboard.menu-builder.store'); // Dashboard > Menu Builder > Store

        Route::post('/page/store', [MenuBuilderController::class, 'page_store'])
                ->name('dashboard.menu-builder.page.store'); // Dashboard > Menu Builder > Page Store

        Route::get('/edit/{id}/{slug?}', [MenuBuilderController::class, 'edit'])
                ->name('dashboard.menu-builder.edit'); // Dashboard > Menu Builder > Edit

        Route::post('/update/{id}/{slug?}', [MenuBuilderController::class, 'update'])
                ->name('dashboard.menu-builder.update'); // Dashboard > Menu Builder > Update

        Route::get('/destroy/{id}/{slug?}', [MenuBuilderController::class, 'destroy'])
                ->name('dashboard.menu-builder.destroy'); // Dashboard > Menu Builder > Destroy

        Route::get('/change/status/{id}/{slug?}', [MenuBuilderController::class, 'changeStatus'])
                ->name('dashboard.menu-builder.status'); // Dashboard > Menu Builder > Status
    });

    // Pages
    Route::group(['middleware' => ['auth'], 'prefix' => 'page'], function () {
        Route::get('/', [PageController::class, 'index'])
            ->name('dashboard.page.index'); // Dashboard > Pages > Index

        Route::post('/store', [PageController::class, 'store'])
            ->name('dashboard.page.store'); // Dashboard > Pages > Store

        Route::get('/editor/{id}/{slug}', [PageController::class, 'editorjs_store_step2'])
            ->name('projects.editorjs.store.step2'); // Dashboard > Pages > EditorJS > Store Step 2

        Route::post('/store/{id}/{slug}', [PageController::class, 'editorjs_store'])
            ->name('projects.editorjs.store'); //ajax // Dashboard > Pages > EditorJS > Store

        Route::get('/destroy/{id}/{slug}', [PageController::class, 'destroy'])
        ->name('dashboard.page.destroy'); // Dashboard > Pages > Destroy

        Route::get('/edit/{id}/{slug}', [PageController::class, 'edit'])
            ->name('dashboard.page.edit'); // Dashboard > Pages > Edit
    });

    // Autoupgrade
    Route::group(['middleware' => ['auth'], 'prefix' => 'upgrade'], function () {
        Route::get('/', [AutoUpdateController::class, 'index'])
            ->name('dashboard.upgrade'); // Dashboard > AutoUpgrade > Index

        Route::get('/software/is-on-fire', [AutoUpdateController::class, 'lets_update_the_monster'])
        ->name('auto.update.fire'); // Dashboard > AutoUpgrade > Fire
    });

    // Payment gateways
    Route::group(['middleware' => ['auth'], 'prefix' => 'gateways'], function () {
        Route::get('/', [PaymentGatewayController::class, 'index'])
            ->name('dashboard.payment.gateways'); // Dashboard > payment > gateways
    });

    // Payment gateways
    Route::group(['middleware' => ['auth'], 'prefix' => 'optimize'], function () {
        Route::get('/', function(){
            Artisan::call('optimize:clear');
            smilify('success', 'Cache cleared successfully');
            return back();
        })
            ->name('optimize'); // Dashboard > payment > gateways
    });

    // Twilio Call Cost List
    Route::group(['middleware' => ['auth', 'kyc.verified'], 'prefix' => 'twilio'], function () {
        Route::get('/call-costs', [TwilioCallCostController::class, 'index'])
            ->name('dashboard.twilio.call.cost.index'); // Dashboard > twilio > call costs

        Route::post('/call-cost/store', [TwilioCallCostController::class, 'store'])
            ->name('dashboard.twilio.call.cost.store'); // Dashboard > twilio > call cost store

        Route::post('/call-cost/update/{id}', [TwilioCallCostController::class, 'update'])
            ->name('dashboard.twilio.call.cost.update'); // Dashboard > twilio > call cost update

        Route::get('/call-cost/destroy/{id}', [TwilioCallCostController::class, 'destroy'])
            ->name('dashboard.twilio.call.cost.destroy'); // Dashboard > twilio > call cost destroy
    });

    // addons
    Route::group(['middleware' => ['auth', 'kyc.verified'], 'prefix' => 'addons'], function () {
        Route::get('/', [AddonsController::class, 'index'])
            ->name('dashboard.addons.index'); // Dashboard > addons > index

        Route::post('/install', [AddonsController::class, 'install'])
            ->name('dashboard.addons.install')->middleware('can:admin'); // Dashboard > addons > install
    });

    // ENDS
});

