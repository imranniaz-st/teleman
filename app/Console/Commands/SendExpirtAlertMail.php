<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\CronJob;

class SendExpirtAlertMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:expiry-alert-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send expirt alert mail';

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
            CronJob('send:expiry-alert-mail', 1, null);
            return expirationNotify(env('EXPRITY_ALERT_MAIL_DAY'));
        } catch (\Throwable $th) {
            CronJob('send:expiry-alert-mail', 0, $th->getMessage());
        }
        
    }
}
