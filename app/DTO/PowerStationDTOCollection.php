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
                'generating' => $powerStation->nowGenerating(),
                'today' => $powerStation->energyProducedToday(),
                'working' => $powerStation->isWorking(),
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