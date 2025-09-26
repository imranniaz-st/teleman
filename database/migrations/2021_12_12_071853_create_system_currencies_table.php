<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_currencies', function (Blueprint $table) {
            $table->id();
            $table->longText('name')->nullable();
            $table->longText('code')->nullable();
            $table->longText('symbol')->nullable();
            $table->longText('icon')->nullable();
            $table->longText('amount')->nullable();
            $table->boolean('default')->nullable();
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
        Schema::dropIfExists('system_currencies');
    }
}
