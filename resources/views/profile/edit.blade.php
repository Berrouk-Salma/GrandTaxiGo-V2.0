@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        {{-- <div class="card mb-4">
            <div class="card-header">Profile Information</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <img src="{{ Storage::url($user->profile_image) }}" class="img-fluid rounded-circle mb-3" style="max-width: 150px; max-height: 150px;">
                            
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Update Profile Image</label>
                                <input type="file" class="form-control @error('profile_image') is-invalid @enderror" id="profile_image" name="profile_image">
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <p class="mb-0"><strong>Account Type:</strong> 
                                    @if($user->isPassenger())
                                        Passenger
                                    @else
                                        Driver
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div> --}}
        <!-- Add this to resources/views/profile/edit.blade.php -->

<div class="card mt-4">
    <div class="card-header">Reviews & Ratings</div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <h2>{{ number_format($user->averageRating(), 1) }}</h2>
                <div class="mb-2">
                    @stars($user->averageRating())
                </div>
                <p class="text-muted">{{ $user->reviewsReceived()->count() }} {{ Str::plural('review', $user->reviewsReceived()->count()) }}</p>
            </div>
            <div class="col-md-8">
                <div class="row">
                    @for ($i = 5; $i >= 1; $i--)
                        @php
                            $count = $user->reviewsReceived()->where('rating', $i)->count();
                            $total = $user->reviewsReceived()->count();
                            $percentage = $total > 0 ? ($count / $total) * 100 : 0;
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
        
        <div class="mt-3 text-center">
            <a href="{{ route('reviews.user', $user) }}" class="btn btn-primary">View All Reviews</a>
        </div>
    </div>
</div>

        <div class="card">
            <div class="card-header">Update Password</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection