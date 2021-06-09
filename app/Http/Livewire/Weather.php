<?php

namespace App\Http\Livewire;

use App\DTO\NullWeatherCondition;
use App\DTO\WeatherCondition;
use App\Services\WeatherProvider;
use Livewire\Component;

class Weather extends Component
{
    public string $temperature;
    public string $iconUrl;

    public function mount(WeatherProvider $weatherProvider)
    {
        $currentCondition = $weatherProvider->condition();

        if ($currentCondition instanceof NullWeatherCondition) {
            $this->temperature = 'N/A';
            $this->iconUrl = url('favicon-32x32.png');
        }

        if ($currentCondition instanceof WeatherCondition) {
            $this->temperature = number_format($currentCondition->getTemperature(), 0);
            $this->iconUrl = $currentCondition->getIconUrl();
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
