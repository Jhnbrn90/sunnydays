<?php

namespace App\DTO;

use App\Contracts\WeatherConditionInterface;

class WeatherCondition implements WeatherConditionInterface
{
    private float $temperature;
    private string $iconUrl;

    public function __construct(float $temperature, string $iconUrl)
    {
        $this->temperature = $temperature;
        $this->iconUrl = $iconUrl;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }
}