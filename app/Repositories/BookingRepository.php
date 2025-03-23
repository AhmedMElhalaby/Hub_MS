<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Enums\FinanceType;
use App\Jobs\CreateHotspotUser;
use App\Jobs\RemoveHotspotUser;
use App\Jobs\UpdateHotspotUser;
use App\Jobs\ResetHotspotUser;
use App\Models\Setting;

class BookingRepository extends BaseRepository
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($query, $search)
    {
        return $query->whereHas('customer', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        })->orWhereHas('workspace', function ($q) use ($search) {
            $q->where('desk', 'like', '%' . $search . '%');
        });
    }

    public function create(array $data)
    {
        $booking = parent::create(array_merge($data, [
            'status' => BookingStatus::Draft,
            'balance' => $data['total']
        ]));

        $booking->workspace->markAsBooked();

        if (Setting::get('mikrotik_enabled', false)) {
            CreateHotspotUser::dispatch($booking);
        }

        $booking->logEvent('Created', [
            'customer' => $booking->customer->name,
            'workspace' => $booking->workspace->desk,
            'plan' => $booking->plan->type->label(),
            'total' => $booking->total,
        ]);

        return $booking;
    }

    public function update($id, array $data)
    {
        $booking = $this->findById($id);
        $oldEndDate = $booking->ended_at;

        $booking = parent::update($id, $data);

        // Recalculate balance based on existing payments
        $totalPaid = $booking->finances()->where('type', FinanceType::Income)->sum('amount');
        $newBalance = $data['total'] - $totalPaid;
        $booking->update(['balance' => max(0, $newBalance)]);

        if (Setting::get('mikrotik_enabled', false)) {
            UpdateHotspotUser::dispatch($booking, $oldEndDate);
        }

        return $booking;
    }

    public function confirm($id)
    {
        $booking = $this->findById($id);
        $booking->update(['status' => BookingStatus::Confirmed]);
        $booking->logEvent('Confirmed');
        return $booking;
    }

    public function cancel($id)
    {
        $booking = $this->findById($id);
        $booking->update(['status' => BookingStatus::Cancelled]);
        $booking->workspace->markAsAvailable();

        if (Setting::get('mikrotik_enabled', false)) {
            RemoveHotspotUser::dispatch($booking);
        }

        $booking->logEvent('Cancelled', [
            'reason' => 'User cancelled',
        ]);

        return $booking;
    }

    public function addPayment($id, $amount, $paymentMethod)
    {
        $booking = $this->findById($id);

        $booking->update([
            'balance' => $booking->balance - $amount,
        ]);

        $payment = $booking->finances()->create([
            'amount' => $amount,
            'type' => FinanceType::Income,
            'payment_method' => $paymentMethod,
        ]);

        $booking->logEvent('Payment', [
            'amount' => $amount,
            'remaining_balance' => $booking->balance,
        ]);

        return $payment;
    }

    public function renew($id, array $data)
    {
        $booking = $this->findById($id);
        $oldEndDate = $booking->ended_at;
        $oldTotal = $booking->total;

        $totalCost = $oldTotal + $data['additional_cost'];

        $booking->update([
            'plan_id' => $data['plan_id'],
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'status' => BookingStatus::Confirmed,
            'total' => $totalCost,
            'balance' => $booking->balance + $data['additional_cost'],
        ]);

        if (Setting::get('mikrotik_enabled', false)) {
            UpdateHotspotUser::dispatch($booking, $oldEndDate);
            ResetHotspotUser::dispatch($booking);
        }

        $booking->logEvent('Renewed', [
            'old_end_date' => $oldEndDate->format('Y-m-d H:i:s'),
            'new_end_date' => $data['ended_at'],
            'plan' => $booking->plan->type->label(),
            'additional_cost' => $data['additional_cost'],
            'new_total' => $totalCost
        ]);

        return $booking;
    }

    public function findWithRelations($id)
    {
        return $this->model->with(['customer', 'workspace', 'plan', 'finances', 'events'])
            ->findOrFail($id);
    }

    public function getActiveBookings($search = null, $status = null, $dateFilter = null)
    {
        return $this->model->with(['customer', 'workspace', 'plan'])
            ->when($search, function ($query, $search) {
                $this->applySearch($query, $search);
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            }, function ($query) {
                $query->where('status', '!=', BookingStatus::Cancelled)
                    ->where('status', '!=', BookingStatus::Completed);
            })
            ->when($dateFilter, function ($query) use ($dateFilter) {
                switch ($dateFilter) {
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
            ->latest();
    }
}