<?php

namespace App\Livewire\Bookings;

use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class CancelBooking extends Component
{
    use WithModal, NotificationService;

    public $bookingId;

    protected BookingRepository $bookingRepository;

    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function cancel()
    {
        try {
            $this->bookingRepository->cancel($this->bookingId);
            $this->closeModal();
            $this->dispatch('booking-canceled');
            $this->notifySuccess(__('crud.bookings.messages.canceled'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.bookings.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.bookings.cancel-booking');
    }

    #[On('open-cancel-booking')]
    public function open($bookingId)
    {
        $this->bookingId = $bookingId;
        $this->openModal();
    }
}
