<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reviewer_id',
        'reviewed_id',
        'trip_id',
        'rating',
        'comment'
    ];

    // Relationship to the user who wrote the review
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Relationship to the user who received the review
    public function reviewed()
    {
        return $this->belongsTo(User::class, 'reviewed_id');
    }

    // Relationship to the trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Scope for passenger reviews (about drivers)
    public function scopePassengerReviews($query)
    {
        return $query->whereHas('reviewer', function($q) {
            $q->where('user_type', 'passenger');
        });
    }

    // Scope for driver reviews (about passengers)
    public function scopeDriverReviews($query)
    {
        return $query->whereHas('reviewer', function($q) {
            $q->where('user_type', 'driver');
        });
    }
}