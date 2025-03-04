@extends('layouts.app')

@section('title', 'My Trips')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h2>My Trips</h2>
    <a href="{{ route('trips.create') }}" class="btn btn-primary">Book New Trip</a>
</div>

@if($trips->isEmpty())
    <div class="alert alert-info">You haven't booked any trips yet.</div>
@else
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-trips" type="button" role="tab" aria-controls="all-trips" aria-selected="true">All</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-trips" type="button" role="tab" aria-controls="pending-trips" aria-selected="false">Pending</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted-trips" type="button" role="tab" aria-controls="accepted-trips" aria-selected="false">Accepted</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed-trips" type="button" role="tab" aria-controls="completed-trips" aria-selected="false">Completed</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled-trips" type="button" role="tab" aria-controls="canceled-trips" aria-selected="false">Canceled</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="all-trips" role="tabpanel" aria-labelledby="all-tab">
                    @include('trips.partials.trip-list', ['trips' => $trips])
                </div>
                <div class="tab-pane fade" id="pending-trips" role="tabpanel" aria-labelledby="pending-tab">
                    @include('trips.partials.trip-list', ['trips' => $trips->where('status', 'pending')])
                </div>
                <div class="tab-pane fade" id="accepted-trips" role="tabpanel" aria-labelledby="accepted-tab">
                    @include('trips.partials.trip-list', ['trips' => $trips->where('status', 'accepted')])
                </div>
                <div class="tab-pane fade" id="completed-trips" role="tabpanel" aria-labelledby="completed-tab">
                    @include('trips.partials.trip-list', ['trips' => $trips->where('status', 'completed')])
                </div>
                <div class="tab-pane fade" id="canceled-trips" role="tabpanel" aria-labelledby="canceled-tab">
                    @include('trips.partials.trip-list', ['trips' => $trips->where('status', 'canceled')])
                </div>
            </div>
        </div>
    </div>
@endif
@endsection