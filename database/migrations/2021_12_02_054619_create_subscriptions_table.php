<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->longText('domain')->nullable();
            $table->double('credit')->nullable();
            $table->longText('emails')->nullable();
            $table->longText('sms')->nullable();
            $table->longText('start_at')->nullable();
            $table->longText('end_at')->nullable();
            $table->boolean('active')->default(0);
            $table->longText('payment_status')->nullable();
            $table->longText('payment_gateway')->nullable();
            $table->double('amount')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
