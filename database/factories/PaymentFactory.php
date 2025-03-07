<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $trip = Trip::where('status', 'completed')->inRandomOrder()->first();
        
        // If no completed trips exist, create one
        if (!$trip) {
            $trip = Trip::factory()->create([
                'status' => 'completed'
            ]);
        }
        
        $amount = $this->faker->randomFloat(2, 20, 150);
        $status = $this->faker->randomElement(['succeeded', 'pending', 'failed']);
        
        return [
            'trip_id' => $trip->id,
            'user_id' => $trip->passenger_id,
            'amount' => $amount,
            'payment_method' => 'stripe',
            'transaction_id' => 'pi_' . Str::random(24),
            'status' => $status,
            'payment_details' => [
                'payment_intent_id' => 'pi_' . Str::random(24),
                'payment_method_id' => 'pm_' . Str::random(24),
                'payment_method_types' => ['card'],
            ],
        ];
    }

    public function succeeded()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'succeeded',
            ];
        });
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    public function failed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'failed',
            ];
        });
    }
}