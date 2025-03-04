<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateTripReservation
{
    public function handle(Request $request, Closure $next)
    {
        // Validate departure time is in the future
        if ($request->filled('departure_time') && strtotime($request->departure_time) <= time()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['departure_time' => 'Departure time must be in the future.']);
        }
        
        // Validate origin and destination are not the same
        if ($request->filled('origin_location') && $request->filled('destination_location') && 
            $request->origin_location === $request->destination_location) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['destination_location' => 'Origin and destination cannot be the same.']);
        }
        
        return $next($request);
    }
}