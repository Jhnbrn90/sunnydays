<?php

namespace App\Http\Controllers;

use App\Models\DailyProductionLog;
use App\Models\PowerStation;

class ShowWeekOverview
{
    public function __invoke()
    {
        return PowerStation::all()->flatMap(function (PowerStation $powerStation) {
            $productionLogs = $powerStation
                ->dailyProductionLogs()
                ->thisWeek()
                ->get()
                ->flatMap(fn ($log) => [$this->date($log) => $this->value($log)]);

            return [$powerStation->name => $productionLogs];
        });
    }

    /**
     * Format the date of the logged production.
     *
     * @param DailyProductionLog $log
     * @return string
     */
    private function date(DailyProductionLog $log): string
    {
        return (string) $log->created_at->format('m-d-Y');
    }

    /**
     * Return the daily production value in kWh.
     *
     * @param DailyProductionLog $log
     * @return float|int
     */
    private function value(DailyProductionLog $log)
    {
        return $log->total_production / 1000;
    }
}