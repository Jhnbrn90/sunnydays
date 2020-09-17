<?php

namespace App\Http\Controllers;

use App\Models\Powerlog;
use Carbon\Carbon;

class ShowGraphForDate
{
    public function __invoke(string $date)
    {
        $users = array_keys(config('services.goodwe'));

        return collect($users)->flatMap(function ($user) use ($date) {
            $logs = Powerlog::where('user', $user)->whereDate('created_at', Carbon::parse($date))->get();

            $data = $logs->flatMap(function ($log) {
                return [(string)$log->created_at->format('H:i') => [
                    'power' => $log->current_power,
                    'weather_condition' => $log->weather_condition,
                    'temperature' => $log->temperature
                ]];
            });

            return [$user => $data];
        });
    }
}