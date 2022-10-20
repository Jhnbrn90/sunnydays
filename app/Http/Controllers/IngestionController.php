<?php

namespace App\Http\Controllers;

use App\Models\PowerStation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IngestionController
{
    public function __invoke(Request $request)
    {
        $key = $request->header('x-api-key');
        
        if ($key !== config('app.api_key')) {
            return response()->json([
                'message' => 'No valid API key',
            ], 401);
        }

        $powerStation = PowerStation::findOrfail($request->powerstation_id);
        $powerStation->powerlogs()->create([
           'current_power' => $request->power,
           'kwh_today' => $request->kwh_today,
        ]);
        
        $dailyProductionLog = $powerStation->dailyProductionLogs()->firstOrNew(['created_at' => Carbon::today()]);
        $dailyProductionLog->total_production = $request->kwh_today * 1000;
        $dailyProductionLog->save();
        
        return ['message' => 'Recorded'];
    }
}
