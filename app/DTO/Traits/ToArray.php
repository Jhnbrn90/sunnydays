<?php

namespace App\DTO\Traits;

use App\Contracts\PowerStationInterface;

trait ToArray
{
    public function toArray(): array
    {
        return $this->map(function (PowerStationInterface $powerStation) {
            return [
                'owner' => [
                    'name' => $powerStation->owner(),
                    'color' => $powerStation->ownerColor()
                ],
                'working' => $powerStation->isWorking(),
                'generating' => $powerStation->nowGenerating(),
                'today' => $powerStation->energyProducedToday(),
                'total' => $powerStation->energyProducedTotal(),
                'average' => $powerStation->dailyProductionAverage(),
            ];
        })->sortBy('owner.name')->all();
    }
}