<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role',
        'is_active',
        'phone',
        'address',
        'vehicle',
        'email_notifications',
        'sms_notifications',
        'push_notifications',
        'two_factor',
        'share_location',
        'theme_color',
        'dark_mode',
        'must_change_password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'two_factor' => 'boolean',
        'share_location' => 'boolean',
        'dark_mode' => 'boolean',
        'must_change_password' => 'boolean',
    ];

    // Relationships
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function managedLocations()
    {
        return $this->hasMany(ParkingLocation::class, 'manager_id');
    }

    public function keeperAssignments()
    {
        return $this->hasMany(KeeperAssignment::class);
    }

    public function assignedLocations()
    {
        return $this->belongsToMany(ParkingLocation::class, 'keeper_assignments');
    }

    // Role helpers
    public function isMainAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isLotManager(): bool
    {
        return $this->role === 'lot_manager';
    }

    public function isKeeper(): bool
    {
        return $this->role === 'keeper';
    }














    // /** @use HasFactory<\Database\Factories\UserFactory> */
    // use HasFactory, Notifiable;

    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var list<string>
    //  */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    // /**
    //  * The attributes that should be hidden for serialization.
    //  *
    //  * @var list<string>
    //  */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    // /**
    //  * Get the attributes that should be cast.
    //  *
    //  * @return array<string, string>
    //  */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }
}
