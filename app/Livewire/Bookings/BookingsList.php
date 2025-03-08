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
use App\Jobs\CreateHotspotUser;
use App\Jobs\RemoveHotspotUser;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;

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
    public $mikrotikEnabled = false;

    public function mount()
    {
        $this->mikrotikEnabled = Setting::get('mikrotik_enabled', false);
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

    public function create()
    {
        $validated = $this->validate([
            'customerId' => 'required|exists:customers,id',
            'workspaceId' => 'required|exists:workspaces,id',
            'planId' => 'required|exists:plans,id',
            'startedAt' => 'required|date', // Removed 'after:now' validation
            'endedAt' => 'required|date|after:startedAt', // Only validate that end date is after start date
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
        if ($this->mikrotikEnabled) {
            CreateHotspotUser::dispatch($booking);
        }
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

        try {
            DB::beginTransaction();

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
            DB::commit();

            $this->showPaymentModal = false;
            session()->flash('message', __('Payment processed successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Failed to process payment.'));
            Log::error('Payment processing failed', [
                'booking_id' => $this->selectedBooking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function cancelBooking(Booking $booking)
    {
        try {
            DB::beginTransaction();

            $booking->update(['status' => BookingStatus::Cancelled]);
            $booking->workspace->markAsAvailable();

            // Dispatch hotspot user removal job
            RemoveHotspotUser::dispatch($booking);

            DB::commit();
            session()->flash('message', __('Booking cancelled successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Failed to cancel booking.'));
            Log::error('Booking cancellation failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
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
