<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class BookingDetails extends Component
{
    public Booking $booking;

    public function mount(Booking $booking)
    {
        $this->booking = $booking->load(['customer', 'workspace', 'plan', 'finances']);
    }

    public function render()
    {
        return view('livewire.bookings.booking-details');
    }
}
