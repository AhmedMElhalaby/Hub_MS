<?php

namespace App\Livewire\Bookings;

use App\Enums\PlanType;
use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Workspace;
use Carbon\Carbon;
use Livewire\Attributes\On;

class EditBooking extends Component
{
    use WithModal, NotificationService;

    public $workspaces = [];
    public $booking;
    public $bookingId;
    public $customerId;
    public $workspaceId;
    public $customers = [];
    public $plans = [];
    public $planId;
    public $startedAt;
    public $duration;
    public $endedAt;
    public $total;

    protected BookingRepository $bookingRepository;

    public function mount(){
        $this->booking = $this->bookingRepository->findById($this->bookingId);
        $this->workspaces = Workspace::where(function($query){
            $query->available()
                ->orWhere('id', $this->booking->workspace_id);
        })->get();
        $this->plans = Plan::all();
        $this->customers = Customer::all();
    }

    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
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

    public function update()
    {
        $validated = $this->validate([
            'customerId' => 'required|exists:customers,id',
            'workspaceId' => 'required|exists:workspaces,id',
            'planId' => 'required|exists:plans,id',
            'startedAt' => 'required|date',
            'endedAt' => 'required|date|after:startedAt',
            'total' => 'required|numeric|min:0'
        ]);
        try {
            $this->bookingRepository->update($this->bookingId, [
                'customer_id' => $validated['customerId'],
                'workspace_id' => $validated['workspaceId'],
                'plan_id' => $validated['planId'],
                'started_at' => $validated['startedAt'],
                'ended_at' => $validated['endedAt'],
                'total' => $validated['total'],
            ]);
            $this->reset();
            $this->closeModal();
            $this->dispatch('booking-updated');
            $this->notifySuccess(__('crud.bookings.messages.updated'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.bookings.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.bookings.edit-booking');
    }

    #[On('open-edit-booking')]
    public function open()
    {
        $this->bookingId = $this->booking->id;
        $this->customerId = $this->booking->customer_id;
        $this->workspaceId = $this->booking->workspace_id;
        $this->planId = $this->booking->plan_id;
        $this->startedAt = $this->booking->started_at->format('Y-m-d\TH:i');
        $this->endedAt = $this->booking->ended_at;
        $this->total = $this->booking->total;
        $this->duration = $this->booking->getDurationFromDates();
        $this->openModal();
    }
}
