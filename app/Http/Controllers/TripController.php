<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripRequest;
use App\Models\Trip;
use App\Models\User;
use App\Models\DriverAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\DriverAvailabilityService;
use App\Notifications\TripAccepted;

class TripController extends Controller
{
    protected $availabilityService;

    public function __construct(DriverAvailabilityService $availabilityService)
    {
        $this->middleware('auth');
        $this->availabilityService = $availabilityService;
    }

    // Show trips for authenticated user
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isPassenger()) {
            $trips = $user->tripsAsPassenger()->with('driver')->latest()->get();
            return view('trips.passenger-index', compact('trips'));
        } else {
            $trips = $user->tripsAsDriver()->with('passenger')->latest()->get();
            // Also fetch pending trips that match driver's availability
            $pendingTrips = $this->getAvailablePendingTrips($user);
            return view('trips.driver-index', compact('trips', 'pendingTrips'));
        }
    }

    // Show trip creation form (for passengers only)
    public function create()
    {
        $this->authorize('create', Trip::class);
        return view('trips.create');
    }

    // Store new trip (for passengers only)
    public function store(TripRequest $request)
    {
        $this->authorize('create', Trip::class);
        
        $trip = auth()->user()->tripsAsPassenger()->create($request->validated());
        
        return redirect()->route('trips.index')->with('success', 'Trip booked successfully!');
    }

    // Show trip details
    public function show(Trip $trip)
    {
        $this->authorize('view', $trip);
        return view('trips.show', compact('trip'));
    }

    // Cancel trip (for passengers, with time restriction)
    public function cancel(Trip $trip)
    {
        $this->authorize('cancel', $trip);
        
        // Check if departure time is at least 1 hour away
        if (now()->diffInMinutes($trip->departure_time) < 60) {
            return back()->with('error', 'Trips can only be canceled at least 1 hour before departure.');
        }
        
        $trip->update(['status' => 'canceled']);
        return redirect()->route('trips.index')->with('success', 'Trip canceled successfully!');
    }

    // Accept a trip (for drivers only)
    public function accept(Trip $trip)
    {
        $this->authorize('accept', $trip);
        
        $trip->update([
            'driver_id' => auth()->id(),
            'status' => 'accepted'
        ]);
        
        // Update driver availabilities
        $this->availabilityService->updateDriverAvailability(auth()->user());
        
        // Send email notification with QR code to passenger
        $trip->passenger->notify(new TripAccepted($trip));
        
        return redirect()->route('trips.index')->with('success', 'Trip accepted successfully!');
    }

    // Complete a trip (for drivers only)
    public function complete(Trip $trip)
    {
        $this->authorize('complete', $trip);
        
        $trip->update(['status' => 'completed']);
        
        // Create new availability after trip
        $this->availabilityService->createAvailabilityAfterTrip($trip);
        
        return redirect()->route('trips.index')->with('success', 'Trip marked as completed!');
    }

    // Find available drivers (for passengers)
    public function findDrivers(Request $request)
    {
        $location = $request->location;
        
        // Cache the results for 5 minutes to optimize performance
        $drivers = Cache::remember('drivers_in_'.$location, 5 * 60, function () use ($location) {
            return User::where('user_type', 'driver')
                ->whereHas('availabilities', function ($query) use ($location) {
                    $query->active()->inLocation($location);
                })
                ->select('id', 'name', 'profile_image')
                ->get();
        });
        
        return view('trips.available-drivers', compact('drivers', 'location'));
    }

    // Helper method to get pending trips that match driver's availability
    private function getAvailablePendingTrips($driver)
    {
        $availabilities = $driver->availabilities()->active()->get();
        $pendingTrips = collect();
        
        if ($availabilities->count() > 0) {
            $locations = $availabilities->pluck('current_location')->toArray();
            
            $pendingTrips = Trip::pending()
                ->whereNull('driver_id')
                ->whereIn('origin_location', $locations)
                ->where('departure_time', '>', now())
                ->with('passenger')
                ->get();
        }
        
        return $pendingTrips;
    }
}