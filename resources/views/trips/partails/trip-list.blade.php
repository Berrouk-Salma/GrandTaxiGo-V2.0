@if($trips->isEmpty())
    <div class="alert alert-info">No trips found in this category.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Departure</th>
                    <th>Status</th>
                    <th>Driver</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trips as $trip)
                    <tr>
                        <td>{{ $trip->origin_location }}</td>
                        <td>{{ $trip->destination_location }}</td>
                        <td>{{ $trip->departure_time->format('M d, Y H:i') }}</td>
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
                        <td>
                            @if($trip->driver)
                                <div class="d-flex align-items-center">
                                    <img src="{{ Storage::url($trip->driver->profile_image) }}" class="rounded-circle me-2" width="30" height="30">
                                    {{ $trip->driver->name }}
                                </div>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('trips.show', $trip) }}" class="btn btn-sm btn-info">Details</a>
                            
                            @if($trip->status === 'pending' && now()->diffInMinutes($trip->departure_time) >= 60)
                                <form action="{{ route('trips.cancel', $trip) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this trip?')">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif