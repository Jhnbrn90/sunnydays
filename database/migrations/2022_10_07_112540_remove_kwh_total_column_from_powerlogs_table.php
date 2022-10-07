<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveKwhTotalColumnFromPowerlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('powerlogs', function (Blueprint $table) {
            $table->dropColumn('kwh_total');
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
            $table->unsignedInteger('kwh_total')->after('kwh_today')->nullable();
        });
    }
}
