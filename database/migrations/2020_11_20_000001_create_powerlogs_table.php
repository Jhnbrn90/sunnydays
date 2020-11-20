<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePowerlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('powerlogs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('current_power');
            $table->foreignId('power_station_id')->default(1)
                ->references('id')
                ->on('power_stations')
                ->onDelete('cascade');
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
        Schema::dropIfExists('powerlogs');
    }
}
