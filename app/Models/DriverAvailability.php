<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverAvailability extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'driver_id',
        'start_time',
        'end_time',
        'current_location',
        'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Relationship
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Scope to get active availabilities
    public function scopeActive($query)
    {
        return $query->where('status', 'available')
                    ->where('start_time', '<=', now())
                    ->where('end_time', '>=', now());
    }

    // Scope to get availabilities for a specific location
    public function scopeInLocation($query, $location)
    {
        return $query->where('current_location', 'like', "%{$location}%");
    }
}