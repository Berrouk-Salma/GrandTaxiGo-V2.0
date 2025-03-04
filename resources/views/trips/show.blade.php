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