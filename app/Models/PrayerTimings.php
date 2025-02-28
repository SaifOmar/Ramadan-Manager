<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerTimings extends Model
{
    //
    protected $fillable = ['timings'];
    public function casts(): array
    {
        return [
            'timings' => 'array'
        ];
    }
}
