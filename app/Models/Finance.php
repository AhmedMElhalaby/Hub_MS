<?php

namespace App\Models;

use App\Enums\FinanceType;
use App\Enums\PaymentMethod;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Finance extends Model
{
    use HasFactory,BelongsToTenant;

    protected $fillable = [
        'type',
        'booking_id',
        'expense_id',
        'amount',
        'note',
        'payment_method'
    ];

    protected $casts = [
        'type' => FinanceType::class,
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'payment_method' => PaymentMethod::class
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
