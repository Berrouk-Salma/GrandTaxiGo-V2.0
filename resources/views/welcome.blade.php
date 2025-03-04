
@extends('layouts.app')

@section('title', 'Welcome to GrandTaxiGo')

@section('content')
<div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold">GrandTaxiGo</h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">The easiest way to book intercity taxi rides. Connect with drivers and enjoy hassle-free travel.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 gap-3">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">Register</a>
            @else
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4 gap-3">Dashboard</a>
            @endguest
        </div>
    </div>
</div>

<div class="container">
    <div class="row g-4 py-5">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-car-front display-1"></i>
                    </div>
                    <h3 class="card-title">Easy Booking</h3>
                    <p class="card-text">Book your intercity taxi ride with just a few clicks. Specify your destination and preferred departure time.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-people display-1"></i>
                    </div>
                    <h3 class="card-title">Verified Drivers</h3>
                    <p class="card-text">All our drivers are verified professionals committed to providing safe and reliable transportation.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-geo-alt display-1"></i>
                    </div>
                    <h3 class="card-title">Intercity Travel</h3>
                    <p class="card-text">Specialize in intercity transport, making it easy to travel between cities without hassle.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection