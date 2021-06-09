<?php

namespace App\Services;

use App\Contracts\WeatherInterface;
use Illuminate\Http\Client\HttpClientException;

class WeatherProvider
{
    private WeatherInterface $api;

    public function __construct(WeatherInterface $api)
    {
        $this->api = $api;
    }

    public function condition(): array
    {
        try {
            $condition = $this->api->condition();
        } catch (HttpClientException) {
            return [];
        }

        return [
            'temperature' => $condition['temperature'],
            'iconUrl' => $condition['iconUrl'],
        ];
    }
}