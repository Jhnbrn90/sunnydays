<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ShowPowerStations;
use App\Http\Controllers\ShowAverageYield;
use App\Http\Controllers\ShowGraphForDate;
use App\Http\Controllers\ShowCurrentWeather;
use App\Http\Controllers\ShowWeekOverview;

Route::get('/', IndexController::class);

Route::prefix('api')->group(function () {
    Route::get('powerstations', ShowPowerStations::class);
    Route::get('week-overview', ShowWeekOverview::class);
    Route::get('live-chart/{date}', ShowGraphForDate::class);
    Route::get('average-yield', ShowAverageYield::class);
    Route::get('weather', ShowCurrentWeather::class);
});