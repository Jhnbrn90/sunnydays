<?php

namespace App\Console;

use App\Mail\HeartbeatMail;
use App\Mail\StatisticsMail;
use Illuminate\Support\Facades\Mail;
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
        /**
         * Get currently generated power (hourly)
         */
        $schedule->call(function () {
            $url = \Config::get('app.url') . '/api/hourly';
            $response = json_decode(file_get_contents($url, true));

            foreach ($response as $user => $value) {
                if ($value->power > 50) {
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

        /**
         * Log produced energy daily
         */
        $schedule->call(function () {
            $url = \Config::get('app.url') . '/api/daily';
            $response = json_decode(file_get_contents($url, true));

            $log = [];

            foreach ($response as $user => $value) {
                $logs[$user] = \App\DailyProductionLog::create([
                    'total_production'  => $value->energy_today,
                    'user'              => $user,
                ]);    
            }

            Mail::to(config('app.mail'))->send(new StatisticsMail($logs));

        })->timezone('Europe/Amsterdam')->dailyAt('23:00');

        /**
         * Heartbeat
         */
        $schedule->call(function () {
            $url = \Config::get('app.url') . '/api/hourly';
            $response = json_decode(file_get_contents($url, true));

            $values = [];

            foreach ($response as $user => $value) {
                $values[$user] = $value->power;
                $weather['condition'] = $value->condition;
                $weather['temperature'] = $value->temperature;
            }

            Mail::to(config('app.mail'))->send(new HeartbeatMail($values, $weather));

        })->timezone('Europe/Amsterdam')->dailyAt('12:00');
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
