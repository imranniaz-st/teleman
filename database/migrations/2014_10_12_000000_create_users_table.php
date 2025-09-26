<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->longText('otp')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->longText('domain')->nullable();
            $table->longText('rest_name')->nullable();
            $table->longText('rest_address')->nullable();
            $table->longText('role')->nullable();
            $table->longText('country')->nullable();
            $table->longText('country_code')->nullable();
            $table->longText('region_name')->nullable();
            $table->longText('city')->nullable();
            $table->longText('zip')->nullable();
            $table->longText('lat')->nullable();
            $table->longText('lon')->nullable();
            $table->longText('timezone')->nullable();
            $table->boolean('restriction')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
