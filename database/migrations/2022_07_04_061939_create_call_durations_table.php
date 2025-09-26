<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallDurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_durations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('campaign_name')->nullable();
            $table->longText('phone')->nullable();
            $table->longText('accountSid')->nullable();
            $table->longText('sid')->nullable();
            $table->longText('duration')->nullable();
            $table->longText('deduction')->nullable();
            $table->longText('app_deduction')->nullable();
            $table->longText('total_deduction')->nullable();
            $table->longText('status')->nullable();
            $table->boolean('active')->nullable();
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
        Schema::dropIfExists('call_durations');
    }
}
