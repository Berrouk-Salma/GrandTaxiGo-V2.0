<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_type',
        'profile_image',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function tripsAsPassenger()
    {
        return $this->hasMany(Trip::class, 'passenger_id');
    }

    public function tripsAsDriver()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    public function availabilities()
    {
        return $this->hasMany(DriverAvailability::class, 'driver_id');
    }

    // Check if user is a driver
    public function isDriver()
    {
        return $this->user_type === 'driver';
    }

    // Check if user is a passenger
    public function isPassenger()
    {
        return $this->user_type === 'passenger';
    }

    // Add these methods to your existing User model
public function reviewsReceived()
{
    return $this->hasMany(Review::class, 'reviewed_id');
}

public function reviewsGiven()
{
    return $this->hasMany(Review::class, 'reviewer_id');
}

public function payments()
{
    return $this->hasMany(Payment::class);
}

// Helper method to check if user is admin
public function isAdmin()
{
    return $this->role === 'admin';
}

// Average rating received
public function averageRating()
{
    return $this->reviewsReceived()->avg('rating') ?? 0;
}
}