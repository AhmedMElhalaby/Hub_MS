<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Draft = 'draft';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    public function label(): string
    {
        return match($this) {
            self::Draft => __('Draft'),
            self::Confirmed => __('Confirmed'),
            self::Cancelled => __('Cancelled'),
            self::Completed => __('Completed'),
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
}
