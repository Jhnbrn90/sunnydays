<?php

namespace App\Providers;

use App\Contracts\RetrieverInterface;
use App\Services\GoodWeApi;
use App\Services\YahooWeatherApi;
use App\Services\YahooWeatherProvider;
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

        $this->app->singleton(YahooWeatherApi::class, function ($app) {
            return new YahooWeatherApi(
                $appId = config('yahoo.app_id'),
                $baseUrl = config('yahoo.url'),
                $consumerKey = config('yahoo.consumer_key'),
                $consumerSecret = config('yahoo.consumer_secret'),
                $temperatureUnit = config('yahoo.temperature_unit'),
                $weatherLocation = config('yahoo.location')
            );
        });

        $this->app->singleton(YahooWeatherProvider::class, function ($app) {
            return new YahooWeatherProvider(
                $app->make(YahooWeatherApi::class)
            );
        });
    }
}
