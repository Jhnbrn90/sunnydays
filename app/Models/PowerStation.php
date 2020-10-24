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
}
