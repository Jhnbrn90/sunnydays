<?php

Route::get('/', 'IndexController');

Route::prefix('api')->group(function () {
    Route::get('goodwe/all', 'PowerstationsOverview');
    Route::get('hourly', 'CurrentlyGeneratedPower');
    Route::get('daily', 'DailyGeneratedPower');
    Route::get('production', 'WeeklyGeneratedPower');
    Route::get('dailygraph/{date}', 'ShowGraphForDate');
    Route::get('average', 'ShowAverage');
    Route::get('weather', 'WeatherData');
});
