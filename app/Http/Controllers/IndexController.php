<?php

namespace App\Http\Controllers;

use App\DailyLiveGraph;

class IndexController
{
    public function __invoke()
    {
        return view('welcome', [
            'goodweIds' => json_encode(collect(config('services.goodwe'))),
            'liveGraphData' => DailyLiveGraph::for('today'),
        ]);
    }
}