<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveCallDurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_call_durations', function (Blueprint $table) {
            $table->id();
            $table->longText('dialer_session_uuid')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('phone')->nullable();
            $table->longText('start_at')->nullable();
            $table->longText('end_at')->nullable();
            $table->longText('duration')->nullable();
            $table->longText('app_deduction')->nullable();
            $table->longText('total_deduction')->nullable();
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
        Schema::dropIfExists('live_call_durations');
    }
}
