<?php

namespace App\Services;

use App\Contracts\WeatherInterface;

class WeatherProvider
{
    private WeatherInterface $api;
    private array $data;

    public function __construct(WeatherInterface $api)
    {
        $this->api = $api;
    }

    public function condition()
    {
        $this->fetch();

        return $this->data['current_observation']['condition'];
    }

    private function fetch()
    {
        $this->data = $this->api->fetch();
    }
}