<?php

namespace App\Providers;

use App\Models\GoodWeApi;
use App\Services\YahooWeatherApi;
use App\Services\YahooWeatherProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GoodWeApi::class, function ($app) {
            return new GoodWeApi(
                config('goodwe.account'), config('goodwe.password')
            );
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
