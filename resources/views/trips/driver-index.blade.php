@extends('layouts.app')

@section('title', 'My Trips')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h2>My Trips</h2>
    <a href="{{ route('availabilities.create') }}" class="btn btn-primary">Add Availability</a>
</div>

@if(!$pendingTrips->isEmpty())
    <div class="card mb-4">
        <div class="card-header bg-info text-white">Available Trips Matching Your Location</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Passenger</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Departure</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingTrips as $trip)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ Storage::url($trip->passenger->profile_image) }}" class="rounded-circle me-2" width="40" height="40">
                                        {{ $trip->passenger->name }}
                                    </div>
                                </td>
                                <td>{{ $trip->origin_location }}</td>
                                <td>{{ $trip->destination_location }}</td>
                                <td>{{ $trip->departure_time->format('M d, Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('trips.accept', $trip) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success">Accept Trip</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@if($trips->isEmpty())
    <div class="alert alert-info">You haven't accepted any trips yet.</div>
@else
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-trips" type="button" role="tab" aria-controls="all-trips" aria-selected="true">All</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted-trips" type="button" role="tab" aria-controls="accepted-trips" aria-selected="false">Active</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-trips" type="button" role="tab" aria-controls="completed-trips" aria-selected="false">Completed</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="all-trips" role="tabpanel" aria-labelledby="all-tab">
                    @include('trips.partials.driver-trip-list', ['trips' => $trips])
                </div>
                <div class="tab-pane fade" id="accepted-trips" role="tabpanel" aria-labelledby="accepted-tab">
                    @include('trips.partials.driver-trip-list', ['trips' => $trips->where('status', 'accepted')])
                </div>
                <div class="tab-pane fade" id="completed-trips" role="tabpanel" aria-labelledby="completed-tab">
                    @include('trips.partials.driver-trip-list', ['trips' => $trips->where('status', 'completed')])
                </div>
            </div>
        </div>
    </div>
@endif
@endsection