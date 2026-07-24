<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeeperAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'parking_location_id',
        'status',
        'invite_token',
        'invite_sent_at',
    ];

    protected $casts = [
        'invite_sent_at' => 'datetime',
    ];

    public function keeper()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }
}

