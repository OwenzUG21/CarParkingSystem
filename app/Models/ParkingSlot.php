<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    protected $fillable = [
        'parking_location_id',
        'label',
        'is_vip',
        'is_ev_only',
        'is_under_maintenance',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'is_ev_only' => 'boolean',
        'is_under_maintenance' => 'boolean',
    ];

    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }
}

