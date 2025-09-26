<?php

namespace Database\Seeders;

use App\Models\SystemCurrency;
use Illuminate\Database\Seeder;

class SystemCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemCurrency::create([
            'name' => 'US Dollar',
            'code' => '840',
            'symbol' => 'USD',
            'icon' => '$',
            'amount' => 1,
            'default' => 1,
        ]);
        //ENDS
    }
}
