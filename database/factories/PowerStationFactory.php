<?php

namespace Database\Factories;

use App\Models\PowerStation;
use Illuminate\Database\Eloquent\Factories\Factory;

class PowerStationFactory extends Factory
{
    protected $model = PowerStation::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'goodwe_id' => 'FAKE-ID-' . $this->faker->randomDigit,
            'line_color' => $this->faker->rgbColor,
        ];
    }
}
