<?php

namespace App\Services;

use App\Contracts\WeatherInterface;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Http;

class OpenWeatherMap implements WeatherInterface
{
    public function fetch(): array
    {
        $response = Http::get($this->buildUrl());

        if (! $response->successful()) {
            throw new HttpClientException('Could not reach Open Weather API.');
        }

        return $response->json();
    }

    /**
     * @return array
     * @throws HttpClientException
     */
    public function condition(): array
    {
        $data = $this->fetch();

        return [
            'temperature' => $data['main']['temp'],
            'iconUrl' => $this->generateIconUrl($data['weather'][0]['icon'])
        ];
    }

    private function buildUrl(): string
    {
        $baseUrl = config('openweather.url');
        $location = config('openweather.location');
        $apiKey = config('openweather.key');

        return "{$baseUrl}?q={$location}&appid={$apiKey}&units=metric";
    }

    private function generateIconUrl(string $iconCode): string
    {
        $baseUrl = config('openweather.icon_base_url');
        $extension = config('openweather.icon_extension');

        return "{$baseUrl}/{$iconCode}{$extension}";
    }
}