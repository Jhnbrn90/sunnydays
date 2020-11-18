<?php

use App\Models\DailyProductionLog;
use App\Models\Powerlog;
use App\Models\PowerStation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPowerStationIdToDailyProductionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_production_logs', function (Blueprint $table) {
            $table->foreignId('power_station_id')->default(1)
                ->references('id')
                ->on('power_stations')
                ->onDelete('cascade');
        });

        PowerStation::all()->each(function ($powerStation) {
            DailyProductionLog::where('user', $powerStation->name)->update([
                'power_station_id' => $powerStation->id,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_production_logs', function (Blueprint $table) {
            $table->dropColumn('power_station_id');
        });
    }
}
