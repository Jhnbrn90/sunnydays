<?php

namespace App\Http\Controllers;

use App\DailyProductionLog;

class ShowAverageYield
{
    public function __invoke()
    {
        $users = array_keys(config('services.goodwe'));

        return collect($users)->flatMap(function ($user) {
            return [$user => $this->calculateAverageFor($user)];
        });
    }

    private function calculateAverageFor($user)
    {
        $productionLogs = DailyProductionLog::whereUser($user)->positiveValues()->get();

        $totalYieldInKWh = $productionLogs->sum('total_production') / 1000;
        $dataPoints = $productionLogs->count();

        return number_format($totalYieldInKWh / $dataPoints, 1);
    }
}