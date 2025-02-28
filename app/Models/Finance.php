<?php

namespace App\Models;

use App\Enums\FinanceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'booking_id',
        'expense_id',
        'amount',
        'note'
    ];

    protected $casts = [
        'type' => FinanceType::class,
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
