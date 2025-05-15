<?php

namespace App\Livewire\Bookings;

use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class ConfirmBooking extends Component
{
    use WithModal, NotificationService;

    public $bookingId;

    protected BookingRepository $bookingRepository;

    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function confirm()
    {
        try {
            $this->bookingRepository->confirm($this->bookingId);
            $this->closeModal();
            $this->dispatch('booking-confirmed');
            $this->notifySuccess(__('crud.bookings.messages.confirmed'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.bookings.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.bookings.confirm-booking');
    }

    #[On('open-confirm-booking')]
    public function open($bookingId)
    {
        $this->bookingId = $bookingId;
        $this->openModal();
    }
}
