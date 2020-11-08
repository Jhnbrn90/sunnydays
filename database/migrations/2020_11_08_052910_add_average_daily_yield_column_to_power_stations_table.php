<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAverageDailyYieldColumnToPowerStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('power_stations', function (Blueprint $table) {
            $table->unsignedDouble('daily_average')->default(0)->after('line_color');
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
            $table->dropColumn('daily_average');
        });
    }
}