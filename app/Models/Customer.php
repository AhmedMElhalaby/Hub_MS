<?php

namespace App\Models;

use App\Enums\Specialization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'address',
        'specialization'
    ];

    protected $casts = [
        'specialization' => Specialization::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
