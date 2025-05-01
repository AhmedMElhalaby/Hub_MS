<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingCompleted extends Notification
{
    use Queueable;

    public function __construct(
        protected Booking $booking
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Booking Completed',
            'message' => "Booking for {$this->booking->customer->name} has been completed.",
            'type' => 'info',
            'link' => route('bookings.show', ['tenant'=>$this->booking->tenant->domain,'booking' => $this->booking->id])
        ];
    }
}
