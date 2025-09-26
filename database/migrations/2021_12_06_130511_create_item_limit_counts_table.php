<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemLimitCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_limit_counts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('domain')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->longText('credit')->nullable();
            $table->longText('all_time_credit')->nullable();
            $table->longText('emails')->nullable();
            $table->longText('sms')->nullable();
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
        Schema::dropIfExists('item_limit_counts');
    }
}
