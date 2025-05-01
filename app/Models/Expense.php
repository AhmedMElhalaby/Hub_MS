<?php

namespace App\Models;

use App\Enums\ExpenseCategory;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory,BelongsToTenant;

    protected $fillable = [
        'category',
        'amount'
    ];

    protected $casts = [
        'category' => ExpenseCategory::class,
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }
}
