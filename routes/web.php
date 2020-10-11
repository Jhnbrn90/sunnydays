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
    Route::get('dailygraph/{date}', ShowGraphForDate::class);
    Route::get('average-yield', ShowAverageYield::class);
    Route::get('weather', ShowCurrentWeather::class);
});

Route::prefix('snapshot')->group(function () {
    Route::get('statistics', function () {
        return view('statistics');
    })->name('statistics');

    Route::get('graph', function () {
        return view ('graph');
    })->name('graph');

    Route::get('weekly', function () {
        return view('weekly');
    })->name('weekly');
});