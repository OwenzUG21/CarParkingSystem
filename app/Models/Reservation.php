<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
     protected $fillable = [
        'user_id', 'parking_location_id', 'date', 'start_time',
        'end_time', 'vehicle', 'amount', 'status', 'booking_date'
    ];

    protected $casts = [
        'date' => 'date',
        'booking_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
