<?php

namespace App\DTO;

use App\Contracts\PowerStationInterface;
use App\Models\Powerlog;
use App\Models\PowerStation;

class SmartMeterPowerStation implements PowerStationInterface
{
    private PowerStation $model;
    private ?Powerlog $latestPowerlog;

    public function __construct(PowerStation $model)
    {
        $this->model = $model;
        $this->latestPowerlog = $model->powerlogs()->latest()->first();
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
        if ($this->latestPowerlog->created_at->diffInMinutes(now()) >= 60) {
            return '0';
        }
        
        return $this->latestPowerlog->current_power;
    }
    
    public function isWorking(): bool
    {
        return true;
    }

    public function energyProducedToday(): float
    {
        if (! $this->latestPowerlog->created_at->isToday()) {
            return '0';
        }

         return $this->latestPowerlog->kwh_today;
    }

    public function energyProducedTotal(): int
    {
        return $this->model->total_energy + $this->latestPowerlog->kwh_today;
    }

    public function dailyProductionAverage(): ?float
    {
        return $this->model->daily_average;
    }
}