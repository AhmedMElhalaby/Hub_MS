<?php

namespace App\Models;

use App\Traits\HasApiKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasApiKey;
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key',
        'domain',
        'database',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $hidden = [
        'api_key',
    ];

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
