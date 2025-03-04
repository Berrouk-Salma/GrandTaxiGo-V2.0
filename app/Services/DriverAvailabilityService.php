<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\User;
use App\Models\DriverAvailability;
use Carbon\Carbon;

class DriverAvailabilityService
{
    /**
     * Update driver availabilities based on current and future trips
     */
    public function updateDriverAvailability(User $driver)
    {
        // Get active and upcoming trips for this driver
        $activeTrips = Trip::where('driver_id', $driver->id)
            ->where('status', 'accepted')
            ->where('departure_time', '>=', now())
            ->get();

        if ($activeTrips->isEmpty()) {
            return;
        }

        // Get current driver availabilities
        $availabilities = $driver->availabilities()
            ->where('end_time', '>=', now())
            ->get();

        foreach ($activeTrips as $trip) {
            // Estimated trip duration (e.g., 2 hours)
            $tripStart = $trip->departure_time;
            $tripEnd = Carbon::parse($trip->departure_time)->addHours(2); // Estimated trip time

            // Check for overlapping availabilities
            foreach ($availabilities as $availability) {
                // If trip starts during this availability period
                if ($tripStart->between($availability->start_time, $availability->end_time) || 
                    $tripEnd->between($availability->start_time, $availability->end_time)) {
                    
                    // If trip covers the entire availability period
                    if ($tripStart <= $availability->start_time && $tripEnd >= $availability->end_time) {
                        // Set this availability to unavailable
                        $availability->update(['status' => 'unavailable']);
                    }
                    // If trip starts during availability but ends after it
                    else if ($tripStart->between($availability->start_time, $availability->end_time) && 
                             $tripEnd > $availability->end_time) {
                        // Adjust availability end time
                        $availability->update([
                            'end_time' => $tripStart->subMinutes(1)
                        ]);
                    }
                    // If trip ends during availability but starts before it
                    else if ($tripEnd->between($availability->start_time, $availability->end_time) && 
                             $tripStart < $availability->start_time) {
                        // Adjust availability start time
                        $availability->update([
                            'start_time' => $tripEnd->addMinutes(1)
                        ]);
                    }
                    // If trip is fully within availability
                  <?php

namespace App\Services;

use App\Models\Trip;
use App\Models\User;
use App\Models\DriverAvailability;
use Carbon\Carbon;

class DriverAvailabilityService
{
    /**
     * Update driver availabilities based on current and future trips
     */
    public function updateDriverAvailability(User $driver)
    {
        // Get active and upcoming trips for this driver
        $activeTrips = Trip::where('driver_id', $driver->id)
            ->where('status', 'accepted')
            ->where('departure_time', '>=', now())
            ->get();

        if ($activeTrips->isEmpty()) {
            return;
        }

        // Get current driver availabilities
        $availabilities = $driver->availabilities()
            ->where('end_time', '>=', now())
            ->get();

        foreach ($activeTrips as $trip) {
            // Estimated trip duration (e.g., 2 hours)
            $tripStart = $trip->departure_time;
            $tripEnd = Carbon::parse($trip->departure_time)->addHours(2); // Estimated trip time

            // Check for overlapping availabilities
            foreach ($availabilities as $availability) {
                // If trip starts during this availability period
                if ($tripStart->between($availability->start_time, $availability->end_time) || 
                    $tripEnd->between($availability->start_time, $availability->end_time)) {
                    
                    // If trip covers the entire availability period
                    if ($tripStart <= $availability->start_time && $tripEnd >= $availability->end_time) {
                        // Set this availability to unavailable
                        $availability->update(['status' => 'unavailable']);
                    }
                    // If trip starts during availability but ends after it
                    else if ($tripStart->between($availability->start_time, $availability->end_time) && 
                             $tripEnd > $availability->end_time) {
                        // Adjust availability end time
                        $availability->update([
                            'end_time' => $tripStart->subMinutes(1)
                        ]);
                    }
                    // If trip ends during availability but starts before it
                    else if ($tripEnd->between($availability->start_time, $availability->end_time) && 
                             $tripStart < $availability->start_time) {
                        // Adjust availability start time
                        $availability->update([
                            'start_time' => $tripEnd->addMinutes(1)
                        ]);
                    }
                    // If trip is fully within availability
                  // Continuing from app/Services/DriverAvailabilityService.php
                    // If trip is fully within availability
                    else {
                        // Split the availability into two
                        DriverAvailability::create([
                            'driver_id' => $driver->id,
                            'start_time' => $tripEnd->addMinutes(1),
                            'end_time' => $availability->end_time,
                            'current_location' => $trip->destination_location,
                            'status' => 'available'
                        ]);
                        
                        // Update original availability to end before trip
                        $availability->update([
                            'end_time' => $tripStart->subMinutes(1)
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Create new availability after trip completion
     */
    public function createAvailabilityAfterTrip(Trip $trip)
    {
        if (!$trip->driver_id || $trip->status !== 'completed') {
            return;
        }

        // Estimated end time (e.g., 2 hours after departure)
        $tripEndTime = Carbon::parse($trip->departure_time)->addHours(2);
        
        // Only create new availability if trip ends in the future
        if ($tripEndTime->isPast()) {
            return;
        }

        // Create new availability starting after trip completion
        DriverAvailability::create([
            'driver_id' => $trip->driver_id,
            'start_time' => $tripEndTime,
            'end_time' => $tripEndTime->copy()->addHours(4), // Available for 4 hours after trip
            'current_location' => $trip->destination_location,
            'status' => 'available'
        ]);
    }
}