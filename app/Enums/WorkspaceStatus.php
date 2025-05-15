<?php

namespace App\Enums;

enum WorkspaceStatus: int
{
    case Booked = 0;
    case Available = 1;
    case Maintenance = 2;

    public function label(): string
    {
        return match ($this) {
            self::Booked => __('crud.workspaces.status.booked'),
            self::Available => __('crud.workspaces.status.available'),
            self::Maintenance => __('crud.workspaces.status.maintenance'),
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
