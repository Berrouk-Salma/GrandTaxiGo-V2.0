@extends('layouts.app')

@section('title', 'Payment History')

@section('content')
<div class="card">
    <div class="card-header">
        Payment History
    </div>
    <div class="card-body">
        @if($payments->isEmpty())
            <div class="alert alert-info">You haven't made any payments yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Trip</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('trips.show', $payment->trip) }}">
                                        {{ $payment->trip->origin_location }} → {{ $payment->trip->destination_location }}
                                    </a>
                                </td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    @if($payment->payment_method === 'stripe')
                                        <i class="fab fa-cc-stripe"></i> Stripe
                                    @else
                                        {{ ucfirst($payment->payment_method) }}
                                    @endif
                                </td>
                                <td>
                                    @if($payment->status === 'succeeded')
                                        <span class="badge bg-success">Succeeded</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection