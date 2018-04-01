<?php

namespace App\Console;

use App\Events\PeriodicLogUpdated;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $url = \Config::get('app.url') . '/api/hourly';
            $response = json_decode(file_get_contents($url, true));

            $powerlog = \App\Powerlog::create([
                'current_power'          => $response->power,
                'weather_condition'      => $response->condition,
                'temperature'            => $response->temperature,
                'weather_condition_code' => $response->code,
            ]);

            PeriodicLogUpdated::dispatch($powerlog);
        })->everyFifteenMinutes();

        $schedule->call(function () {
            $url = \Config::get('app.url') . '/api/daily';
            $response = json_decode(file_get_contents($url, true));

            \App\DailyProductionLog::create([
                'total_production'  => $response->energy_today,
            ]);
        })->dailyAt('23:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
