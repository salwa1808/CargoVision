<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $fillable = [
        'name',
        'imo',
        'type',
        'status',
        'latitude',
        'longitude',
        'speed',
        'heading',
        'destination',
        'port_id'
    ];

    public function port()
    {
        return $this->belongsTo(Port::class);
    }
}
