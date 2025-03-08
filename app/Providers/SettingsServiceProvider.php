<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            // Load settings into config
            $settings = Setting::all();
            foreach ($settings as $setting) {
                config(["settings.{$setting->key}" => $setting->value]);
            }

            // Override mikrotik config if enabled
            if (config('settings.mikrotik_enabled')) {
                config([
                    'mikrotik.host' => config('settings.mikrotik_host'),
                    'mikrotik.user' => config('settings.mikrotik_user'),
                    'mikrotik.password' => config('settings.mikrotik_password'),
                    'mikrotik.port' => config('settings.mikrotik_port'),
                ]);
            }
        } catch (\Exception $e) {
            // Handle database not ready yet
        }
    }
}
