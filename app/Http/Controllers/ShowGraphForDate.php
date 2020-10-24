<?php

namespace App\Http\Controllers;

use App\DailyLiveGraph;

class ShowGraphForDate
{
    public function __invoke(string $date = null)
    {
        return DailyLiveGraph::for($date);
    }
}