<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->longText('name')->nullable();
            $table->longText('slug')->nullable();
            $table->longText('credit')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('trial')->default(0);
            $table->longText('feature_id')->nullable();
            $table->double('price')->default(0);
            $table->longText('emails')->nullable();
            $table->longText('sms')->nullable();
            $table->longText('range')->nullable();
            $table->longText('range_type')->nullable();
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
        Schema::dropIfExists('packages');
    }
}
