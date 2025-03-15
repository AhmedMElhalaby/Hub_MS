<?php

namespace App\Traits;

use App\Enums\BookingStatus;
use App\Enums\FinanceType;
use App\Jobs\UpdateHotspotUser;
use App\Models\Booking;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait HasBookingActions
{
    public $showPaymentModal = false;
    public $showRenewalModal = false;
    public $selectedBooking;
    public $paymentAmount;
    public $renewalPlanId;
    public $renewalStartedAt;
    public $renewalDuration = 1;
    public $renewalEndedAt;

    public function showPayment($bookingId)
    {
        $this->selectedBooking = Booking::find($bookingId);
        $this->paymentAmount = $this->selectedBooking->balance;
        $this->showPaymentModal = true;
    }

    public function confirmBooking($bookingId)
    {
        $booking = Booking::find($bookingId);
        $booking->update(['status' => BookingStatus::Confirmed]);
        $booking->logEvent('Confirmed');
        session()->flash('message', __('Booking confirmed successfully.'));
    }

    public function processPayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:0|max:' . $this->selectedBooking->balance,
        ]);

        try {
            DB::beginTransaction();

            $this->selectedBooking->update([
                'balance' => $this->selectedBooking->balance - $this->paymentAmount,
            ]);

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

        $this->selectedBooking->logEvent('Payment', [
            'amount' => $this->paymentAmount,
            'remaining_balance' => $this->selectedBooking->balance,
        ]);
    }

    public function cancelBooking($bookingId)
    {
        $booking = Booking::find($bookingId);
        try {
            DB::beginTransaction();

            $booking->update(['status' => BookingStatus::Cancelled]);
            $booking->workspace->markAsAvailable();

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

        $booking->logEvent('Cancelled');
    }

    public function renewBooking($bookingId)
    {
        $this->selectedBooking = Booking::find($bookingId);
        $this->renewalPlanId = $this->selectedBooking->plan_id;
        $this->renewalStartedAt = now()->format('Y-m-d\TH:i');
        $this->renewalDuration = 1;
        $this->showRenewalModal = true;
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
            'renewalDuration' => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            $plan = Plan::find($this->renewalPlanId);
            $startDate = \Carbon\Carbon::parse($this->renewalStartedAt);
            $endDate = $startDate->copy()->addDays($this->renewalDuration);

            // Update existing booking instead of creating new one
            $this->selectedBooking->update([
                'plan_id' => $this->renewalPlanId,
                'started_at' => $startDate,
                'ended_at' => $endDate,
                'total' => $this->selectedBooking->total + ($plan->price * $this->renewalDuration),
                'balance' => $this->selectedBooking->balance + ($plan->price * $this->renewalDuration),
                'status' => BookingStatus::Confirmed,
            ]);

            // Update Mikrotik user with accumulated time
            UpdateHotspotUser::dispatch($this->selectedBooking);

            $this->selectedBooking->logEvent('Renewed', [
                'plan' => $plan->title,
                'duration' => $this->renewalDuration,
                'previous_end_date' => $this->selectedBooking->ended_at,
                'new_end_date' => $endDate,
            ]);

            DB::commit();
            $this->showRenewalModal = false;
            session()->flash('message', __('Booking renewed successfully.'));

            return redirect()->route('bookings.show', $this->selectedBooking);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Failed to renew booking.'));
            Log::error('Booking renewal failed', [
                'booking_id' => $this->selectedBooking->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function calculateRenewalEndDate()
    {
        if ($this->renewalStartedAt && $this->renewalDuration) {
            $startDate = \Carbon\Carbon::parse($this->renewalStartedAt);
            $this->renewalEndedAt = $startDate->copy()->addDays($this->renewalDuration)->format('Y-m-d\TH:i');
        }
    }
}
