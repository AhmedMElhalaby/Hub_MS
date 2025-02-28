<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Workspace;
use App\Models\Plan;
use App\Traits\WithModal;
use App\Enums\BookingStatus;
use App\Enums\PlanType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Traits\WithSorting;
use App\Enums\FinanceType;

#[Layout('components.layouts.app')]
class BookingsList extends Component
{
    use WithPagination, WithModal, WithSorting;

    public $customerId;
    public $workspaceId;
    public $planId;
    public $startedAt;
    public $endedAt;
    public $total;
    public $balance;

    public $paymentAmount = 0;
    public $selectedBooking;
    public $showPaymentModal = false;

    public $duration = 1;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->dateFilter = '';
    }
    public function updatedPlanId()
    {
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

    public function create()
    {
        $validated = $this->validate([
            'customerId' => 'required|exists:customers,id',
            'workspaceId' => 'required|exists:workspaces,id',
            'planId' => 'required|exists:plans,id',
            'startedAt' => 'required|date|after:now',
            'endedAt' => 'required|date|after:startedAt',
        ]);

        $booking = Booking::create([
            'customer_id' => $validated['customerId'],
            'workspace_id' => $validated['workspaceId'],
            'plan_id' => $validated['planId'],
            'started_at' => $validated['startedAt'],
            'ended_at' => $validated['endedAt'],
            'total' => $this->total,
            'balance' => $this->total,
            'status' => BookingStatus::Draft,
        ]);
        $booking->workspace->markAsBooked();
        session()->flash('message', __('Booking created successfully.'));
        $this->closeModal();
    }

    public function confirmBooking(Booking $booking)
    {
        $this->selectedBooking = $booking;
        $this->paymentAmount = $booking->balance;
        $this->showPaymentModal = true;
    }

    public function processPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0|max:' . $this->selectedBooking->balance,
        ]);

        // Process payment and update booking status
        $this->selectedBooking->update([
            'balance' => $this->selectedBooking->balance - $this->paymentAmount,
            'status' => BookingStatus::Confirmed,
        ]);

        // Create payment record
        $this->selectedBooking->finances()->create([
            'amount' => $this->paymentAmount,
            'type' => FinanceType::Income,
        ]);

        $this->showPaymentModal = false;
        session()->flash('message', __('Payment processed successfully.'));
    }

    public function cancelBooking(Booking $booking)
    {
        $booking->update(['status' => BookingStatus::Cancelled]);
        $booking->workspace->markAsAvailable();
        session()->flash('message', __('Booking cancelled successfully.'));
    }

    public function renewBooking(Booking $booking)
    {
        $this->customerId = $booking->customer_id;
        $this->workspaceId = $booking->workspace_id;
        $this->planId = $booking->plan_id;
        $this->startedAt = now()->format('Y-m-d\TH:i');
        $this->duration = 1;

        // Calculate end date and total based on selected plan
        $this->calculateEndDate();
        $this->calculateTotal();

        // Open the create modal
        $this->openModal();
    }

    public function render()
    {
        return view('livewire.bookings.bookings-list', [
            'bookings' => Booking::with(['customer', 'workspace', 'plan'])
                ->when($this->search, function ($query) {
                    $query->whereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('workspace', function ($q) {
                        $q->where('desk', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->when($this->dateFilter, function ($query) {
                    switch ($this->dateFilter) {
                        case 'today':
                            $query->whereDate('started_at', today());
                            break;
                        case 'week':
                            $query->whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()]);
                            break;
                        case 'month':
                            $query->whereBetween('started_at', [now()->startOfMonth(), now()->endOfMonth()]);
                            break;
                    }
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->latest()
                ->paginate(10),
            'customers' => Customer::all(),
            'workspaces' => Workspace::available()->get(),
            'plans' => Plan::all(),
        ]);
    }
}
