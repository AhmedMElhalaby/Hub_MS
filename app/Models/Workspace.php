<?php

namespace App\Models;

use App\Enums\WorkspaceStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workspace extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'desk',
        'status'
    ];

    protected $casts = [
        'status' => WorkspaceStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', WorkspaceStatus::Available);
    }

    public function markAsAvailable()
    {
        $this->update(['status' => WorkspaceStatus::Available]);
    }

    public function markAsBooked()
    {
        $this->update(['status' => WorkspaceStatus::Booked]);
    }
}
