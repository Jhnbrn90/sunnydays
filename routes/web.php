<?php

use Carbon\Carbon;
use App\DailyProductionLog;

Route::get('/', function () {
    $data = getDailyLogs();
    $goodweId = \Config::get('services.goodwe.id');

    // get the past week of values
    $weeklyGraph = DailyProductionLog::whereBetween('created_at', [Carbon::today()->subDays(7), Carbon::today()])->orderBy('created_at', 'ASC')->get();

    $days = $weeklyGraph->map(function ($data) {
        return $data->created_at->formatLocalized('%A %e %B');
    });

    $produced = $weeklyGraph->map(function ($data) {
        return $data->total_production / 1000;
    });

    return view('welcome', compact('data', 'goodweId', 'weeklyGraph', 'days', 'produced'));
});

Route::get('/api/goodwe', 'ApiController@goodWe');

Route::get('/api/hourly', 'ApiController@hourly');
Route::get('/api/daily', 'ApiController@daily');

Route::get('/api/data', function () {
    return getDailyLogs();
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
