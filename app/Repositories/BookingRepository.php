<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Enums\FinanceType;
use App\Jobs\CreateHotspotUser;
use App\Jobs\UpdateHotspotUser;
use App\Models\Setting;

class BookingRepository extends BaseRepository
{
    public function __construct(Booking $model)
    {
        parent::__construct($model);
    }

    protected function applySearch($query, $search)
    {
        if (!empty($search)) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }

    public function findWithRelations($id)
    {
        return $this->findById($id)->load(['customer', 'workspace', 'plan', 'finances', 'events']);
    }

    public function create(array $data)
    {
        $booking = parent::create(array_merge($data, [
            'balance' => $data['total'],
        ]));

        $booking->workspace->markAsBooked();
        $booking->update([
            'hotspot_username' => str_pad($booking->id,5,"0",STR_PAD_LEFT),
            'hotspot_password' => mt_rand(10000, 99999)
        ]);
        return $booking;
    }

    public function update($id, array $data)
    {
        $booking = $this->findById($id);
        $oldEndDate = $booking->ended_at;
        $paidAmount = $booking->total - $booking->balance;
        $data['balance'] = $data['total'] - $paidAmount;
        $data['balance'] = max(0, $data['balance']);
        $booking = parent::update($id, $data);
        if (Setting::get('mikrotik_enabled', false)) {
            UpdateHotspotUser::dispatch($booking, $oldEndDate);
        }
        return $booking;
    }

    public function getActiveBookings($search = null, $status = null, $dateFilter = null, $plan = null)
    {
        return $this->model
            ->with(['customer', 'workspace', 'plan'])
            ->when($search, function ($query) use ($search) {
                return $this->applySearch($query, $search);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($dateFilter && $dateFilter['from'] && $dateFilter['to'], function ($query) use ($dateFilter) {
                $column = match($dateFilter['type']) {
                    'start' => 'started_at',
                    'end' => 'ended_at',
                    default => 'created_at',
                };
                return $query->whereBetween($column, [$dateFilter['from'], $dateFilter['to']]);
            })
            ->when($plan, function ($query) use ($plan) {
                return $query->where('plan_id', $plan);
            })
            ->latest();
    }

    public function addPayment($id, $amount, $paymentMethod)
    {
        $booking = $this->findById($id);
        $booking->update(['balance' => $booking->balance - $amount]);

        return $booking->finances()->create([
            'amount' => $amount,
            'type' => FinanceType::Income,
            'payment_method' => $paymentMethod,
        ]);
    }
    public function cancel($id)
    {
        $booking = $this->findById($id);
        $booking->status = BookingStatus::Cancelled;
        $booking->save();
        $booking->workspace->markAsAvailable();
        return $booking;
    }
    public function confirm($id)
    {
        $booking = $this->findById($id);
        $booking->status = BookingStatus::Confirmed;
        $booking->save();
        return $booking;
    }
    public function renew($booking, $planId, $startedAt, $endedAt, $total)
    {
        $booking->logEvent('Booking Renewed', [
            'previous_plan' => $booking->plan->title,
            'previous_start' => $booking->started_at->format('Y-m-d H:i'),
            'previous_end' => $booking->ended_at->format('Y-m-d H:i'),
            'renewal_date' => now()->format('Y-m-d H:i')
        ]);

        // Create a new booking with the renewal details
        $newBooking = $this->create([
            'customer_id' => $booking->customer_id,
            'workspace_id' => $booking->workspace_id,
            'plan_id' => $planId,
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
            'total' => $total,
            'status' => BookingStatus::Confirmed,
        ]);

        $newBooking->logEvent('Booking Created from Renewal', [
            'original_booking_id' => $booking->id,
            'original_plan' => $booking->plan->title,
            'renewal_date' => now()->format('Y-m-d H:i')
        ]);

        return $newBooking;
    }

}
