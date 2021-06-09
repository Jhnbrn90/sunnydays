<?php

namespace App\Contracts;

use Illuminate\Http\Client\HttpClientException;

Interface WeatherInterface
{
    /**
     * @throws HttpClientException
     * @return array
     */
    public function fetch(): array;
}