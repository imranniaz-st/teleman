<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Campaign;
use App\Models\SmsContent;
use App\Models\SmsSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    public function start_campaign($campaign_id, $slug = null)
    {
        $campaign = Campaign::where('id', $campaign_id)->first();

        if (check_balance($campaign->user_id) == false) {
            smilify('error', 'Insufficient balance');
            return back();
        }

        /**
         * check has group and provider
         */
        if ($campaign->group_id == null || $campaign->provider == null) {
            smilify('error', 'Campaign has no group or provider');
            return back();
        }

        /**
         * Check Hourly quota
         */
        if (check_quota_hourly($campaign->user_id, $campaign->provider) == 'crossed') {
            smilify('warning', 'Hourly quota crossed');
            return back();
        }

        /**
         * Check Twilio Connection
         */
        if (check_twilio_connection(account_sid($campaign->provider)) == false) {
            smilify('error', 'Twilio Connection Failed. Please check your Twilio Account');
            return back();
        }

        $sms = new SmsSchedule;
        $sms->user_id = Auth::id();
        $sms->campaign_id = $campaign_id;
        $sms->group_id = $campaign->group_id;
        $sms->provider = $campaign->provider;
        $sms->start_at = Carbon::now();
        $sms->status = 'PENDING';
        $sms->third_party_provider = request('third_party_provider') ?? null;
        $sms->save();

        if ($sms->save()) {
            $content = SmsContent::updateOrCreate(
                ['campaign_id' =>  $campaign_id],
                ['content' => request('content')]
            );
        }

        smilify('success', 'Campaign Started successfully');

        return back();
    }
    //ENDS
}
