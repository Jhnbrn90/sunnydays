<?php

namespace App\DTO;

use App\Contracts\PowerStationInterface;
use App\Models\PowerStation;

class SmartMeterPowerStation implements PowerStationInterface
{
    private PowerStation $model;

    public function __construct(PowerStation $model)
    {
        $this->model = $model;    
    }
    
    public function owner(): string
    {
        return $this->model->name;
    }

    public function ownerColor(): string
    {
        return $this->model->line_color;
    }

    public function nowGenerating(): int
    {
        return $this->model->powerlogs()->latest()->first()->current_power;
    }
    
    public function isWorking(): bool
    {
        return true;
    }

    public function energyProducedToday(): float
    {
         return $this->model->powerlogs()->latest()->first()->kwh_today;
 
    }

    public function energyProducedTotal(): int
    {
         return $this->model->powerlogs()->latest()->first()->kwh_total;
    }

    public function dailyProductionAverage(): ?float
    {
        return $this->model->daily_average;
    }
}