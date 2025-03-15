<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PlanType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    // Add this relationship
    public function events(): HasMany
    {
        return $this->hasMany(BookingEvent::class);
    }

    // Add this method to log events
    public function logEvent(string $eventType, array $metadata = []): void
    {
        $this->events()->create([
            'user_id' => auth()->id(),
            'event_type' => $eventType,
            'metadata' => $metadata,
        ]);
    }

    protected static function booted()
    {
    }

    public function getDurationFromDates()
        {
            $start = Carbon::parse($this->started_at);
            $end = Carbon::parse($this->ended_at);

            switch ($this->plan->type) {
                case PlanType::Hourly:
                    return $end->diffInHours($start);
                case PlanType::Daily:
                    return $end->diffInDays($start);
                case PlanType::Weekly:
                    return $end->diffInWeeks($start);
                case PlanType::Monthly:
                    return $end->diffInMonths($start);
                default:
                    return 1;
            }
        }
}
