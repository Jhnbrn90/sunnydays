<?php

namespace Database\Seeders;

use App\Models\PowerStation;
use Illuminate\Database\Seeder;

class PowerStationTableSeeder extends Seeder
{
    public function run()
    {
        PowerStation::factory()->create([
            'name' => 'JL',
            'goodwe_id' => 'd170f803-22fd-41e2-81d5-9b039f957e81',
            'line_color' => '255, 165, 120',
        ]);

        PowerStation::factory()->create([
            'name' => 'MB',
            'goodwe_id' => 'ae26ebf7-41f8-425f-8927-8de03d5ebf59',
            'line_color' => '2, 158, 227',
        ]);

        PowerStation::factory()->create([
            'name' => 'BE',
            'goodwe_id' => '62f57f9d-9369-4cc5-88f8-3d0537672077',
            'line_color' => '0, 153, 51',
        ]);

        PowerStation::factory()->create([
            'name' => 'RB',
            'goodwe_id' => '5b67540e-072c-46a1-abc6-eb76d8079b5c',
            'line_color' => '95, 66, 244',
        ]);
    }
}