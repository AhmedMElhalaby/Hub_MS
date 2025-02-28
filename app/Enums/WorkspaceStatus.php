<?php

namespace App\Enums;

enum WorkspaceStatus: string
{
    case Booked = 'booked';
    case Available = 'available';
    case Maintenance = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::Booked => __('Booked'),
            self::Available => __('Available'),
            self::Maintenance => __('Maintenance'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Booked => 'amber',
            self::Available => 'green',
            self::Maintenance => 'red',
        };
    }
}
