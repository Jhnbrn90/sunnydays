<?php

namespace App\DTO;

use App\DTO\PowerStation as PowerStationDTO;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PowerStationDTOCollection extends Collection
{
    public function toArray(): array
    {
        return Cache::remember('live_statistics_gauge', now()->addMinutes(5), function() {
            return $this->map(function (PowerStationDTO $powerStation) {
                return [
                    'owner' => [
                        'name' => $powerStation->owner(),
                        'color' => $powerStation->ownerColor()
                    ],
                    'working' => $powerStation->isWorking(),
                    'generating' => $powerStation->nowGenerating(),
                    'today' => $powerStation->energyProducedToday(),
                    'month' => $powerStation->energyProducedThisMonth(),
                    'total' => $powerStation->energyProducedTotal(),
                    'average' => $powerStation->dailyProductionAverage(),
                ];
            })->sortBy('owner.name')->all();
        });
    }

    public function registered(): self
    {
        return $this->filter(function (PowerStationDTO $powerStation) {
            return $powerStation->shouldRetrieveData();
        });
    }

    public function working(): self
    {
        return $this->filter(function (PowerStationDTO $powerStation) {
            return $powerStation->isWorking();
        });
    }
}