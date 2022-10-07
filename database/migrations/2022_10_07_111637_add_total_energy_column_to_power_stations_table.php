<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalEnergyColumnToPowerStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('power_stations', function (Blueprint $table) {
            $table->unsignedDouble('total_energy')->after('daily_average')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('power_stations', function (Blueprint $table) {
            $table->dropColumn('total_energy');
        });
    }
}
