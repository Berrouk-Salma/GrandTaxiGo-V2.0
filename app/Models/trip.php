<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'passenger_id',
        'driver_id',
        'origin_location',
        'destination_location',
        'departure_time',
        'status'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
    ];

    // Relationships
    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Scope to get pending trips
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope to get trips that need to be auto-canceled (past departure time without acceptance)
    public function scopeNeedingAutoCancellation($query)
    {
        return $query->where('status', 'pending')
                    ->where('departure_time', '<', now());
    }
    // Add these methods to your existing Trip model
public function reviews()
{
    return $this->hasMany(Review::class);
}

public function payments()
{
    return $this->hasMany(Payment::class);
}

public function isPaid()
{
    return $this->payments()->successful()->exists();
}
}