<?php

namespace App\Http\Controllers;

use App\Contracts\RetrieverInterface;
use App\DailyLiveGraph;
use App\WeeklyGraph;

class IndexController
{
    public function __invoke(RetrieverInterface $retriever)
    {
//        $retriever->getPowerStations();

        return view('welcome', [
            'liveGraphData' => DailyLiveGraph::for('today'),
            'weeklyGraphData' => WeeklyGraph::thisWeek(),
        ]);
    }
}