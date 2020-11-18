<?php

namespace Database\Seeders;

use App\Models\Powerlog;
use App\Models\PowerStation;
use Illuminate\Database\Seeder;

class PowerStationIdSeeder extends Seeder
{
    public function run()
    {
        PowerStation::all()->each(function ($powerStation) {
            Powerlog::where('user', $powerStation->name)->update([
                'power_station_id' => $powerStation->id,
            ]);
        });
    }
}