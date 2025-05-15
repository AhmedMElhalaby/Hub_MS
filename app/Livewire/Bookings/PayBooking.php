<?php

namespace App\Livewire\Bookings;

use Livewire\Component;
use App\Enums\PaymentMethod;
use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class PayBooking extends Component
{
    use WithModal, NotificationService;

    public $bookingId;
    public $booking;
    public $amount;
    public $payment_method = PaymentMethod::Cash->value;

    protected BookingRepository $bookingRepository;

    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function pay()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0|max:' . $this->booking->balance,
            'payment_method' => 'required|in:' . enum_rules(PaymentMethod::class),
        ]);

        try {
            $this->bookingRepository->addPayment(
                $this->booking->id,
                $this->amount,
                $this->payment_method
            );
            $this->reset();
            $this->closeModal();
            $this->dispatch('booking-payed');
            $this->notifySuccess(__('crud.bookings.messages.paid'));
        } catch (\Exception $e) {
            $this->notifyError($e->getMessage());
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.bookings.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.bookings.pay-booking', [
            'booking' => $this->booking
        ]);
    }

    #[On('open-pay-booking')]
    public function open($bookingId)
    {
        $this->bookingId = $bookingId;
        $this->booking = $this->bookingRepository->findById($this->bookingId);
        $this->amount = $this->booking->balance;
        $this->openModal();
    }
}
