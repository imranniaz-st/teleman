<?php

namespace App\Console\Commands;

use App\Models\CallDuration;
use Illuminate\Console\Command;

class CalculateDeduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:deduction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate deduction for each call';

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
            // calculate total_deduction
            echo 'Calculating total deduction...'.PHP_EOL;
            $all_completed_calls = CallDuration::where('status', 'completed')
                                            ->whereNull('total_deduction')
                                            ->get();

            echo 'Total completed calls: '.count($all_completed_calls).PHP_EOL;

            foreach ($all_completed_calls as $all_completed_call) {
                if ($all_completed_call->duration != null) {
                    $all_completed_call->total_deduction = $all_completed_call->duration * $all_completed_call->app_deduction;
                    $all_completed_call->save();

                    deduct_credit($all_completed_call->user_id);

                    echo 'Call ID: '.$all_completed_call->sid.' - Deduction: '.$all_completed_call->total_deduction.PHP_EOL;
                }
            }
            echo 'Total deduction calculated....';
            CronJob('calculate:deduction', 1, null);
        } catch (\Throwable $th) {
            CronJob('calculate:deduction', 0, $th->getMessage());
        }
        
    }
}
