<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsExportHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads_export_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->longText('campaign_name')->nullable();
            $table->longText('total_contacts')->nullable();
            $table->longText('picked')->nullable();
            $table->longText('busy')->nullable();
            $table->longText('swiched_off')->nullable();
            $table->longText('lead')->nullable();
            $table->longText('total')->nullable();
            $table->longText('picked_percentage')->nullable();
            $table->longText('busy_percentage')->nullable();
            $table->longText('swiched_off_percentage')->nullable();
            $table->longText('lead_percentage')->nullable();
            $table->longText('lead_percentage_expectation')->nullable();
            $table->longText('export_date')->nullable();
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
        Schema::dropIfExists('leads_export_histories');
    }
}
