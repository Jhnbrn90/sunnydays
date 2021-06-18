<?php

namespace App;

use App\Models\PowerStation;
use App\Services\LiveStatistics;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class DailyLiveGraph
{
    public static function for(string $date = null)
    {
        $carbonDate = $date ? Carbon::parse($date) : Carbon::today();
        $cacheKey = self::getCacheKeyFor($carbonDate);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        if ($carbonDate->isToday()) {
            return self::getPowerStationDataForToday();
        }

        return self::getPowerStationDataForDate($carbonDate);
    }

    public static function getPowerStationDataForDate(Carbon $date): array
    {
        $cacheKey = self::getCacheKeyFor($date);

        $powerStationData = PowerStation::all()->map(function (PowerStation $powerStation) use ($date) {
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

        Cache::forever($cacheKey, $powerStationData);

        return $powerStationData;
    }

    private static function getPowerStationDataForToday(): array
    {
        $today = Carbon::today();
        $cacheKey = self::getCacheKeyFor($today);
        $ttl = now()->addMinutes(5);

        $powerStationData = self::getPowerStationDataForDate($today);

        self::populateCache($cacheKey, $powerStationData, $ttl);

        return $powerStationData;
    }

    private static function getCacheKeyFor(Carbon $date): string
    {
        $formattedDate = $date->format('d-m-Y');

        return "graph.{$formattedDate}";
    }

    private static function populateCache(string $cacheKey, array $data, ?Carbon $ttl = null): void
    {
        $ttl ? Cache::put($cacheKey, $data, $ttl) : Cache::forever($cacheKey, $data);
    }
}