<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    protected $fillable = [
        'country_id',
        'name',
        'latitude',
        'longitude'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}