<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Exception\CardException;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function checkout(Trip $trip)
    {
        // Ensure trip is valid for payment
        if ($trip->status !== 'completed' || $trip->isPaid()) {
            return back()->with('error', 'This trip is not eligible for payment.');
        }

        // Calculate amount (could be based on distance, time, etc.)
        $amount = 50.00; // Example fixed amount; in a real app this would be calculated

        return view('payments.checkout', compact('trip', 'amount'));
    }

    public function process(Request $request, Trip $trip)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            // Create payment intent with Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Stripe expects amounts in cents
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'description' => "Payment for trip #{$trip->id}",
                'metadata' => [
                    'trip_id' => $trip->id,
                    'passenger_id' => $trip->passenger_id,
                    'driver_id' => $trip->driver_id,
                ],
            ]);

            // Create payment record in database
            $payment = Payment::create([
                'trip_id' => $trip->id,
                'user_id' => auth()->id(),
                'amount' => $request->amount,
                'payment_method' => 'stripe',
                'transaction_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'payment_details' => [
                    'payment_intent_id' => $paymentIntent->id,
                    'payment_method_id' => $request->payment_method_id,
                    'payment_method_types' => $paymentIntent->payment_method_types,
                ],
            ]);

            // Update trip status if needed
            // $trip->update(['payment_status' => 'paid']);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'payment' => $payment,
            ]);

        } catch (CardException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function success(Payment $payment)
    {
        // Show payment success page
        return view('payments.success', compact('payment'));
    }

    public function history()
    {
        $payments = auth()->user()->payments()->with('trip')->latest()->get();
        return view('payments.history', compact('payments'));
    }
}