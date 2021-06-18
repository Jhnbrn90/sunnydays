<?php

namespace App\Services;

use App\Contracts\RetrieverInterface;
use Illuminate\Support\Facades\Cache;

class LiveStatistics
{
    public const LIVE_CACHE_KEY = 'live_statistics_gauge';

    public static function getActivePowerStations(): array
    {
        if (Cache::missing(self::LIVE_CACHE_KEY)) {
            self::populateCache();
        }

        return Cache::get(self::LIVE_CACHE_KEY);
    }

    private static function populateCache(): void
    {
        $powerStations = app(RetrieverInterface::class)
                ->getPowerStations()
                ->registered()
                ->toArray();

        Cache::put(self::LIVE_CACHE_KEY, $powerStations, now()->addMinutes(5));
    }
}