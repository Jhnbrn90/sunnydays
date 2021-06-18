<?php

namespace App\Http\Livewire;

use App\Contracts\RetrieverInterface;
use App\Services\LiveStatistics;
use Livewire\Component;

class Gauge extends Component
{
    public string $title;
    public string $subtitle;
    public string $property;

    public function render()
    {
        return view('livewire.gauge', [
            'powerStations' => LiveStatistics::getActivePowerStations(),
        ]);
    }
}
