<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $fillable = [
        'name',
        'mmsi',
        'imo',
        'call_sign',
        'type',
        'ais_ship_type',
        'status',
        'latitude',
        'longitude',
        'speed',
        'heading',
        'course',
        'destination',
        'position_reported_at',
        'data_source',
        'port_id'
    ];

    protected $casts = ['position_reported_at' => 'datetime', 'latitude' => 'float', 'longitude' => 'float', 'speed' => 'float'];

    public function port()
    {
        return $this->belongsTo(Port::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
