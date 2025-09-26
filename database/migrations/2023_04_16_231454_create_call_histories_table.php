<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_histories', function (Blueprint $table) {
            $table->id();
            $table->longText('caller_uuid')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('identity_id')->nullable();
            $table->longText('my_number')->nullable();
            $table->longText('caller_number')->nullable();
            $table->longText('pick_up_time')->nullable();
            $table->longText('hang_up_time')->nullable();
            $table->longText('record_file')->nullable();
            $table->longText('status')->nullable(); // missed, picked, hanged
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
        Schema::dropIfExists('call_histories');
    }
}
