@extends('layouts.app')

@section('title', 'Book a Trip')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Book a Trip</div>
            <div class="card-body">
                <form method="POST" action="{{ route('trips.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="origin_location" class="form-label">Pickup Location</label>
                        <input type="text" class="form-control @error('origin_location') is-invalid @enderror" id="origin_location" name="origin_location" value="{{ old('origin_location') }}" required>
                        @error('origin_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="destination_location" class="form-label">Destination</label>
                        <input type="text" class="form-control @error('destination_location') is-invalid @enderror" id="destination_location" name="destination_location" value="{{ old('destination_location') }}" required>
                        @error('destination_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="departure_time" class="form-label">Departure Time</label>
                        <input type="datetime-local" class="form-control @error('departure_time') is-invalid @enderror" id="departure_time" name="departure_time" value="{{ old('departure_time') }}" required>
                        @error('departure_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Book Trip</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection