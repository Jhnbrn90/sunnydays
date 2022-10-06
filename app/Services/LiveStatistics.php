<?php

namespace App\Services;

use App\Contracts\RetrieverInterface;
use App\DTO\SmartMeterPowerStationCollection;
use App\Models\PowerStation;
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
        $goodWePowerStations = app(RetrieverInterface::class)
                ->getPowerStations()
                ->registered()
                ->toArray();
        
        $smartMeterPowerStations = SmartMeterPowerStationCollection::fromEloquentCollection(
            PowerStation::where('type', 'push')->get()
        )->toArray();
        
        $powerStations = array_merge($goodWePowerStations, $smartMeterPowerStations);

        Cache::put(self::LIVE_CACHE_KEY, $powerStations, now()->addMinutes(5));
    }
}