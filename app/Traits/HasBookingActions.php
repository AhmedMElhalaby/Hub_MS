<?php

namespace App\Traits;

use App\Enums\PaymentMethod;
use App\Enums\PlanType;
use App\Models\Plan;
use App\Repositories\BookingRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;

trait HasBookingActions
{
    use NotificationService;

    protected BookingRepository $bookingRepository;

    public $showPaymentModal = false;
    public $showRenewalModal = false;
    public $selectedBooking;
    public $paymentAmount;
    public $paymentMethod;
    public $renewalPlanId;
    public $renewalStartedAt;
    public $renewalDuration = 1;
    public $renewalEndedAt;

    public function bootHasBookingActions(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function showPayment($bookingId)
    {
        $this->selectedBooking = $this->bookingRepository->findWithRelations($bookingId);
        $this->paymentAmount = $this->selectedBooking->balance;
        $this->paymentMethod = PaymentMethod::Cash->value;
        $this->showPaymentModal = true;
    }

    public function confirmBooking($bookingId)
    {
        try {
            $this->bookingRepository->confirm($bookingId);
            $this->notifySuccess('messages.booking.confirmed');
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.confirm_error');
            Log::error('Booking confirmation failed', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function processPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0|max:' . $this->selectedBooking->balance,
            'paymentMethod' => 'required|in:' . implode(',', array_column(PaymentMethod::cases(), 'value')),
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
            Log::error('Payment processing failed', [
                'booking_id' => $this->selectedBooking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function cancelBooking($bookingId)
    {
        try {
            $this->bookingRepository->cancel($bookingId);
            $this->notifySuccess('messages.booking.cancelled');
        } catch (\Exception $e) {
            $this->notifyError('messages.booking.cancel_error');
            Log::error('Booking cancellation failed', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function renewBooking($bookingId)
    {
        $this->selectedBooking = $this->bookingRepository->findWithRelations($bookingId);
        $this->renewalPlanId = $this->selectedBooking->plan_id;
        $this->renewalStartedAt = now()->format('Y-m-d\TH:i');
        $this->renewalDuration = 1;
        $this->showRenewalModal = true;
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

            $this->bookingRepository->renew($this->selectedBooking->id, [
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

    protected function calculateRenewalEndDate()
    {
        if ($this->renewalPlanId && $this->renewalStartedAt && $this->renewalDuration) {
            $plan = Plan::find($this->renewalPlanId);
            $start = \Carbon\Carbon::parse($this->renewalStartedAt);
            $duration = (int) $this->renewalDuration;

            // Calculate end date based on plan type and duration
            switch ($plan->type) {
                case PlanType::Hourly:
                    $this->renewalEndedAt = $start->addHours($duration);
                    break;
                case PlanType::Daily:
                    $this->renewalEndedAt = $start->addDays($duration);
                    break;
                case PlanType::Weekly:
                    $this->renewalEndedAt = $start->addWeeks($duration);
                    break;
                case PlanType::Monthly:
                    $this->renewalEndedAt = $start->addMonths($duration);
                    break;
            }
        }
    }
}
