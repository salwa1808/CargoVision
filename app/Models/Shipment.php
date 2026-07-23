<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    public const TRANSPORT_MODES = ['ship', 'air', 'truck'];

    public const STATUSES = ['pending', 'loading', 'departed', 'in_transit', 'arrived', 'delivered', 'cancelled'];

    protected $fillable = [
        'tracking_number',
        'booking_id',
        'origin_country_id',
        'destination_country_id',
        'origin_port_id',
        'destination_port_id',
        'vessel_id',
        'transport_mode',
        'status',
        'distance',
        'progress',
        'is_simulated',
        'departure_at',
        'estimated_arrival',
        'arrived_at',
    ];

    protected $casts = [
        'distance' => 'float',
        'progress' => 'integer',
        'is_simulated' => 'boolean',
        'departure_at' => 'datetime',
        'estimated_arrival' => 'datetime',
        'arrived_at' => 'datetime',
    ];

    public function originCountry()
    {
        return $this->belongsTo(Country::class,'origin_country_id');
    }

    public function destinationCountry()
    {
        return $this->belongsTo(Country::class,'destination_country_id');
    }

    public function originPort()
    {
        return $this->belongsTo(Port::class,'origin_port_id');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class,'destination_port_id');
    }

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function booking() { return $this->belongsTo(Booking::class); }
    public function events() { return $this->hasMany(ShipmentEvent::class)->orderByDesc('occurred_at'); }
}
