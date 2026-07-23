<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentEvent extends Model
{
    protected $fillable = ['shipment_id', 'status', 'notes', 'updated_by', 'occurred_at'];
    protected $casts = ['occurred_at' => 'datetime'];
    public function shipment() { return $this->belongsTo(Shipment::class); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
}
