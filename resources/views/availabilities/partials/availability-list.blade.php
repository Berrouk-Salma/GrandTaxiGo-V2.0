@if($availabilities->isEmpty())
    <div class="alert alert-info">No availabilities found in this category.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Location</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($availabilities as $availability)
                    <tr>
                        <td>{{ $availability->current_location }}</td>
                        <td>{{ $availability->start_time->format('M d, Y H:i') }}</td>
                        <td>{{ $availability->end_time->format('M d, Y H:i') }}</td>
                        <td>
                            @if($availability->status === 'available')
                                <span class="badge bg-success">Available</span>
                            @else
                                <span class="badge bg-secondary">Unavailable</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('availabilities.edit', $availability) }}" class="btn btn-sm btn-primary">Edit</a>
                            
                            <form action="{{ route('availabilities.destroy', $availability) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this availability?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif