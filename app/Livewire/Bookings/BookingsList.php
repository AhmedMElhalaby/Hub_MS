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
use App\Enums\PaymentMethod;
use App\Jobs\CreateHotspotUser;
use App\Jobs\RemoveHotspotUser;
use App\Jobs\UpdateHotspotUser;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;
use App\Traits\HasBookingActions;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class BookingsList extends Component
{
    use WithPagination, WithModal, WithSorting;
    use HasBookingActions;

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
    public $bookingId;

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
            DB::beginTransaction();

            if ($this->bookingId) {
                $booking = Booking::find($this->bookingId);
                $oldEndDate = $booking->ended_at;

                $booking->update([
                    'customer_id' => $this->customerId,
                    'workspace_id' => $this->workspaceId,
                    'plan_id' => $this->planId,
                    'started_at' => $this->startedAt,
                    'ended_at' => $this->endedAt,
                    'total' => $this->total,
                ]);

                // Recalculate balance based on existing payments
                $totalPaid = $booking->finances()->where('type', FinanceType::Income)->sum('amount');
                $newBalance = $this->total - $totalPaid;
                $booking->update(['balance' => max(0, $newBalance)]);

                // Update Mikrotik user if enabled
                if ($this->mikrotikEnabled) {
                    UpdateHotspotUser::dispatch($booking, $oldEndDate);
                }

                session()->flash('message', __('Booking updated successfully.'));
            } else {
                // Create new booking
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
                $booking->logEvent('Created', [
                    'customer' => $booking->customer->name,
                    'workspace' => $booking->workspace->desk,
                    'plan' => $booking->plan->type->label(),
                    'total' => $booking->total,
                ]);
            }
            DB::commit();
            $this->reset();
            $this->showModal = false;
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Failed to save booking.'));
            Log::error('Booking save failed', ['error' => $e->getMessage()]);
        }
    }

    public function confirmBooking(Booking $booking)
    {
        $booking->update(['status' => BookingStatus::Confirmed]);
        $booking->logEvent('Confirmed');
        session()->flash('message', __('Booking confirmed successfully.'));
    }

    public function showPayment(Booking $booking)
    {
        $this->selectedBooking = $booking;
        $this->paymentAmount = $booking->balance;
        $this->showPaymentModal = true;
    }

    public $paymentMethod = PaymentMethod::Cash->value; // Default to Cash

    public function processPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0|max:' . $this->selectedBooking->balance,
            'paymentMethod' => 'required|numeric|in:' . implode(',', array_column(PaymentMethod::cases(), 'value')),
        ]);

        try {
            DB::beginTransaction();

            $this->selectedBooking->update([
                'balance' => $this->selectedBooking->balance - $this->paymentAmount,
                'status' => BookingStatus::Confirmed,
            ]);

            $this->selectedBooking->finances()->create([
                'amount' => $this->paymentAmount,
                'type' => FinanceType::Income,
                'payment_method' => $this->paymentMethod,
            ]);
            $this->selectedBooking->logEvent('Payment', [
                'amount' => $this->paymentAmount,
                'remaining_balance' => $this->selectedBooking->balance,
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
        $booking->logEvent('Cancelled', [
            'reason' => 'User cancelled',
        ]);
    }

    // Add these properties
    public $renewalBooking;

    public function renewBooking($bookingId)
    {
        $booking = Booking::find($bookingId);
        $this->renewalBooking = $booking;
        $this->renewalPlanId = $booking->plan_id;
        $this->renewalStartedAt = now()->format('Y-m-d\TH:i');
        $this->calculateRenewalEndDate();
        $this->showRenewalModal = true;
    }

    public function updatedRenewalPlanId()
    {
        $this->calculateRenewalEndDate();
    }

    public function updatedRenewalStartedAt()
    {
        $this->calculateRenewalEndDate();
    }

    public function updatedRenewalDuration()
    {
        $this->calculateRenewalEndDate();
    }

    public function calculateRenewalEndDate()
    {
        if ($this->renewalPlanId && $this->renewalStartedAt && $this->renewalDuration) {
            $plan = Plan::find($this->renewalPlanId);
            $start = \Carbon\Carbon::parse($this->renewalStartedAt);
            $duration = (int) $this->renewalDuration;

            switch ($plan->type) {
                case PlanType::Hourly:
                    $this->renewalEndedAt = $start->copy()->addHours($duration);
                    break;
                case PlanType::Daily:
                    $this->renewalEndedAt = $start->copy()->addDays($duration);
                    break;
                case PlanType::Weekly:
                    $this->renewalEndedAt = $start->copy()->addWeeks($duration);
                    break;
                case PlanType::Monthly:
                    $this->renewalEndedAt = $start->copy()->addMonths($duration);
                    break;
            }
        }
    }

    public function processRenewal()
    {
        $this->validate([
            'renewalPlanId' => 'required|exists:plans,id',
            'renewalStartedAt' => 'required|date',
            'renewalEndedAt' => 'required|date|after:renewalStartedAt',
        ]);

        $oldEndDate = $this->renewalBooking->ended_at;
        $oldTotal = $this->renewalBooking->total;

        // Calculate new cost based on plan and duration
        $plan = Plan::find($this->renewalPlanId);
        $newCost = $plan->price * $this->renewalDuration;
        $totalCost = $oldTotal + $newCost;

        $this->renewalBooking->update([
            'plan_id' => $this->renewalPlanId,
            'started_at' => $this->renewalStartedAt,
            'ended_at' => $this->renewalEndedAt,
            'status' => BookingStatus::Confirmed,
            'total' => $totalCost,
            'balance' => $this->renewalBooking->balance + $newCost,
        ]);

        if ($this->mikrotikEnabled) {
            dispatch(new UpdateHotspotUser($this->renewalBooking, $oldEndDate));
        }

        $this->renewalBooking->logEvent('Renewed', [
            'old_end_date' => $oldEndDate->format('Y-m-d H:i:s'),
            'new_end_date' => $this->renewalEndedAt,
            'plan' => $plan->type->label(),
            'additional_cost' => $newCost,
            'new_total' => $totalCost
        ]);

        $this->showRenewalModal = false;
        session()->flash('message', __('Booking renewed successfully.'));
    }

    public function render()
    {
        $workspaces = Workspace::query()
            ->when(!$this->bookingId, function ($query) {
                // Only filter for available workspaces when creating new booking
                $query->available();
            })
            ->when($this->bookingId, function ($query) {
                // Include the current workspace when editing
                $query->where(function ($q) {
                    $q->available()
                      ->orWhere('id', $this->workspaceId);
                });
            })
            ->get();

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
                ->when($this->statusFilter !== '', function ($query) {
                    $query->where('status', $this->statusFilter);
                }, function ($query) {
                    // By default, exclude cancelled bookings unless specifically filtered
                    $query->where('status', '!=', \App\Enums\BookingStatus::Cancelled)->where('status', '!=', \App\Enums\BookingStatus::Completed);
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
            'customers' => $this->customers,
            'workspaces' => $workspaces,
            'plans' => Plan::all(),
            'specializations' => \App\Enums\Specialization::cases(), // Add this line
        ]);
    }


    #[On('customer-created')]
    public function handleCustomerCreated($customerId)
    {
        $this->customers = Customer::all();
        $this->customerId = $customerId;
    }
}
