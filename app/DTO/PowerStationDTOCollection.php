<?php

namespace App\DTO;

use App\DTO\PowerStation as PowerStationDTO;
use Illuminate\Support\Collection;

class PowerStationDTOCollection extends Collection
{
    public function toJson($options = 0)
    {
        $body = $this->map(function (PowerStationDTO $powerStation) {
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
        })->all();

        return json_encode($body);
    }

    public function toArray(): array
    {
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
        })->all();
    }

    public function registered(): self
    {
        return $this->filter(function (PowerStationDTO  $powerStation) {
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