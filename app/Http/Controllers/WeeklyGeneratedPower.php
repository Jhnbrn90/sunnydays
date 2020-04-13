<?php

namespace App\Http\Controllers;

use App\DailyProductionLog;

class WeeklyGeneratedPower
{
    public function __invoke()
    {
        $users = array_keys(config('services.goodwe'));

        return collect($users)->flatMap(function ($user) {
            $logs = DailyProductionLog::where('user', $user)->thisWeek()->get();

            $data = $logs->flatMap(function ($log) {
                return [(string) $log->created_at->format('m-d-Y') => $log->total_production / 1000];
            });

           return [$user => $data];
        });
    }
}