<?php

namespace App\Http\Livewire;

use App\Services\WeatherProvider;
use Livewire\Component;

class Weather extends Component
{
    public string $temperature;
    public string $iconUrl;

    public function mount(WeatherProvider $weatherProvider)
    {
        $currentCondition = $weatherProvider->condition();

        if ($currentCondition !== null) {
            $this->temperature = number_format($currentCondition ['temperature'], 0);
            $this->iconUrl = $currentCondition['iconUrl'];
        }
    }

    public function render()
    {
        return view('livewire.weather', [
            'date' => now()->format('l, j F Y'),
            'time' => now()->format('H:i'),
        ]);
    }
}
