<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trip_id',
        'user_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'payment_details'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'amount' => 'decimal:2'
    ];

    // Relationship to the trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Relationship to the user who made the payment
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for successful payments
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'succeeded');
    }

    // Scope for pending payments
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for failed payments
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}