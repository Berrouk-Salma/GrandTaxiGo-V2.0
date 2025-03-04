@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="text-center mb-4">
    <h2>Welcome to GrandTaxiGo, {{ auth()->user()->name }}!</h2>
    <p class="lead">Your go-to platform for intercity taxi bookings.</p>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">My Trips</h5>
                <p class="card-text">View and manage your trips.</p>
                <a href="{{ route('trips.index') }}" class="btn btn-primary">Go to My Trips</a>
            </div>
        </div>
    </div>

    @if(auth()->user()->isPassenger())
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Book a Trip</h5>
                    <p class="card-text">Need a ride? Book a new trip now!</p>
                    <a href="{{ route('trips.create') }}" class="btn btn-success">Book Now</a>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">My Availabilities</h5>
                    <p class="card-text">Manage your driving availabilities.</p>
                    <a href="{{ route('availabilities.index') }}" class="btn btn-success">Manage Availabilities</a>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Quick Stats</div>
            <div class="card-body">
                <div class="row text-center">
                    @if(auth()->user()->isPassenger())
                        <div class="col-md-4">
                            <h5>{{ auth()->user()->tripsAsPassenger()->count() }}</h5>
                            <p>Total Trips</p>
                        </div>
                        <div class="col-md-4">
                            <h5>{{ auth()->user()->tripsAsPassenger()->where('status', 'completed')->count() }}</h5>
                            <p>Completed Trips</p>
                        </div>
                        <div class="col-md-4">
                            <h5>{{ auth()->user()->tripsAsPassenger()->where('status', 'pending')->count() }}</h5>
                            <p>Pending Trips</p>
                        </div>
                    @else
                        <div class="col-md-3">
                            <h5>{{ auth()->user()->tripsAsDriver()->count() }}</h5>
                            <p>Total Trips</p>
                        </div>
                        <div class="col-md-3">
                            <h5>{{ auth()->user()->tripsAsDriver()->where('status', 'completed')->count() }}</h5>
                            <p>Completed Trips</p>
                        </div>
                        <div class="col-md-3">
                            <h5>{{ auth()->user()->tripsAsDriver()->where('status', 'accepted')->count() }}</h5>
                            <p>Active Trips</p>
                        </div>
                        <div class="col-md-3">
                            <h5>{{ auth()->user()->availabilities()->where('status', 'available')->count() }}</h5>
                            <p>Active Availabilities</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection