<?php

namespace App\Providers;

use App\GoodWeApi;
use App\Services\YahooWeatherApi;
use App\Services\YahooWeatherProvider;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\VarDumper\Cloner\Data;

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
        $this->app->singleton('httpClient', function ($app) {
            return new Client();
        });

        $this->app->singleton(GoodWeApi::class, function ($app) {
            return new GoodWeApi($app->make('httpClient'));
        });

        $this->app->singleton(YahooWeatherApi::class, function ($app) {
            return new YahooWeatherApi(
                $appId = config('yahoo.app_id'),
                $baseUrl = config('yahoo.url'),
                $consumerKey = config('yahoo.consumer_key'),
                $consumerSecret = config('yahoo.consumer_secret'),
                $temperatureUnit = config('yahoo.temperature_unit'),
                $weatherLocation = config('yahoo.location'),
                $httpClient = $app->make('httpClient')
            );
        });

        $this->app->singleton(YahooWeatherProvider::class, function ($app) {
            return new YahooWeatherProvider($app->make(YahooWeatherApi::class));
        });
    }
}
