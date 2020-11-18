<?php

namespace App\Http\Controllers;

use App\DailyLiveGraph;
use App\WeeklyGraph;

class DashboardController
{
    public function __invoke()
    {
        return view('dashboard', [
            'liveChartData' => DailyLiveGraph::for('today'),
            'weekChartData' => WeeklyGraph::thisWeek(),
        ]);
    }
}