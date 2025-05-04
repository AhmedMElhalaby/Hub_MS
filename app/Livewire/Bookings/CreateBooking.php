<?php

namespace App\Livewire\Bookings;

use App\Enums\BookingStatus;
use App\Enums\PlanType;
use App\Models\Customer;
use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Models\Plan;
use App\Models\Workspace;
use Carbon\Carbon;
use Livewire\Attributes\On;

class CreateBooking extends Component
{
    use WithModal, NotificationService;

    public $plans = [];
    public $customers = [];
    public $workspaces = [];
    public $customerId;
    public $workspaceId;
    public $planId;
    public $startedAt;
    public $duration = 1;
    public $endedAt;
    public $total = 0;
    public $status = BookingStatus::Confirmed;

    public function mount()
    {
        // This will automatically apply the tenant scope from the BelongsToTenant trait
        $this->plans = Plan::all();
        $this->customers = Customer::all();
        $this->workspaces = Workspace::available()->get();
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
            $start = Carbon::parse($this->startedAt);
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

    protected BookingRepository $bookingRepository;

    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }
    public function storeAsDraft()
    {
        $this->status = BookingStatus::Draft;
        $this->store();
    }
    public function store()
    {
        $this->validate([
            'customerId' => 'required|exists:customers,id',
            'workspaceId' => 'required|exists:workspaces,id',
            'planId' => 'required|exists:plans,id',
            'startedAt' => 'required|date',
            'endedAt' => 'required|date|after:startedAt',
            'total' => 'required|numeric|min:0'
        ]);
        try {
            $this->bookingRepository->create([
                'customer_id' => $this->customerId,
                'workspace_id' => $this->workspaceId,
                'plan_id' => $this->planId,
                'started_at' => $this->startedAt,
                'ended_at' => $this->endedAt,
                'total' => $this->total,
                'status' => $this->status
            ]);
            $this->reset();
            $this->closeModal();
            $this->dispatch('booking-created');
            $this->notifySuccess('messages.booking.created');
        } catch (\Exception $e) {
            $this->notifyError($e->getMessage());
            $this->notifyError('messages.booking.save_error');
        }
    }

    public function render()
    {
        return view('livewire.bookings.create-booking');
    }

    #[On('open-create-booking')]
    public function open()
    {
        $this->showModal = true;
    }
}
