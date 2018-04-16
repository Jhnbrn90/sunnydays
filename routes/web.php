<?php

use Carbon\Carbon;
use App\DailyProductionLog;

Route::get('/', function () {
    $goodweId = \Config::get('services.goodwe.JL');
    $goodweIdMB = \Config::get('services.goodwe.MB');

    return view('welcome', compact('goodweId', 'goodweIdMB'));
});

Route::get('/api/goodwe/{id}', 'ApiController@goodWe');

Route::get('/api/hourly', 'ApiController@hourly');
Route::get('/api/daily', 'ApiController@daily');

Route::get('/api/production', function () {
    return getWeeklyLogs();
});

Route::get('/api/dailygraph/{date}', function ($date) {
    return getdailyLogsFor($date);
});

/********************
 * Helper functions
 ********************/

function getDailyLogsFor($date)
{
    $date = Carbon::parse($date);
    $start = $date->startOfDay();

    $users = ['JL', 'MB'];

    foreach ($users as $user) {
        $log = [];
        $powerlogs = \App\Powerlog::where('user', $user)->whereDate('created_at', $start)->get();

        foreach ($powerlogs as $powerlog) {
            $log[(string)$powerlog->created_at->format('H:i')] = [
            'power'             => $powerlog->current_power,
            'weather_condition' => $powerlog->weather_condition,
            'temperature'       => $powerlog->temperature
        ];
        }

        $collection[$user] = $log;
    }

    return collect($collection);
}

function getWeeklyLogs()
{
    $users = ['JL', 'MB'];

    foreach ($users as $user) {
        $result = [];
        $weeklyEntries[$user] = DailyProductionLog::where('user', $user)->whereBetween('created_at', [Carbon::today()->subDays(7), Carbon::today()])->orderBy('created_at', 'ASC')->get();

        foreach ($weeklyEntries[$user] as $entry) {
            $result[(string)$entry->created_at->format('m-d-Y')] = $entry->total_production / 1000;
        }
        $collection[$user] = $result;
    }

    return collect($collection);
}
