<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUserColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_production_logs', function (Blueprint $table) {
            $table->dropColumn('user');
        });

        Schema::table('powerlogs', function (Blueprint $table) {
            $table->dropColumn('user');
        });
    }
}