<?php

namespace App\Livewire\Bookings;

use App\Models\Customer;
use App\Models\Workspace;
use App\Models\Plan;
use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use App\Enums\BookingStatus;
use App\Enums\PlanType;
use App\Enums\PaymentMethod;
use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Traits\WithSorting;
use App\Models\Setting;
use App\Traits\HasBookingActions;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class BookingsList extends Component
{
    use WithPagination, WithModal, WithSorting, HasBookingActions, NotificationService;

    protected BookingRepository $bookingRepository;

    public $bookingId;

    public $customerId;
    public $workspaceId;
    public $planId;
    public $startedAt;
    public $endedAt;
    public $total;
    public $balance;
    public $selectedBooking;
    public $customers = [];

    public $duration = 1;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $mikrotikEnabled = false;

    public function mount()
    {
        $this->mikrotikEnabled = Setting::get('mikrotik_enabled', false);
        $this->customers = Customer::all(); // Add this line
    }
    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->dateFilter = '';
    }
    public function updatedPlanId()
    {
        if ($this->planId) {
            $plan = Plan::find($this->planId);
            $now = now();

            // Set default start time based on plan type
            if ($plan->type === PlanType::Hourly) {
                // Start from next hour
                $this->startedAt = $now->addHour()->startOfHour()->format('Y-m-d\TH:i');
            } else {
                // For daily, weekly, monthly plans, start at 9 AM next day
                $this->startedAt = $now->addDay()->setHour(9)->setMinute(0)->format('Y-m-d\TH:i');
            }
        }

        $this->calculateEndDate();
        $this->calculateTotal();
    }

    public function updatedStartedAt()
    {
        $this->calculateEndDate();
        $this->calculateTotal();
    }

    public function updatedDuration()
    {
        $this->calculateEndDate();
        $this->calculateTotal();
    }

    public function calculateEndDate()
    {
        if ($this->planId && $this->startedAt && $this->duration) {
            $plan = Plan::find($this->planId);
            $start = \Carbon\Carbon::parse($this->startedAt);
            $duration = (int) $this->duration;

            // Calculate end date based on plan type and duration
            switch ($plan->type) {
                case PlanType::Hourly:
                    $this->endedAt = $start->addHours($duration);
                    break;
                case PlanType::Daily:
                    $this->endedAt = $start->addDays($duration);
                    break;
                case PlanType::Weekly:
                    $this->endedAt = $start->addWeeks($duration);
                    break;
                case PlanType::Monthly:
                    $this->endedAt = $start->addMonths($duration);
                    break;
            }
        }
    }

    public function calculateTotal()
    {
        if ($this->planId && $this->duration) {
            $plan = Plan::find($this->planId);
            $this->total = $plan->price * $this->duration;
        }
    }

    public function resetForm()
    {
        $this->reset([
            'customerId',
            'workspaceId',
            'planId',
            'startedAt',
            'endedAt',
            'duration',
            'total',
            'balance',
            'paymentAmount',
            'selectedBooking',
        ]);
    }

    // Add this property with the other public properties

    #[On('editBooking')]
    public function editBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if (in_array($booking->status, [BookingStatus::Completed, BookingStatus::Cancelled])) {
            session()->flash('error', __('Cannot edit completed or cancelled bookings.'));
            return;
        }

        // Mark current workspace as available before editing
        $booking->workspace->markAsAvailable();

        $this->bookingId = $booking->id;
        $this->customerId = $booking->customer_id;
        $this->workspaceId = $booking->workspace_id;
        $this->planId = $booking->plan_id;
        $this->startedAt = $booking->started_at->format('Y-m-d\TH:i');

        // Calculate duration based on plan type
        $this->duration = match($booking->plan->type) {
            PlanType::Hourly => $booking->started_at->diffInHours($booking->ended_at),
            PlanType::Daily => $booking->started_at->diffInDays($booking->ended_at),
            PlanType::Weekly => $booking->started_at->diffInWeeks($booking->ended_at),
            PlanType::Monthly => $booking->started_at->diffInMonths($booking->ended_at),
        };

        $this->calculateEndDate();
        $this->calculateTotal();

        $this->showModal = true;
    }

    // Add this method to reset the form properly
    public function closeModal()
    {
        if ($this->bookingId) {
            // If editing was cancelled, mark the workspace as booked again
            $booking = Booking::find($this->bookingId);
            $booking->workspace->markAsBooked();
        }
        $this->resetForm();
        $this->bookingId = null;
        $this->showModal = false;
    }


    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function create()
    {
        $this->validate([
            'customerId' => 'required|exists:customers,id',
            'workspaceId' => 'required|exists:workspaces,id',
            'planId' => 'required|exists:plans,id',
            'startedAt' => 'required|date',
            'duration' => 'required|numeric|min:1',
        ]);

        try {
            if ($this->bookingId) {
                $this->bookingRepository->update($this->bookingId, [
                    'customer_id' => $this->customerId,
                    'workspace_id' => $this->workspaceId,
                    'plan_id' => $this->planId,
                    'started_at' => $this->startedAt,
                    'ended_at' => $this->endedAt,
                    'total' => $this->total,
                ]);
                $this->notifySuccess('messages.booking.updated');
            } else {
                $this->bookingRepository->create([
                    'customer_id' => $this->customerId,
                    'workspace_id' => $this->workspaceId,
                    'plan_id' => $this->planId,
                    'started_at' => $this->startedAt,
                    'ended_at' => $this->endedAt,
                    'total' => $this->total,
                ]);
                $this->notifySuccess('messages.booking.created');
            }
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.save_error');
        }
    }

    public function confirmBooking($bookingId)
    {
        try {
            $this->bookingRepository->confirm($bookingId);
            $this->notifySuccess('messages.booking.confirmed');
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.confirm_error');
        }
    }

    public function processPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0|max:' . $this->selectedBooking->balance,
            'paymentMethod' => 'required|numeric|in:' . implode(',', array_column(PaymentMethod::cases(), 'value')),
        ]);

        try {
            $this->bookingRepository->addPayment(
                $this->selectedBooking->id,
                $this->paymentAmount,
                $this->paymentMethod
            );
            $this->showPaymentModal = false;
            $this->notifySuccess('messages.booking.payment_processed');
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.payment_error');
        }
    }

    public function cancelBooking($bookingId)
    {
        try {
            $this->bookingRepository->cancel($bookingId);
            $this->notifySuccess('messages.booking.cancelled');
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.cancel_error');
        }
    }

    public function processRenewal()
    {
        $this->validate([
            'renewalPlanId' => 'required|exists:plans,id',
            'renewalStartedAt' => 'required|date',
            'renewalEndedAt' => 'required|date|after:renewalStartedAt',
        ]);

        try {
            $plan = Plan::find($this->renewalPlanId);
            $newCost = $plan->price * $this->renewalDuration;

            $this->bookingRepository->renew($this->renewalBooking->id, [
                'plan_id' => $this->renewalPlanId,
                'started_at' => $this->renewalStartedAt,
                'ended_at' => $this->renewalEndedAt,
                'additional_cost' => $newCost,
            ]);

            $this->showRenewalModal = false;
            $this->notifySuccess('messages.booking.renewed');
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.renew_error');
        }
    }

    public function render()
    {
        return view('livewire.bookings.bookings-list', [
            'bookings' => $this->bookingRepository->getActiveBookings(
                $this->search,
                $this->statusFilter,
                $this->dateFilter
            )->paginate(10),
            'customers' => $this->customers,
            'workspaces' => Workspace::available()->get(),
            'plans' => Plan::all(),
            'specializations' => \App\Enums\Specialization::cases(),
        ]);
    }


    #[On('customer-created')]
    public function handleCustomerCreated($customerId)
    {
        $this->customers = Customer::all();
        $this->customerId = $customerId;
    }
}
