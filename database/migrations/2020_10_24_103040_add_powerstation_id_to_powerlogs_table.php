<?php

use App\Models\Powerlog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPowerstationIdToPowerlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->environment() !== 'testing') {
            (new \Database\Seeders\PowerStationTableSeeder)->run();
        }

        Schema::table('powerlogs', function (Blueprint $table) {
            $table->foreignId('power_station_id')->default(1)
                ->references('id')
                ->on('power_stations')
                ->onDelete('cascade');
        });

        (new \Database\Seeders\PowerStationIdSeeder)->run();

        Schema::table('powerlogs', function (Blueprint $table) {
            $table->string('user')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('powerlogs', function (Blueprint $table) {
            $table->dropColumn('power_station_id');
        });
    }
}
