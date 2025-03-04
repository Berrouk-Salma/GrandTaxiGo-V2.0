<!-- resources/views/emails/trip-accepted.blade.php -->
@component('mail::message')
# Your Trip Has Been Accepted

Hello {{ $trip->passenger->name }},

Your trip from {{ $trip->origin_location }} to {{ $trip->destination_location }} has been accepted by a driver.

**Driver Details:**
- Name: {{ $trip->driver->name }}
- Phone: {{ $trip->driver->phone }}

**Trip Details:**
- Departure: {{ $trip->departure_time->format('F j, Y, g:i a') }}
- Status: Accepted

Please show the QR code below to your driver upon pickup:

<img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code" style="width: 250px;">

@component('mail::button', ['url' => route('trips.show', $trip)])
View Trip Details
@endcomponent

Thank you for using GrandTaxiGo!

Regards,<br>
{{ config('app.name') }}
@endcomponent