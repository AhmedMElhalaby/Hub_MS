<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasApiKey
{
    protected static function bootHasApiKey()
    {
        static::creating(function ($model) {
            if (empty($model->api_key)) {
                $model->api_key = static::generateUniqueApiKey();
            }
        });
    }

    protected static function generateUniqueApiKey(): string
    {
        do {
            $key = Str::random(32);
        } while (static::where('api_key', $key)->exists());

        return $key;
    }
}
