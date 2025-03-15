<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Plan;
use App\Models\Customer;
use App\Models\Workspace;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Traits\HasBookingActions;
use \Illuminate\Support\Facades\Log;

#[Layout('components.layouts.app')]
class BookingDetails extends Component
{
    use HasBookingActions;

    public Booking $booking;
    public $plans;
    public $customers;
    public $workspaces;
    public $showCredentialsModal = false;
    public $messageText;
    public $selectedBooking;

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
            // Send message using your messaging service
            $this->booking->customer->sendMessage($message);

            $this->booking->logEvent('Credentials Sent', [
                'sent_by' => auth()->user()->name
            ]);

            $this->showCredentialsModal = false;
            session()->flash('message', __('Credentials sent successfully.'));
            $this->redirect(request()->header('Referer'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', __('Failed to send credentials.'));
            Log::error('Failed to send credentials', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage()
            ]);
            $this->redirect(request()->header('Referer'), navigate: true);
        }
    }

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->selectedBooking = $booking;
        $this->plans = Plan::all();
        $this->customers = Customer::all();
        $this->workspaces = Workspace::all();
        $this->messageText = __(
            "Internet access:\nU: :username\nP: :password"
        );
    }

    public function render()
    {
        return view('livewire.bookings.booking-details');
    }
}
