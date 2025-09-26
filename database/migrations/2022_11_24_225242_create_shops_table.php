<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchased_user_id')->nullable(); // user id
            $table->longText('phone')->nullable();
            $table->longText('country')->nullable();
            $table->longText('credit_cost')->nullable();
            $table->longText('start_at')->nullable();
            $table->longText('end_at')->nullable();
            $table->boolean('released')->nullable();
            $table->boolean('confirmed')->nullable();
            $table->boolean('renew')->nullable();
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
        Schema::dropIfExists('shops');
    }
}
