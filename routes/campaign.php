<?php

use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'otp.verified', 'kyc.verified']], function () {
    /**
     * campaigns
     */
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('dashboard.campaign.index'); // Dashboard > Campaign > Index
    Route::post('/campaign/store', [CampaignController::class, 'store'])->name('dashboard.campaign.store'); // Dashboard > Campaign > Store
    Route::get('/campaign/edit/{id}/{slug?}', [CampaignController::class, 'show'])->name('dashboard.campaign.edit');
    Route::post('/campaign/update/{id}/{slug?}', [CampaignController::class, 'update'])->name('dashboard.campaign.update');
    Route::get('/campaign/destroy/{id}/{slug?}', [CampaignController::class, 'destroy'])->name('dashboard.campaign.destroy');

    /**
     * Audio Campaign
     */
    Route::get('/audio-campaign/{campaign_id}/{slug?}', [CampaignController::class, 'start_campaign'])->name('dashboard.campaign.start.campaign');
    Route::get('/dev-make-call/{campaign_id}/{slug?}', [CampaignController::class, 'dev_make_call'])->name('dashboard.campaign.make.dev.call');

    /**
     * Voice Campaign
     */
    Route::get('/voice-campaign', [CampaignController::class, 'voice_campaign_index'])->name('dashboard.campaign.voice');
    Route::get('/voice-campaign/{campaign_id}/{slug}', [CampaignController::class, 'voice_campaign'])->name('dashboard.campaign.voice.campaign');
    Route::post('/voice-campaign/lead', [CampaignController::class, 'voice_campaign_lead'])->name('dashboard.campaign.voice.lead');

    /**
     * LEADS
     */
    Route::get('/leads', [CampaignController::class, 'leads'])->name('dashboard.campaign.leads');
    Route::get('/leads/details/{campaign_id}/{slug}', [CampaignController::class, 'leads_details'])->name('dashboard.campaign.leads_details');
    Route::get('/leads/export/{campaign_id}/{slug}', [CampaignController::class, 'leads_export'])->name('dashboard.campaign.leads_export');
    
    /**
     * SMS
     */
    Route::get('/send/sms', [CampaignController::class, 'send_sms'])->name('dashboard.campaign.send_sms');
});
