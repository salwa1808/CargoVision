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

    public function originatingShipments()
    {
        return $this->hasMany(Shipment::class, 'origin_port_id');
    }

    public function destinationShipments()
    {
        return $this->hasMany(Shipment::class, 'destination_port_id');
    }
}
