<?php

namespace App\Console;

use App\Console\Commands\MailDailyYield;
use App\Console\Commands\StoreCurrentYield;
use App\Console\Commands\StoreDailyYield;
use App\Console\Commands\MailCurrentYield;
use App\Console\Commands\UpdateDailyProductionAverages;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        StoreCurrentYield::class,
        StoreDailyYield::class,
        MailCurrentYield::class,
        UpdateDailyProductionAverages::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        /**
         * Get the power that is currently generated from each power station.
         */
        $schedule
            ->command(StoreCurrentYield::class)
            ->everyFifteenMinutes();

        /**
         * Log the total yield (so far) for today per system.
         */
        $schedule
            ->command(StoreDailyYield::class)
            ->timezone('Europe/Amsterdam')
            ->hourly()
            ->between('09:00', '23:00');

        /**
         * Log the total yield (so far) for today per system.
         */
        $schedule
            ->command(UpdateDailyProductionAverages::class)
            ->timezone('Europe/Amsterdam')
            ->dailyAt('23:00');

        /**
         * Send (daily) e-mail report of the total produced energy for each system.
         */
        $schedule
            ->command(MailDailyYield::class)
            ->timezone('Europe/Amsterdam')
            ->dailyAt('23:00');

        /**
         * Send (daily) e-mail to check that all systems are operational.
         */
        $schedule
            ->command(MailCurrentYield::class)
            ->timezone('Europe/Amsterdam')
            ->dailyAt('12:00');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
