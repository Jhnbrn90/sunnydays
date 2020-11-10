<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ShowPowerStations;
use App\Http\Controllers\ShowGraphForDate;
use App\Http\Controllers\ShowCurrentWeather;
use App\Http\Controllers\ShowWeekOverview;

Route::get('/', IndexController::class);

Route::prefix('api')->group(function () {
    Route::get('live-chart/{date}', ShowGraphForDate::class);
    Route::get('powerstations', ShowPowerStations::class)->name('power_stations');
    Route::get('week-overview', ShowWeekOverview::class)->name('week_overview');
    Route::get('weather', ShowCurrentWeather::class);
});