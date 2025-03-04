<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition()
    {
        $passenger = User::where('user_type', 'passenger')->inRandomOrder()->first();
        $driver = User::where('user_type', 'driver')->inRandomOrder()->first();
        $status = $this->faker->randomElement(['pending', 'accepted', 'completed', 'canceled']);
        
        // If status is pending, driver might be null
        if ($status === 'pending' && $this->faker->boolean(30)) {
            $driver = null;
        }
        
        return [
            'passenger_id' => $passenger->id,
            'driver_id' => $driver ? $driver->id : null,
            'origin_location' => $this->faker->city(),
            'destination_location' => $this->faker->city(),
            'departure_time' => $this->faker->dateTimeBetween('now', '+2 weeks'),
            'status' => $status,
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'driver_id' => null,
            ];
        });
    }
}