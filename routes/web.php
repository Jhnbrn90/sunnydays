<?php

use Carbon\Carbon;
use App\DailyProductionLog;

Route::get('/', function () {
    $goodweId = \Config::get('services.goodwe.JL');
    $goodweIdMB = \Config::get('services.goodwe.MB');

    // get the past week of values
    $users = ['JL', 'MB'];

    foreach($users as $user) {
        $weeklyGraph[$user] = DailyProductionLog::where('user', $user)->whereBetween('created_at', [Carbon::today()->subDays(7), Carbon::today()])->orderBy('created_at', 'ASC')->get();
        $produced[$user] = $weeklyGraph[$user]->map(function ($data) {
            return $data->total_production / 1000;
        });
    }

    $produced = json_encode($produced);

    $days = $weeklyGraph[$users[0]]->map(function ($data) {
        return $data->created_at->formatLocalized('%A %e %B');
    });

    return view('welcome', compact('goodweId', 'goodweIdMB', 'days', 'produced'));
});

Route::get('/api/goodwe/{id}', 'ApiController@goodWe');

Route::get('/api/hourly', 'ApiController@hourly');
Route::get('/api/daily', 'ApiController@daily');

Route::get('/api/data', function () {
    return getDailyLogs();
});

Route::get('/api/dailygraph/{date}', function ($date) {
    return getdailyLogsFor($date);
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
