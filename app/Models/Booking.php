<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['booking_number', 'customer_name', 'customer_email', 'cargo_description',
        'cargo_weight', 'origin_country_id', 'destination_country_id', 'origin_port_id',
        'destination_port_id', 'vessel_id', 'departure_at', 'estimated_arrival', 'status',
        'created_by', 'confirmed_at'];

    protected $casts = ['departure_at' => 'datetime', 'estimated_arrival' => 'datetime', 'confirmed_at' => 'datetime'];

    public function originCountry() { return $this->belongsTo(Country::class, 'origin_country_id'); }
    public function destinationCountry() { return $this->belongsTo(Country::class, 'destination_country_id'); }
    public function originPort() { return $this->belongsTo(Port::class, 'origin_port_id'); }
    public function destinationPort() { return $this->belongsTo(Port::class, 'destination_port_id'); }
    public function vessel() { return $this->belongsTo(Vessel::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function shipment() { return $this->hasOne(Shipment::class); }
}
