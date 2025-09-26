<?php

namespace App\Console\Commands;

use App\Models\Shop;
use Illuminate\Console\Command;

class AutomaticRevokeNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'number:revoke';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will revoke the expired number.';

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

        /* A try catch block. */
        try {

            /* Just a message to the user. */
            echo "\e[1;32mFinding expired numbers." .PHP_EOL;
            /* Getting all the numbers that are released, confirmed and purchased. */
            $numbers = Shop::Released()
                ->Confirmed()
                ->whereNotNull('purchased_user_id')
                ->get();

            /* Just a message to the user. */
            echo "\e[1;32mRevoking numbers" .PHP_EOL;
            /* Checking the subscription end days of the number. */
            foreach ($numbers as $number) {
                check_phone_number_subscription_end_days($number->id);
            }

            /* Just a message to the user. */
            echo "\e[1;32mRevoked completed.";

            /* A function that will update the cron job status. */
            CronJob('number:revoke', 1, null);
        } catch (\Throwable $th) {
            /* A function that will update the cron job status. */
            CronJob('number:revoke', 0, $th->getMessage());
        }
        
    }
}
