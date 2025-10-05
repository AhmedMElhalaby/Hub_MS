<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use BelongsToTenant;
    protected $fillable = ['tenant_id', 'key', 'value', 'group'];

    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $group = 'general')
    {
        static::updateOrCreate(
            ['key' => $key, 'tenant_id' => auth()->user()->tenant_id],
            ['value' => $value, 'group' => $group]
        );
    }
}
