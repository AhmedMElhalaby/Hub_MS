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
            'title' => __('crud.bookings.messages.completed'),
            'message' => __('crud.bookings.messages.booking_completed', ['customer' => $this->booking->customer->name]),
            'type' => 'info',
            'link' => route('tenant.bookings.show', ['tenant'=>$this->booking->tenant->domain,'booking' => $this->booking->id])
        ];
    }
}
