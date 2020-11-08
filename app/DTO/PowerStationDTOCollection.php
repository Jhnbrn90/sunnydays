<?php

namespace App\DTO;

use App\DTO\PowerStation as PowerStationDTO;
use Illuminate\Support\Collection;

class PowerStationDTOCollection extends Collection
{
    public function toJson($options = 0)
    {
        $body = $this->map(function (PowerStation $powerStation) {
            return [
                'name' => $powerStation->owner(),
                'working' => $powerStation->isWorking(),
                'generating' => $powerStation->nowGenerating(),
                'today' => $powerStation->energyProducedToday(),
                'month' => $powerStation->energyProducedThisMonth(),
                'total' => $powerStation->energyProducedTotal(),
            ];
        })->all();

        return json_encode($body);
    }

    public function registered(): self
    {
        return $this->filter(function (PowerStation  $powerStation) {
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