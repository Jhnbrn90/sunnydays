<?php

namespace App\Console;

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

            foreach ($response as $user => $value) {
                if ($value->power !== 0) {
                    $powerlog = \App\Powerlog::create([
                        'current_power'          => $value->power,
                        'weather_condition'      => $value->condition,
                        'temperature'            => $value->temperature,
                        'weather_condition_code' => $value->code,
                        'user'                   => $user
                    ]);
                }
            }
        })->everyFifteenMinutes();

        $schedule->call(function () {
            $url = \Config::get('app.url') . '/api/daily';
            $response = json_decode(file_get_contents($url, true));

            foreach ($response as $user => $value) {
                \App\DailyProductionLog::create([
                'total_production'  => $value->energy_today,
                'user'              => $user,
            ]);
            }
        })->daily();
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
