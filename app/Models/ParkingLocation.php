<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingLocation extends Model
{
      protected $fillable = [
        'name', 'address', 'lat', 'lng', 'rating', 'price',
        'available', 'total', 'distance', 'image', 'features', 'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'rating' => 'decimal:1',
        'price' => 'decimal:2',
        'distance' => 'decimal:2',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
