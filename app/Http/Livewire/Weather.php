<?php

namespace App\Http\Livewire;

use App\Services\WeatherProvider;
use Livewire\Component;

class Weather extends Component
{
    public string $temperature;
    public string $iconClass;

    public function mount(WeatherProvider $yahoo)
    {
        $this->temperature = $yahoo->condition()['temperature'];
        $this->iconClass = $yahoo->condition()['code'];
    }

    public function render()
    {
        return view('livewire.weather', [
            'date' => now()->format('l, j F Y'),
            'time' => now()->format('H:i'),
        ]);
    }
}
