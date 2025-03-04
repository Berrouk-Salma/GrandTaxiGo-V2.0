<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DriverAvailabilityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
// use App\Http\Controllers\TripController;



// Review routes
Route::middleware('auth')->group(function () {
    Route::get('/trips/{trip}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::get('/users/{user}/reviews', [ReviewController::class, 'userReviews'])->name('reviews.user');
});

Route::middleware('auth')->group(function () {
    Route::get('/trips/{trip}/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/trips/{trip}/process-payment', [PaymentController::class, 'process'])->name('payments.process');
    Route::get('/payments/{payment}/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
});
// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // User management
    Route::resource('users', Admin\UserController::class);
    
    // Trip management
    Route::resource('trips', Admin\TripController::class);
    
    // Payment management
    Route::resource('payments', Admin\PaymentController::class);
    
    // Statistics
    Route::get('/statistics', [Admin\DashboardController::class, 'statistics'])->name('statistics');
});
// Social authentication routes
Route::get('auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('social.login');
Route::get('auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback']);

Route::post('/trips', [TripController::class, 'store'])->name('trips.store')->middleware('validate.trip');
// home
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
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
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // Trip routes
    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    Route::get('/trips/create', [TripController::class, 'create'])->name('trips.create');
    Route::post('/trips', [TripController::class, 'store'])->name('trips.store');
    Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
    Route::put('/trips/{trip}/cancel', [TripController::class, 'cancel'])->name('trips.cancel');
    Route::put('/trips/{trip}/accept', [TripController::class, 'accept'])->name('trips.accept');
    Route::put('/trips/{trip}/complete', [TripController::class, 'complete'])->name('trips.complete');
    Route::get('/find-drivers', [TripController::class, 'findDrivers'])->name('trips.find-drivers');
    
    // Driver availability routes (driver only)
    Route::middleware('driver')->group(function () {
        Route::resource('availabilities', DriverAvailabilityController::class);
    });
});

// Command to automatically cancel trips past departure time
Route::get('/auto-cancel-trips', function () {
    // This route could be called by a scheduler
    $trips = \App\Models\Trip::needingAutoCancellation()->get();
    foreach ($trips as $trip) {
        $trip->update(['status' => 'canceled']);
    }
    return 'Trips auto-canceled: ' . $trips->count();
})->middleware('auth:sanctum');