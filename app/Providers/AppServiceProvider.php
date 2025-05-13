<?php

namespace App\Providers;

use App\Repositories\CustomerRepository;
use App\Services\SmsService;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\Notification;
use App\Repositories\Interfaces\EloquentRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use App\Repositories\PlanRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\BookingRepository;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EloquentRepositoryInterface::class,CustomerRepository::class);
        $this->app->bind(EloquentRepositoryInterface::class, UserRepository::class);
        $this->app->bind(EloquentRepositoryInterface::class, WorkspaceRepository::class);
        $this->app->bind(EloquentRepositoryInterface::class, PlanRepository::class);
        $this->app->bind(EloquentRepositoryInterface::class, ExpenseRepository::class);
        $this->app->bind(EloquentRepositoryInterface::class, BookingRepository::class);
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
