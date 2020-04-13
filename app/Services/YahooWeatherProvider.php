<?php

namespace App\Services;

class YahooWeatherProvider
{
    private $api;
    private $data;

    public function __construct(YahooWeatherApi $api)
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