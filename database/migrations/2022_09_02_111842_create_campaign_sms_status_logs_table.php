<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignSmsStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_sms_status_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id')->nullable(); // campaign id
            $table->unsignedBigInteger('contact_id')->nullable(); // contact id
            $table->unsignedBigInteger('user_id')->nullable(); // user id
            $table->longText('agent_name')->nullable(); // agent name
            $table->longText('message')->nullable(); // message
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
        Schema::dropIfExists('campaign_sms_status_logs');
    }
}
