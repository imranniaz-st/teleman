<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueueListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_lists', function (Blueprint $table) {
            $table->id();
            $table->longText('caller_number')->nullable();
            $table->longText('my_number')->nullable();
            $table->longText('serial')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // this is the call receiver user
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
        Schema::dropIfExists('queue_lists');
    }
}
