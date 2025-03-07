<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        $trip = Trip::where('status', 'completed')->inRandomOrder()->first();
        
        // If no completed trips exist, create one
        if (!$trip) {
            $passenger = User::where('user_type', 'passenger')->inRandomOrder()->first();
            $driver = User::where('user_type', 'driver')->inRandomOrder()->first();
            
            $trip = Trip::factory()->create([
                'passenger_id' => $passenger->id,
                'driver_id' => $driver->id,
                'status' => 'completed'
            ]);
        }
        
        // Random reviewer (50% chance of being passenger or driver)
        $isPassengerReview = $this->faker->boolean();
        
        $reviewerId = $isPassengerReview ? $trip->passenger_id : $trip->driver_id;
        $reviewedId = $isPassengerReview ? $trip->driver_id : $trip->passenger_id;
        
        return [
            'reviewer_id' => $reviewerId,
            'reviewed_id' => $reviewedId,
            'trip_id' => $trip->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->boolean(80) ? $this->faker->paragraph() : null,
        ];
    }
}