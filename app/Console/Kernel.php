<?php

namespace App\Console;

use App\Console\Commands\FetchPowerValues;
use App\Console\Commands\LogDailyProducedEnergy;
use App\Console\Commands\SendDailyMail;
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
        FetchPowerValues::class,
        LogDailyProducedEnergy::class,
        SendDailyMail::class,
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
         * Get currently generated power for each system.
         */
        $schedule
            ->command('sunnydays:store-currently-generating')
            ->everyFifteenMinutes();

        /**
         * Log the total energy produced today for each system.
         */
        $schedule
            ->command('sunnydays:store-daily-produced-energy')
            ->timezone('Europe/Amsterdam')
            ->dailyAt('23:00');

        /**
         * Daily mail at noon to check if all systems are operational.
         */
        $schedule
            ->command('sunnydays:send-daily-mail')
            ->timezone('Europe/Amsterdam')
            ->dailyAt('12:00');
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
