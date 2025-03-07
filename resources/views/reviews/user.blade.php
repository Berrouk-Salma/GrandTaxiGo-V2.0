@extends('layouts.app')

@section('title', 'Reviews for ' . $user->name)

@section('content')
<div class="mb-4">
    <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <img src="{{ Storage::url($user->profile_image) }}" class="rounded-circle me-3" width="60" height="60">
            <div>
                <h3 class="mb-0">{{ $user->name }}</h3>
                <p class="text-muted mb-0">{{ ucfirst($user->user_type) }}</p>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center">
                <h2>{{ number_format($user->averageRating(), 1) }}</h2>
                <div class="mb-2">
                    @stars($user->averageRating())
                </div>
                <p class="text-muted">{{ $reviews->total() }} {{ Str::plural('review', $reviews->total()) }}</p>
            </div>
            <div class="col-md-8">
                <div class="row">
                    @for ($i = 5; $i >= 1; $i--)
                        @php
                            $count = $user->reviewsReceived()->where('rating', $i)->count();
                            $percentage = $reviews->total() > 0 ? ($count / $reviews->total()) * 100 : 0;
                        @endphp
                        <div class="col-12 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="me-2">{{ $i }} <i class="fas fa-star text-warning"></i></div>
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="ms-2">{{ $count }}</div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

@if($reviews->isEmpty())
    <div class="alert alert-info">No reviews found for this user.</div>
@else
    <div class="card">
        <div class="card-header">
            All Reviews
        </div>
        <div class="card-body">
            @foreach($reviews as $review)
                <div class="review mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex">
                        <img src="{{ Storage::url($review->reviewer->profile_image) }}" class="rounded-circle me-3" width="50" height="50">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-0">{{ $review->reviewer->name }}</h5>
                                    <div class="text-muted mb-2">{{ $review->created_at->format('F j, Y') }}</div>
                                </div>
                                <div>
                                    @stars($review->rating)
                                </div>
                            </div>
                            
                            @if($review->comment)
                                <p class="mb-0">{{ $review->comment }}</p>
                            @endif
                            
                            <div class="mt-2">
                                <small class="text-muted">
                                    Trip: {{ $review->trip->origin_location }} to {{ $review->trip->destination_location }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
@endif
@endsection