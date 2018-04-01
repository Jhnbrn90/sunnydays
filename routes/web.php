<?php

use Carbon\Carbon;
use App\Events\PeriodicLogUpdated;

Route::get('/', function () {
    $data = getDailyLogs();
    $goodweId = \Config::get('services.goodwe.id');
    return view('welcome', compact('data', 'goodweId'));
});

Route::get('/api/goodwe', 'ApiController@goodWe');

Route::get('/api/hourly', 'ApiController@hourly');

Route::get('/api/data', function () {
    return getDailyLogs();
});

Route::get('/newdata', function () {
    PeriodicLogUpdated::dispatch();
});

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
