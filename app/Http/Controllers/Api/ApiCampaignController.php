<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\CampaignSchedule;
use App\Models\CampaignVoice;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PackageSupportedCountry;
use App\Models\TwilioCallCost;

class ApiCampaignController extends Controller
{

    /**
     * INDEX
     */
    public function index()
    {
        $user_token =  request()->user_token;
        return Campaign::where('user_id', token_user($user_token)->user_id)->get();
    }

    /**
     * start_campaign
     */
    public function start_campaign($campaign_id)
    {
        $campaign = Campaign::where('id', $campaign_id)->first();

        if (check_balance($campaign->user_id) == false) {
            return response()->json(['error' => 'Insufficient balance'], 401);
        }

        /**
         * check has group and provider
         */
        if ($campaign->group_id == null || $campaign->provider == null) {
            return response()->json(['error' => 'Campaign has no group or provider'], 401);
        }

        /**
         * Check Hourly quota
         */
        if (check_quota_hourly($campaign->user_id, $campaign->provider) == 'crossed') {
            return response()->json(['warning' => 'Hourly quota crossed'], 400);
        }

        /**
         * Check Twilio Connection
         */
        if (check_twilio_connection(account_sid($campaign->provider)) == false) {
            return response()->json(['error' => 'Twilio Connection Failed. Please check your Twilio Account'], 401);
        }

        $start = new CampaignSchedule;
        $start->user_id = $campaign->user_id;
        $start->campaign_id = $campaign_id;
        $start->group_id = $campaign->group_id;
        $start->provider = $campaign->provider;
        $start->say = $campaign->say;
        $start->audio = $campaign->audio;
        $start->xml = $campaign->xml;
        $start->start_at = Carbon::now();
        $start->status = 'PENDING';
        $start->save();

        return response()->json(['success' => 'Campaign Started successfully'], 200);

    }
    //ENDS
}
