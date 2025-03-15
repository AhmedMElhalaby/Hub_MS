<?php

namespace App\Providers;

use App\Services\SmsService;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\Notification;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('components.notification', \App\Livewire\Components\Notification::class);

        Notification::extend('custom-sms', function ($app) {
            return new class {
                public function send($notifiable, $notification)
                {
                    $message = $notification->toSms($notifiable);

                    if (!empty($message['to']) && !empty($message['message'])) {
                        app(SmsService::class)->send($message['to'], $message['message']);
                    }
                }
            };
        });
    }
}
