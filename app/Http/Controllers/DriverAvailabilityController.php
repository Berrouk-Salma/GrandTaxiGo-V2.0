<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverAvailabilityRequest;
use App\Models\DriverAvailability;
use Illuminate\Http\Request;

class DriverAvailabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('driver'); // Custom middleware to check if user is a driver
    }

    public function index()
    {
        $availabilities = auth()->user()->availabilities()->latest()->get();
        return view('availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        return view('availabilities.create');
    }

    public function store(DriverAvailabilityRequest $request)
    {
        auth()->user()->availabilities()->create($request->validated());
        return redirect()->route('availabilities.index')->with('success', 'Availability added successfully!');
    }

    public function edit(DriverAvailability $availability)
    {
        $this->authorize('update', $availability);
        return view('availabilities.edit', compact('availability'));
    }

    public function update(DriverAvailabilityRequest $request, DriverAvailability $availability)
    {
        $this->authorize('update', $availability);
        $availability->update($request->validated());
        return redirect()->route('availabilities.index')->with('success', 'Availability updated successfully!');
    }

    public function destroy(DriverAvailability $availability)
    {
        $this->authorize('delete', $availability);
        $availability->delete();
        return redirect()->route('availabilities.index')->with('success', 'Availability removed successfully!');
    }
}