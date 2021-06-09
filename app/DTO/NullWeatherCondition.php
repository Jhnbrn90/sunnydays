<?php

namespace App\DTO;

use App\Contracts\WeatherConditionInterface;

class NullWeatherCondition implements WeatherConditionInterface
{
    public function getTemperature(): float
    {
        return 0.00;
    }

    public function getIconUrl(): string
    {
        return '';
    }
}