@extends('layouts.app')

@section('title', 'Pay for Trip')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Payment</div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>Trip Details</h5>
                    <p>From: {{ $trip->origin_location }}</p>
                    <p>To: {{ $trip->destination_location }}</p>
                    <p>Date: {{ $trip->departure_time->format('F j, Y, g:i a') }}</p>
                    <p>Driver: {{ $trip->driver->name }}</p>
                    
                    <div class="alert alert-info">
                        <strong>Amount to pay:</strong> ${{ number_format($amount, 2) }}
                    </div>
                </div>

                <div id="payment-form">
                    <div class="mb-3">
                        <label for="card-element" class="form-label">Credit or Debit Card</label>
                        <div id="card-element" class="form-control">
                            <!-- Stripe Card Element will be inserted here -->
                        </div>
                        <div id="card-errors" class="invalid-feedback d-block" role="alert"></div>
                    </div>

                    <div class="d-grid">
                        <button id="submit-button" class="btn btn-primary">Pay Now</button>
                    </div>
                </div>

                <div id="loading" class="text-center my-4" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Processing your payment...</p>
                </div>

                <div id="payment-success" class="text-center my-4" style="display: none;">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4>Payment Successful!</h4>
                    <p>Your payment has been processed successfully.</p>
                    <a href="{{ route('trips.index') }}" class="btn btn-primary mt-2">Return to Trips</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        
        // Create card element
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');
        
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const cardErrors = document.getElementById('card-errors');
        const loading = document.getElementById('loading');
        const paymentSuccess = document.getElementById('payment-success');
        
        // Handle form submission
        submitButton.addEventListener('click', async function(event) {
            event.preventDefault();
            
            // Disable button to prevent multiple clicks
            submitButton.disabled = true;
            form.style.display = 'none';
            loading.style.display = 'block';
            
            // Create payment method
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });
            
            if (error) {
                // Show error and re-enable button
                cardErrors.textContent = error.message;
                submitButton.disabled = false;
                form.style.display = 'block';
                loading.style.display = 'none';
                return;
            }
            
            // Submit payment to server
            fetch('{{ route('payments.process', $trip) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id,
                    amount: {{ $amount }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Payment successful
                    loading.style.display = 'none';
                    paymentSuccess.style.display = 'block';
                } else {
                    // Payment failed
                    cardErrors.textContent = data.message;
                    submitButton.disabled = false;
                    form.style.display = 'block';
                    loading.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                cardErrors.textContent = 'An error occurred. Please try again.';
                submitButton.disabled = false;
                form.style.display = 'block';
                loading.style.display = 'none';
            });
        });
    });
</script>
@endpush
@endsection