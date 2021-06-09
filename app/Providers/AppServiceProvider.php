<?php

namespace App\Providers;

use App\Contracts\RetrieverInterface;
use App\Contracts\WeatherInterface;
use App\Services\GoodWeApi;
use App\Services\OpenWeatherMap;
use App\Services\WeatherProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind(RetrieverInterface::class, function ($app) {
            return new GoodWeApi;
        });

        $this->app->bind(WeatherInterface::class, function ($app) {
            return new OpenWeatherMap();
        });

        $this->app->bind(WeatherProvider::class, function ($app) {
            return new WeatherProvider(
                $app->make(WeatherInterface::class)
            );
        });
    }
}
