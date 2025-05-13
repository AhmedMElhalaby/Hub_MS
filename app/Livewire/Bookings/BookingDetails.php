<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class BookingDetails extends Component
{
    use NotificationService;

    public $booking;
    public $selectedBooking;

    protected BookingRepository $bookingRepository;

    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function mount(Booking $booking)
    {
        try {
            $this->booking = $this->bookingRepository->findWithRelations($booking->id);
            $this->selectedBooking = $this->booking;
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.not_found');
            return $this->redirect(route('tenant.bookings.index'));
        }
    }
    public function render()
    {
        return view('livewire.bookings.booking-details');
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }
}
