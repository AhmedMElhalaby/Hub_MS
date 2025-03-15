<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
{
    use Queueable;

    public function __construct(protected Booking $booking)
    {
    }

    public function via($notifiable): array
    {
        $channels = ['database'];

        if (Setting::get('sms_enabled', false)) {
            $channels[] = 'custom-sms';
        }

        return $channels;
    }

    public function toSms($notifiable)
    {
        $message = "Your booking #{$this->booking->id} has been confirmed. ";
        $message .= "Start: {$this->booking->started_at->format('M d, Y H:i')}";

        return [
            'to' => $notifiable->phone,
            'message' => $message
        ];
    }
}
