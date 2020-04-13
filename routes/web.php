<?php

use App\Http\Controllers\CurrentlyGeneratedPower;
use App\Http\Controllers\DailyGeneratedPower;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PowerstationsOverview;
use App\Http\Controllers\ShowAverage;
use App\Http\Controllers\ShowGraphForDate;
use App\Http\Controllers\WeatherData;
use App\Http\Controllers\WeeklyGeneratedPower;

Route::get('/', IndexController::class);

Route::prefix('api')->group(function () {
    Route::get('goodwe/all', PowerstationsOverview::class);
    Route::get('hourly', CurrentlyGeneratedPower::class);
    Route::get('daily', DailyGeneratedPower::class);
    Route::get('production', WeeklyGeneratedPower::class);
    Route::get('dailygraph/{date}', ShowGraphForDate::class);
    Route::get('average', ShowAverage::class);
    Route::get('weather', WeatherData::class);
});