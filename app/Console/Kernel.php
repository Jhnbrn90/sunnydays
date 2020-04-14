<?php

namespace App\Console;

use App\Console\Commands\FetchPowerValues;
use App\Console\Commands\LogDailyProducedEnergy;
use App\Console\Commands\SendDailyMail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        FetchPowerValues::class,
        LogDailyProducedEnergy::class,
        SendDailyMail::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        /**
         * Get currently generated power for each system.
         */
        $schedule
            ->command(FetchPowerValues::class)
            ->everyFifteenMinutes();

        /**
         * Log the total energy produced today for each system.
         */
        $schedule
            ->command(LogDailyProducedEnergy::class)
            ->timezone('Europe/Amsterdam')
            ->dailyAt('23:00');

        /**
         * Daily mail at noon to check if all systems are operational.
         */
        $schedule
            ->command(SendDailyMail::class)
            ->timezone('Europe/Amsterdam')
            ->dailyAt('12:00');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
