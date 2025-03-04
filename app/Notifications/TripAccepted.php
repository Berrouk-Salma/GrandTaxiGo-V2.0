<?php

namespace App\Notifications;

use App\Models\Trip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TripAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $trip;

    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Generate QR code with trip details
        $tripDetails = json_encode([
            'id' => $this->trip->id,
            'passenger' => $this->trip->passenger->name,
            'driver' => $this->trip->driver->name,
            'origin' => $this->trip->origin_location,
            'destination' => $this->trip->destination_location,
            'departure' => $this->trip->departure_time->format('Y-m-d H:i'),
        ]);

        $qrCode = QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->generate($tripDetails);

        $qrCodeBase64 = base64_encode($qrCode);

        return (new MailMessage)
            ->subject('Your Trip Has Been Accepted')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your trip from ' . $this->trip->origin_location . ' to ' . $this->trip->destination_location . ' has been accepted by a driver.')
            ->line('Driver Name: ' . $this->trip->driver->name)
            ->line('Departure Time: ' . $this->trip->departure_time->format('F j, Y, g:i a'))
            ->line('Please show the QR code below to your driver upon pickup:')
            ->action('View Trip Details', url(route('trips.show', $this->trip)))
            ->line('Thank you for using GrandTaxiGo!')
            ->embedData($qrCodeBase64, 'QrCode.png', ['mime' => 'image/png'])
            ->view('emails.trip-accepted', [
                'trip' => $this->trip,
                'qrCodeBase64' => $qrCodeBase64
            ]);
    }
}