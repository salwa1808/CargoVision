<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherSnapshot extends Model
{
    protected $fillable = [
        'country_id',
        'temperature',
        'wind_speed',
        'rainfall',
        'storm_risk',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}