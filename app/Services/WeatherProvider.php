<?php

namespace App\Services;

use App\Contracts\WeatherConditionInterface;
use App\Contracts\WeatherInterface;
use App\DTO\NullWeatherCondition;
use Illuminate\Http\Client\HttpClientException;

class WeatherProvider
{
    private WeatherInterface $api;

    public function __construct(WeatherInterface $api)
    {
        $this->api = $api;
    }

    public function condition(): WeatherConditionInterface
    {
        try {
            $condition = $this->api->condition();
        } catch (HttpClientException) {
            return new NullWeatherCondition();
        }

        return $condition;
    }
}