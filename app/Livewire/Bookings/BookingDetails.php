<?php

namespace App\Livewire\Bookings;

use App\Models\Plan;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Workspace;
use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Traits\HasBookingActions;

#[Layout('components.layouts.app')]
class BookingDetails extends Component
{
    use HasBookingActions, NotificationService;

    public $booking;
    public $plans;
    public $customers;
    public $workspaces;
    public $showCredentialsModal = false;
    public $messageText;
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
            $this->plans = Plan::all();
            $this->customers = Customer::all();
            $this->workspaces = Workspace::all();
            $this->messageText = __(
                "Internet access:\nU: :username\nP: :password"
            );
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.not_found');
            return $this->redirect(route('bookings.index'));
        }
    }

    public function sendCredentials()
    {
        $this->validate([
            'messageText' => 'required|string'
        ]);

        try {
            $message = str_replace(
                [':username', ':password'],
                [$this->booking->hotspot_username, $this->booking->hotspot_password],
                $this->messageText
            );

            $this->booking->customer->sendMessage($message);
            $this->booking->logEvent('Credentials Sent', [
                'sent_by' => auth()->user()->name
            ]);

            $this->notifySuccess('messages.booking.credentials_sent');
            $this->showCredentialsModal = false;
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.credentials_send_error');
        }
    }

    public function render()
    {
        return view('livewire.bookings.booking-details');
    }
}
