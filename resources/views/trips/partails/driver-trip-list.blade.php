@if($trips->isEmpty())
    <div class="alert alert-info">No trips found in this category.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Passenger</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Departure</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trips as $trip)
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
                            @if($trip->status === 'accepted')
                                <span class="badge bg-primary">Active</span>
                            @elseif($trip->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('trips.show', $trip) }}" class="btn btn-sm btn-info">Details</a>
                            
                            @if($trip->status === 'accepted')
                                <form action="{{ route('trips.complete', $trip) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to mark this trip as completed?')">Complete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif