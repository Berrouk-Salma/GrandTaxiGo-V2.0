@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Total Users</h5>
                <p class="display-4">{{ $stats['total_users'] }}</p>
                <div class="text-muted">
                    <span class="text-primary">{{ $stats['total_passengers'] }}</span> Passengers, 
                    <span class="text-success">{{ $stats['total_drivers'] }}</span> Drivers
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Total Trips</h5>
                <p class="display-4">{{ $stats['total_trips'] }}</p>
                <div class="text-muted">
                    <span class="text-success">{{ $stats['completed_trips'] }}</span> Completed, 
                    <span class="text-warning">{{ $stats['pending_trips'] }}</span> Pending,
                    <span class="text-danger">{{ $stats['canceled_trips'] }}</span> Canceled
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Total Revenue</h5>
                <p class="display-4">${{ number_format($stats['total_revenue'], 2) }}</p>
                <div class="text-muted">
                    From all successful payments
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5 class="card-title">Avg. Trip Value</h5>
                @php
                    $avgValue = $stats['completed_trips'] > 0 ? $stats['total_revenue'] / $stats['completed_trips'] : 0;
                @endphp
                <p class="display-4">${{ number_format($avgValue, 2) }}</p>
                <div class="text-muted">
                    Per completed trip
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                Recent Users
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_users'] as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ Storage::url($user->profile_image) }}" class="rounded-circle me-2" width="40" height="40">
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($user->user_type === 'passenger')
                                            <span class="badge bg-primary">Passenger</span>
                                        @else
                                            <span class="badge bg-success">Driver</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">View All Users</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                Recent Trips
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>From → To</th>
                                <th>Passenger</th>
                                <th>Driver</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_trips'] as $trip)
                                <tr>
                                    <td>{{ $trip->origin_location }} → {{ $trip->destination_location }}</td>
                                    <td>{{ $trip->passenger->name }}</td>
                                    <td>{{ $trip->driver ? $trip->driver->name : 'Unassigned' }}</td>
                                    <td>
                                        @if($trip->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($trip->status === 'accepted')
                                            <span class="badge bg-primary">Accepted</span>
                                        @elseif($trip->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($trip->status === 'canceled')
                                            <span class="badge bg-danger">Canceled</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('admin.trips.index') }}" class="btn btn-sm btn-primary">View All Trips</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Quick Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-user-plus me-2"></i> Add New User
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.statistics') }}" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-chart-bar me-2"></i> View Statistics
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.trips.index', ['status' => 'pending']) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-clock me-2"></i> View Pending Trips
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-info w-100 mb-2">
                            <i class="fas fa-money-bill-wave me-2"></i> View Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection