<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use Livewire\Attributes\On;
use Livewire\Component;

class SendCredentials extends Component
{
    use WithModal, NotificationService;
    public $bookingId;
    public Booking $booking;
    public ?string $messageText = null;
    protected BookingRepository $bookingRepository;

    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function mount(){
        $this->messageText = __(
            "Internet access:\nU: :username\nP: :password"
        );
    }

    public function send()
    {
        $this->validate([
            'messageText' => ['required', 'string', 'min:10'],
        ]);

        try {
            $message = str_replace(
                [':username', ':password'],
                [$this->booking->hotspot_username, $this->booking->hotspot_password],
                $this->messageText
            );

            $this->booking->customer->sendMessage($message);

            $this->booking->update([
                'credentials_is_sent' => true,
            ]);

            $this->booking->logEvent('Credentials Sent', [
                'message'=> $message,
                'sent_by' => auth()->user()->name,
                'sent_at' => now()->format('Y-m-d H:i:s')
            ]);

            $this->closeModal();
            $this->reset(['messageText', 'bookingId']);
            $this->dispatch('credentials-sent');
            $this->notifySuccess(__('crud.common.messages.sent'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.bookings.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.bookings.send-credentials');
    }

    #[On('open-send-credentials')]
    public function open($bookingId)
    {
        $this->bookingId = $bookingId;

        $this->booking = $this->bookingRepository->findWithRelations($bookingId);
        $this->openModal();
    }
}
