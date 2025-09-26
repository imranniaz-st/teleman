<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MakeCall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:calling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will manage schedule calls';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            echo 'Started at: '.Carbon::now().PHP_EOL;
            // your schedule code
            $campaign = CampaignSchedule::where('status', 'PENDING')
                                        ->where('start_at', '<=', Carbon::now())
                                        ->with('contacts')->first();

            if ($campaign == null) {
                echo 'No Campaign is scheduled'.PHP_EOL;

                return false;
            }

            echo 'Checking balance.......'.PHP_EOL;

            if (check_balance($campaign->user_id) == false) {
                echo 'Insufficient balance'.PHP_EOL;

                return;
            }

            $check_campaign_validity = Campaign::where('id', $campaign->id)->first();

            /**
             * check has group and provider
             */
            if ($campaign->group_id == null || $campaign->provider == null) {
                echo 'Campaign has no group or provider'.PHP_EOL;

                return false;
            }

            /**
             * Check Hourly quota
             */
            if (check_quota_hourly($campaign->user_id, $campaign->provider) == 'crossed') {
                echo 'Hourly quota crossed'.PHP_EOL;

                return false;
            }

            /**
             * Check Contacts
             */
            if ($campaign->contacts->count() == 0) {
                echo 'No Contacts found'.PHP_EOL;

                return false;
            }

            /**
             * Check Twilio Connection
             */
            if (check_twilio_connection(account_sid($campaign->provider)) == false) {
                echo 'Twilio Connection Failed. Please check your Twilio Account'.PHP_EOL;

                return false;
            }

            echo 'Contacts: '.$campaign->contacts->count().PHP_EOL;

            // [UPDATED METHOD::STARTS]
            // Define the batch size
            $batch_size = 100;

            // Fetch the contacts in batches
            $campaign->contacts()
                    ->chunk($batch_size, function ($contacts) use ($campaign) {
                        foreach ($contacts as $camp) {
                            if (check_quota_hourly($campaign->user_id, $campaign->provider) == 'crossed') {
                                echo 'Hourly quota crossed'.PHP_EOL;
                                return false;
                            }
                            
                            twilio_calling($campaign->provider,
                                phone_number($camp->contact_id),
                                true,
                                $campaign->audio, 
                                $campaign->user_id);
                                
                            quota_log_store($campaign->provider, $campaign->user_id, $camp->contact_id, phone_number($camp->contact_id));
                            
                            echo 'Calling: '.phone_number($camp->contact_id).PHP_EOL;
                        }
                        
                        // Update the campaign status
                        $campaign->status = 'COMPLETED';
                        $campaign->save();
                    });
            // [UPDATED METHOD::ENDS]

            echo 'Completed at: '.Carbon::now().PHP_EOL;

            CronJob('start:calling', 1, null);

        } catch (\Throwable $th) {
            CronJob('start:calling', 0, $th->getMessage());
        }
    }
}