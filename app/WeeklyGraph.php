<?php

namespace App;

use App\Models\PowerStation;

class WeeklyGraph
{
    public static function thisWeek()
    {
        return PowerStation::all()->map(function (PowerStation $powerStation) {
            $dailyProductionLogs = $powerStation
                ->dailyProductionLogs()
                ->thisWeek()
                ->get();

            $dataPoints = $dailyProductionLogs->map(function ($log) {
                return [
                    "x" => self::date($log),
                    "y" => self::value($log),
                ];
            });

            return [
                'label' => $powerStation->name,
                'data' => $dataPoints,
                'fill' => false,
                'backgroundColor' => "rgb({$powerStation->line_color})",
            ];
        });
    }

    private static function date($log)
    {
        return $log->created_at->format('Y-m-d');
    }

    private static function value($log)
    {
        return $log->total_production / 1000;
    }
}