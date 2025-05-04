<?php

namespace App\Models;

use App\Enums\PlanType;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory,BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'type',
        'price',
        'mikrotik_profile',
    ];

    protected $casts = [
        'type' => PlanType::class,
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
