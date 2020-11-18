<?php

namespace App\Http\Livewire;

use App\Contracts\RetrieverInterface;
use Livewire\Component;

class Summary extends Component
{
    public function render()
    {
        return view('livewire.summary', [
            'powerStations' => app(RetrieverInterface::class)->getPowerStations()->registered()->toArray(),
        ]);
    }
}
