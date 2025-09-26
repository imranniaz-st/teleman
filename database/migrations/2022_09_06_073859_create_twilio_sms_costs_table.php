<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwilioSmsCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twilio_sms_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('twilio_call_cost_id')->nullable(); // twilio call cost id
            $table->longText('teleman_sms_cost')->nullable(); // per sms
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
        Schema::dropIfExists('twilio_sms_costs');
    }
}
