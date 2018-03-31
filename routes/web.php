<?php

use Carbon\Carbon;

Route::get('/', function () {
    $data = getDailyLogs();
    $goodweId = \Config::get('services.goodwe.id');
    return view('welcome', compact('data', 'goodweId'));
});

Route::get('/api/hourly', 'ApiController@hourly');

/********************
 * Helper functions
 ********************/

function getDailyLogs()
{
    $log = [];
    $powerlogs = \App\Powerlog::where('created_at', '>=', Carbon::today())->get();

    foreach ($powerlogs as $powerlog) {
        $log[(string)$powerlog->created_at->format('H:i')] = [
            'power'             => $powerlog->current_power,
            'weather_condition' => $powerlog->weather_condition,
            'temperature'       => $powerlog->temperature
        ];
    }

    return collect($log);
}
