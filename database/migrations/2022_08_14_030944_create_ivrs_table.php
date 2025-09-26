<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIvrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ivrs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('ivr_name')->nullable();
            $table->longText('audio_file')->nullable();
            $table->integer('key1')->nullable();
            $table->longText('key1_value')->nullable();
            $table->integer('key2')->nullable();
            $table->longText('key2_value')->nullable();
            $table->integer('key3')->nullable();
            $table->longText('key3_value')->nullable();
            $table->integer('key4')->nullable();
            $table->longText('key4_value')->nullable();
            $table->integer('key5')->nullable();
            $table->longText('key5_value')->nullable();
            $table->integer('key6')->nullable();
            $table->longText('key6_value')->nullable();
            $table->integer('key7')->nullable();
            $table->longText('key7_value')->nullable();
            $table->integer('key8')->nullable();
            $table->longText('key8_value')->nullable();
            $table->integer('key9')->nullable();
            $table->longText('key9_value')->nullable();
            $table->integer('key0')->nullable();
            $table->longText('key0_value')->nullable();
            $table->longText('key_star')->nullable();
            $table->longText('key_star_value')->nullable();
            $table->longText('key_hash')->nullable();
            $table->longText('key_hash_value')->nullable();
            $table->longText('xml_file')->nullable();
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
        Schema::dropIfExists('ivrs');
    }
}
