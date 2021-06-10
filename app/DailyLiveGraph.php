<?php

namespace App;

use App\Models\PowerStation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class DailyLiveGraph
{
    public static function for(string $date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::today();
        $formattedDate = $date->format('d-m-Y');
        $cacheKey = "graph.{$formattedDate}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $powerStationData = static::getPowerStationDataForDate($date);

        if (! $date->isToday()) {
            Cache::forever($cacheKey, $powerStationData);
        }

        return $powerStationData;
    }

    public static function getPowerStationDataForDate(Carbon $date)
    {
        return PowerStation::all()->map(function (PowerStation $powerStation) use ($date) {
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