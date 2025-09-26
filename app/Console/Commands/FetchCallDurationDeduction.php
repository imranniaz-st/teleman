<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchCallDurationDeduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:duration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will fetch every completed call and deduct the duration from the provider';

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
            fetch_call_duration_deduction();
            CronJob('call:duration', 1, null);
        } catch (\Throwable $th) {
            CronJob('call:duration', 0, $th->getMessage());
        }
        
    }
}
