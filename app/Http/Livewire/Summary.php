<?php

namespace App\Http\Livewire;

use App\Contracts\RetrieverInterface;
use App\Services\LiveStatistics;
use Livewire\Component;

class Summary extends Component
{
    public function render()
    {
        return view('livewire.summary', [
            'powerStations' => LiveStatistics::getActivePowerStations(),
        ]);
    }
}
