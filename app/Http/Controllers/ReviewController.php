<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show form to create a new review
    public function create(Trip $trip)
    {
        // Ensure user is part of this trip
        if (auth()->id() != $trip->passenger_id && auth()->id() != $trip->driver_id) {
            return redirect()->route('trips.index')->with('error', 'You can only review your own trips.');
        }

        // Ensure trip is completed
        if ($trip->status !== 'completed') {
            return redirect()->route('trips.index')->with('error', 'You can only review completed trips.');
        }

        // Determine who is being reviewed
        $reviewedUser = auth()->id() == $trip->passenger_id ? $trip->driver : $trip->passenger;
        
        // Check if review already exists
        $existingReview = Review::where('reviewer_id', auth()->id())
            ->where('trip_id', $trip->id)
            ->first();
        
        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview)->with('info', 'You have already reviewed this trip. You can edit your review.');
        }

        return view('reviews.create', compact('trip', 'reviewedUser'));
    }

    // Store a new review
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'reviewed_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $trip = Trip::findOrFail($validated['trip_id']);

        // Verify user is allowed to review this trip
        if (auth()->id() != $trip->passenger_id && auth()->id() != $trip->driver_id) {
            return redirect()->route('trips.index')->with('error', 'You can only review your own trips.');
        }

        // Create the review
        $review = Review::create([
            'reviewer_id' => auth()->id(),
            'reviewed_id' => $validated['reviewed_id'],
            'trip_id' => $validated['trip_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('trips.show', $trip)->with('success', 'Your review has been submitted.');
    }

    // Show review edit form
    public function edit(Review $review)
    {
        // Ensure user owns this review
        if (auth()->id() != $review->reviewer_id) {
            return redirect()->route('trips.index')->with('error', 'You can only edit your own reviews.');
        }

        $trip = $review->trip;
        $reviewedUser = $review->reviewed;

        return view('reviews.edit', compact('review', 'trip', 'reviewedUser'));
    }

    // Update an existing review
    public function update(Request $request, Review $review)
    {
        // Ensure user owns this review
        if (auth()->id() != $review->reviewer_id) {
            return redirect()->route('trips.index')->with('error', 'You can only edit your own reviews.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $review->update($validated);

        return redirect()->route('trips.show', $review->trip)->with('success', 'Your review has been updated.');
    }

    // Show all reviews for a user
    public function userReviews(User $user)
    {
        $reviews = $user->reviewsReceived()->with(['reviewer', 'trip'])->latest()->paginate(10);
        return view('reviews.user', compact('user', 'reviews'));
    }
}