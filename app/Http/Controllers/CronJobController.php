<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampaignSchedule;

class CronJobController extends Controller
{

    /**
     * This is view file 
     */
    public function index()
    {
        return view('backend.cron_jobs.index');
    }

    /**
     * Stop the cron job
     */
    public function destroy($campaign_id, $group_id, $provider_id)
    {
        try {
            $cron = CampaignSchedule::where('status', 'PENDING')
                                    ->where('campaign_id', $campaign_id)
                                    ->where('group_id', $group_id)
                                    ->where('provider', $provider_id)
                                    ->first();
            if ($cron) {
                $cron->delete();
            }else {
                smilify('info', 'This campaign has no Cron Job');
                return back();
            }

            smilify('success', 'Cron job stopped for this campaign');
            return back();
        } catch (\Throwable $th) {
            smilify('error', 'Something went wrong.');
            return back();
        }
        
    }
    //ENDS
}
