<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwilioCallCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twilio_call_costs', function (Blueprint $table) {
            $table->id();
            $table->longText('country')->nullable();
            $table->longText('code')->nullable();
            $table->longText('twilio_cost')->nullable(); // per minute
            $table->longText('teleman_cost')->nullable(); // per minute
            $table->longText('teleman_cost_per_second')->nullable(); // per second
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('twilio_call_costs');
    }
}
