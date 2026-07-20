<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EconomicIndicator extends Model
{
    protected $fillable = [
        'country_id',
        'gdp',
        'inflation',
        'population',
        'year',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}