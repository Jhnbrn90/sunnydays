<?php

namespace App;

use App\Models\PowerStation;
use Carbon\Carbon;

class DailyLiveGraph
{
    public static function for(string $date = null)
    {
        return PowerStation::all()->map(function ($powerStation) use ($date) {
            $date = $date ? Carbon::parse($date) : Carbon::today();

            $logs = $powerStation->powerlogs()->whereDate('created_at', $date)->get();

            $dataPoints = $logs->map(function ($log) {
                return [
                    "x" => $log->created_at->format('H:i'),
                    "y" => $log->current_power
                ];
            });

            return [
                'label' => $powerStation->name,
                'data' => $dataPoints,
                'fill' => false,
                'borderColor' => "rgb({$powerStation->line_color})",
                'backgroundColor' => 'rgba(255, 255, 255, 0.1)'
            ];
        });
    }
}