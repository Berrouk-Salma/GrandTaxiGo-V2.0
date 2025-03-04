@extends('layouts.app')

@section('title', 'Add Availability')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Add Availability</div>
            <div class="card-body">
                <form method="POST" action="{{ route('availabilities.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="current_location" class="form-label">Location</label>
                        <input type="text" class="form-control @error('current_location') is-invalid @enderror" id="current_location" name="current_location" value="{{ old('current_location') }}" required>
                        @error('current_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Availability</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection