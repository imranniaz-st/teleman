<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('provider_name')->nullable();
            $table->longText('account_sid')->nullable();
            $table->longText('auth_token')->nullable();
            $table->string('phone')->nullable();
            $table->longText('say')->nullable();
            $table->longText('audio')->nullable();
            $table->longText('xml')->nullable();
            $table->longText('provider')->nullable();
            $table->longText('hourly_quota')->nullable();
            $table->boolean('ivr')->nullable();
            $table->longText('capability_token')->nullable();
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('providers');
    }
}
