<?php

namespace App\Enums;

enum BookingStatus: int
{
    case Draft = 0;
    case Confirmed = 1;
    case Cancelled = 2;
    case Completed = 3;

    public function label(): string
    {
        return match($this) {
            self::Draft => __('crud.bookings.status.draft'),
            self::Confirmed => __('crud.bookings.status.confirmed'),
            self::Cancelled => __('crud.bookings.status.canceled'),
            self::Completed => __('crud.bookings.status.completed'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft => 'zinc',
            self::Confirmed => 'orange',
            self::Cancelled => 'rose',
            self::Completed => 'teal',
        };
    }

    public function canEdit(): bool
    {
        return !in_array($this, [self::Completed, self::Cancelled]);
    }

    public function canCancel(): bool
    {
        return !in_array($this, [self::Completed, self::Cancelled]) && in_array($this, [self::Draft, self::Confirmed]);
    }

    public function canConfirm(): bool
    {
        return $this === self::Draft;
    }

    public function canRenew(): bool
    {
        return $this === self::Completed;
    }

    public function canPay(): bool
    {
        return !in_array($this, [self::Draft, self::Cancelled]);
    }
}
