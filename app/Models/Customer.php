<?php

namespace App\Models;

use App\Enums\Specialization;
use App\Services\SmsService;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory,Notifiable,BelongsToTenant;

    protected $fillable = [
        'tenant_id',
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
    public function sendMessage(string $message)
    {
        (new SmsService())->send($this->mobile, $message);
    }
}
