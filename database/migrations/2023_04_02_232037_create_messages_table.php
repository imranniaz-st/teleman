<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('sender')->nullable();
            $table->string('recipient')->nullable();
            $table->text('content')->nullable();
            $table->text('mms')->nullable();
            $table->string('user_number')->nullable();
            $table->string('my_number')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->boolean('seen')->nullable();
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
        Schema::dropIfExists('messages');
    }
}
