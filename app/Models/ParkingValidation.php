<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingValidation extends Model
{
    protected $fillable = [
        'parking_location_id',
        'business_name',
        'code',
        'free_minutes',
        'max_uses',
        'uses_count',
        'expires_at',
    ];

    protected $casts = [
        'free_minutes' => 'integer',
        'max_uses' => 'integer',
        'uses_count' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }
}

