<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DriverAvailabilityController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
  
    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    Route::get('/trips/create', [TripController::class, 'create'])->name('trips.create');
    Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
    Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
    Route::put('/trips/{trip}/cancel', [TripController::class, 'cancel'])->name('trips.cancel');
    Route::put('/trips/{trip}/accept', [TripController::class, 'accept'])->name('trips.accept');
    Route::put('/trips/{trip}/complete', [TripController::class, 'complete'])->name('trips.complete');
    Route::get('/find-drivers', [TripController::class, 'findDrivers'])->name('trips.find-drivers');
    

    Route::middleware('driver')->group(function () {
        Route::resource('availabilities', DriverAvailabilityController::class);
    });
});

Route::get('/auto-cancel-trips', function () {
    $trips = \App\Models\Trip::needingAutoCancellation()->get();
    foreach ($trips as $trip) {
        $trip->update(['status' => 'canceled']);
    }
    return 'Trips auto-canceled: ' . $trips->count();
})->middleware('auth:sanctum');