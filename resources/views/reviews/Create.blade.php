@extends('layouts.app')

@section('title', 'Write a Review')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Write a Review</div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>Trip Details</h5>
                    <p>From: {{ $trip->origin_location }}</p>
                    <p>To: {{ $trip->destination_location }}</p>
                    <p>Date: {{ $trip->departure_time->format('F j, Y, g:i a') }}</p>
                    
                    <h5 class="mt-3">Reviewing</h5>
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ Storage::url($reviewedUser->profile_image) }}" class="rounded-circle me-3" width="60" height="60">
                        <div>
                            <h5 class="mb-0">{{ $reviewedUser->name }}</h5>
                            <p class="text-muted mb-0">{{ ucfirst($reviewedUser->user_type) }}</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('reviews.store') }}">
                    @csrf
                    <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                    <input type="hidden" name="reviewed_id" value="{{ $reviewedUser->id }}">

                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating">
                            <div class="btn-group" role="group">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" class="btn-check" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-warning" for="rating{{ $i }}">
                                        {{ $i }} <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment (Optional)</label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="4">{{ old('comment') }}</textarea>
                        @error('comment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .rating .btn-outline-warning {
        color: #ffc107;
        border-color: #ffc107;
    }
    .rating .btn-check:checked + .btn-outline-warning {
        color: #fff;
        background-color: #ffc107;
        border-color: #ffc107;
    }
</style>
@endsection