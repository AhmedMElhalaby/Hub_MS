<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Services\MikrotikService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use \Illuminate\Support\Facades\Log;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'workspace_id',
        'plan_id',
        'started_at',
        'ended_at',
        'total',
        'balance',
        'status',
        'hotspot_username',
        'hotspot_password'
    ];
    protected $casts = [
        'status' => BookingStatus::class,
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'total' => 'decimal:2',
        'balance' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }

    protected static function booted()
    {
    }

}
