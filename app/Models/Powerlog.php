<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Powerlog extends Model
{
    protected $guarded = [];

    public function powerStation(): belongsTo
    {
        return $this->belongsTo(PowerStation::class);
    }
}
