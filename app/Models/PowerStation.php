<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerStation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function powerlogs()
    {
        return $this->hasMany(Powerlog::class);
    }

    public function storeCurrentYield(int $currentYield)
    {
        $this->powerlogs()->create([
            'current_power' => $currentYield
        ]);
    }
}
