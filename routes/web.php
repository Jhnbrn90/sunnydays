<?php

use Carbon\Carbon;

Route::get('/', function () {
    $data = getDailyLogs();
    return view('welcome', compact('data'));
});

Route::get('/api/hourly', 'ApiController@hourly');

/********************
 * Helper functions
 ********************/

function getDailyLogs()
{
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
