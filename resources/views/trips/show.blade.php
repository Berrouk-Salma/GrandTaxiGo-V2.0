@extends('layouts.app')

@section('title', 'Trip Details')

@section('content')
<div class="mb-4">
    <a href="{{ route('trips.index') }}" class="btn btn-secondary">← Back to Trips</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                Trip Details
                <span class="float-end">
                    @if($trip->status === 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($trip->status === 'accepted')
                        <span class="badge bg-primary">Accepted</span>
                    @elseif($trip->status === 'completed')
                        <span class="badge bg-success">Completed</span>
                    @elseif($trip->status === 'canceled')
                        <span class="badge bg-danger">Canceled</span>
                    @endif
                </span>
            </div>

            @if($trip->status === 'completed')
    <!-- Payment section -->
    <hr>
    <div class="row mb-3">
        <div class="col-md-12">
            <h5>Payment Status</h5>
            @if($trip->isPaid())
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i> Payment Completed
                </div>
            @else
                @if(auth()->id() === $trip->passenger_id)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i> Payment Required
                        <a href="{{ route('payments.checkout', $trip) }}" class="btn btn-primary btn-sm float-end">Pay Now</a>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i> Payment Pending from Passenger
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Review section -->
    <hr>
    <div class="row mb-3">
        <div class="col-md-12">
            <h5>Reviews</h5>
            
            @php
                $userReview = $trip->reviews()->where('reviewer_id', auth()->id())->first();
                $otherReview = $trip->reviews()->where('reviewer_id', '!=', auth()->id())->first();
                $reviewTarget = auth()->id() === $trip->passenger_id ? $trip->driver : $trip->passenger;
            @endphp
            
            @if($userReview)
                <div class="card mb-3">
                    <div class="card-header">
                        <span>Your Review</span>
                        <a href="{{ route('reviews.edit', $userReview) }}" class="btn btn-sm btn-primary float-end">Edit</a>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            @stars($userReview->rating)
                        </div>
                        @if($userReview->comment)
                            <p>{{ $userReview->comment }}</p>
                        @else
                            <p class="text-muted">No comment provided.</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> You haven't reviewed this trip yet.
                    <a href="{{ route('reviews.create', $trip) }}" class="btn btn-primary btn-sm float-end">Write a Review</a>
                </div>
            @endif
            
            @if($otherReview)
                <div class="card mt-3">
                    <div class="card-header">
                        <span>{{ $otherReview->reviewer->name }}'s Review</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            @stars($otherReview->rating)
                        </div>
                        @if($otherReview->comment)
                            <p>{{ $otherReview->comment }}</p>
                        @else
                            <p class="text-muted">No comment provided.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>From</h5>
                        <p class="lead">{{ $trip->origin_location }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>To</h5>
                        <p class="lead">{{ $trip->destination_location }}</p>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Departure Time</h5>
                        <p>{{ $trip->departure_time->format('F d, Y - H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Booking Date</h5>
                        <p>{{ $trip->created_at->format('F d, Y - H:i') }}</p>
                    </div>
                </div>

                <hr>
                
                <div class="row">
                    @if(auth()->user()->isPassenger())
                        <div class="col-md-12">
                            <h5>Driver Information</h5>
                            @if($trip->driver)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ Storage::url($trip->driver->profile_image) }}" class="rounded-circle me-3" width="60" height="60">
                                    <div>
                                        <h5 class="mb-0">{{ $trip->driver->name }}</h5>
                                        <p class="text-muted mb-0">{{ $trip->driver->phone }}</p>
                                        <p class="text-muted mb-0">{{ $trip->driver->email }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">No driver assigned yet.</p>
                            @endif
                        </div>
                    @else
                        <div class="col-md-12">
                            <h5>Passenger Information</h5>
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ Storage::url($trip->passenger->profile_image) }}" class="rounded-circle me-3" width="60" height="60">
                                <div>
                                    <h5 class="mb-0">{{ $trip->passenger->name }}</h5>
                                    <p class="text-muted mb-0">{{ $trip->passenger->phone }}</p>
                                    <p class="text-muted mb-0">{{ $trip->passenger->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                @if(auth()->user()->isPassenger() && $trip->status === 'pending' && now()->diffInMinutes($trip->departure_time) >= 60)
                    <form action="{{ route('trips.cancel', $trip) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this trip?')">Cancel Trip</button>
                    </form>
                @elseif(auth()->user()->isDriver() && $trip->status === 'accepted')
                    <form action="{{ route('trips.complete', $trip) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to mark this trip as completed?')">Complete Trip</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Trip Timeline</div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Booked</strong>
                            <p class="mb-0 text-muted">{{ $trip->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <span class="badge bg-success rounded-pill">✓</span>
                    </li>
                    
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Driver Assigned</strong>
                            @if($trip->driver)
                                <p class="mb-0 text-muted">{{ $trip->updated_at->format('M d, Y H:i') }}</p>
                            @else
                                <p class="mb-0 text-muted">Pending</p>
                            @endif
                        </div>
                        @if($trip->driver)
                            <span class="badge bg-success rounded-pill">✓</span>
                        @else
                            <span class="badge bg-secondary rounded-pill">⋯</span>
                        @endif
                    </li>
                    
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Trip Status</strong>
                            <p class="mb-0 text-muted">{{ ucfirst($trip->status) }}</p>
                        </div>
                        @if($trip->status === 'completed')
                            <span class="badge bg-success rounded-pill">✓</span>
                        @elseif($trip->status === 'canceled')
                            <span class="badge bg-danger rounded-pill">✕</span>
                        @else
                            <span class="badge bg-secondary rounded-pill">⋯</span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection