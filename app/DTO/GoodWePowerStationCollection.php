<?php

namespace App\DTO;

use Illuminate\Support\Collection;

class GoodWePowerStationCollection extends Collection
{
    public function toArray(): array
    {
        return $this->map(function (GoodWePowerStation $powerStation) {
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
    }

    public function registered(): self
    {
        return $this->filter(function (GoodWePowerStation $powerStation) {
            return $powerStation->shouldRetrieveData();
        });
    }

    public function working(): self
    {
        return $this->filter(function (GoodWePowerStation $powerStation) {
            return $powerStation->isWorking();
        });
    }
}