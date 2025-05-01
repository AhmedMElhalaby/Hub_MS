<?php

namespace App\Livewire\Bookings;

use App\Enums\PlanType;
use App\Models\Plan;
use Livewire\Component;
use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use Carbon\Carbon;
use Livewire\Attributes\On;

class RenewBooking extends Component
{
    use WithModal, NotificationService;

    public $bookingId;
    public $booking;

    public $startedAt;
    public $endedAt;
    public $planId;
    public $duration = 1;
    public $total;


    protected BookingRepository $bookingRepository;

    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->startedAt = now()->format('Y-m-d\TH:i');
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

    public function renew()
    {
        $this->validate([
            'planId' => 'required|exists:plans,id',
            'startedAt' => 'required|date|after:booking.ended_at',
            'duration' => 'required|integer|min:1',
        ]);

        try {
            $this->calculateEndDate();
            $this->calculateTotal();
            $this->bookingRepository->renew(
                $this->booking,
                $this->planId,
                Carbon::parse($this->startedAt),
                $this->endedAt,
                $this->total
            );
            $this->reset(['startedAt', 'endedAt', 'planId', 'duration', 'total']);
            $this->closeModal();
            $this->dispatch('booking-renewed');
            $this->notifySuccess('messages.booking.renew_processed');
        } catch (\Exception $e) {
            $this->notifyError($e->getMessage());
            $this->notifyError('messages.booking.renew_error');
        }
    }

    public function render()
    {
        return view('livewire.bookings.renew-booking', [
            'booking' => $this->booking,
            'plans' => Plan::all()
        ]);
    }

    #[On('open-renew-booking')]
    public function open($bookingId)
    {
        $this->bookingId = $bookingId;
        $this->booking = $this->bookingRepository->findById($this->bookingId);
        $this->planId = $this->booking->plan_id;

        // Set the start date to be after the current booking's end date
        $this->startedAt = $this->booking->ended_at->format('Y-m-d\TH:i');

        $this->calculateEndDate();
        $this->calculateTotal();
        $this->openModal();
    }
}
