<?php

use App\Http\Controllers\TenantRegistrationController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Main domain routes
Route::domain(config('app.url'))->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/register/tenant', [TenantRegistrationController::class, 'create'])
        ->name('register.tenant');

    Route::post('/register/tenant', [TenantRegistrationController::class, 'store'])
        ->name('register.tenant.store');

    // Add tenant routes with prefix on main domain
    Route::prefix('{tenant}')->middleware([\App\Http\Middleware\ResolveTenant::class])->group(function () {
        Route::get('/', function() {
            return redirect()->route('dashboard', ['tenant' => request()->tenant]);
        });

        Route::get('dashboard', App\Http\Controllers\DashboardController::class)
            ->middleware(['auth', 'verified'])
            ->name('dashboard');

        Route::middleware(['auth'])->group(function () {
            Route::redirect('settings', 'settings/profile');
            Route::get('/customers', App\Livewire\Customers\CustomersList::class)->name('customers.index');
            Route::get('/customers/{customer}', App\Livewire\Customers\CustomerDetails::class)->name('customers.show');
            Route::get('/workspaces', \App\Livewire\Workspaces\WorkspacesList::class)->name('workspaces.index');
            Route::get('/workspaces/{workspace}', \App\Livewire\Workspaces\WorkspaceDetails::class)->name('workspaces.show');
            Route::get('/plans', App\Livewire\Plans\PlansList::class)->name('plans.index');
            Route::get('/plans/{plan}', App\Livewire\Plans\PlanDetails::class)->name('plans.show');
            Route::get('/expenses', App\Livewire\Expenses\ExpensesList::class)->name('expenses.index');
            Route::get('/expenses/{expense}', App\Livewire\Expenses\ExpenseDetails::class)->name('expenses.show');
            Route::get('/bookings', App\Livewire\Bookings\BookingsList::class)->name('bookings.index');
            Route::get('/bookings/{booking}', App\Livewire\Bookings\BookingDetails::class)->name('bookings.show');

            Route::get('/settings/profile', App\Livewire\Settings\Profile::class)->name('settings.profile');
            Route::get('/settings/password', App\Livewire\Settings\Password::class)->name('settings.password');
            Route::get('/settings/appearance', App\Livewire\Settings\Appearance::class)->name('settings.appearance');
            Route::get('/settings/general', App\Livewire\Settings\General::class)->name('settings.general');
            Route::get('/settings/mikrotik', App\Livewire\Settings\Mikrotik::class)->name('settings.mikrotik');
            Route::get('/settings/sms', App\Livewire\Settings\Sms::class)->name('settings.sms');

            Route::get('/users', App\Livewire\Users\UsersList::class)->name('users.index');
            Route::get('/users/{user}', App\Livewire\Users\UserDetails::class)->name('users.show');
            Route::get('/finances', App\Livewire\Finances\FinancesList::class)->name('finances.index');
            Route::get('/notifications', App\Livewire\Notifications\NotificationsList::class)->name('notifications.index');
            Route::post('logout', App\Livewire\Actions\Logout::class)->name('logout');
        });
        Volt::route('login', 'auth.login')
            ->name('login');
    });
});

// Subdomain tenant routes
Route::domain('{tenant}.'.config('app.url'))->middleware([\App\Http\Middleware\ResolveTenant::class])->group(function () {
    Route::get('/', function() {
        return redirect()->route('dashboard', ['tenant' => request()->tenant]);
    });

    Route::get('dashboard', App\Http\Controllers\DashboardController::class)
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::redirect('settings', 'settings/profile');
        Route::get('/customers', App\Livewire\Customers\CustomersList::class)->name('customers.index');
        Route::get('/customers/{customer}', App\Livewire\Customers\CustomerDetails::class)->name('customers.show');
        Route::get('/workspaces', \App\Livewire\Workspaces\WorkspacesList::class)->name('workspaces.index');
        Route::get('/workspaces/{workspace}', \App\Livewire\Workspaces\WorkspaceDetails::class)->name('workspaces.show');
        Route::get('/plans', App\Livewire\Plans\PlansList::class)->name('plans.index');
        Route::get('/plans/{plan}', App\Livewire\Plans\PlanDetails::class)->name('plans.show');
        Route::get('/expenses', App\Livewire\Expenses\ExpensesList::class)->name('expenses.index');
        Route::get('/expenses/{expense}', App\Livewire\Expenses\ExpenseDetails::class)->name('expenses.show');
        Route::get('/bookings', App\Livewire\Bookings\BookingsList::class)->name('bookings.index');
        Route::get('/bookings/{booking}', App\Livewire\Bookings\BookingDetails::class)->name('bookings.show');

        Route::get('/settings/profile', App\Livewire\Settings\Profile::class)->name('settings.profile');
        Route::get('/settings/password', App\Livewire\Settings\Password::class)->name('settings.password');
        Route::get('/settings/appearance', App\Livewire\Settings\Appearance::class)->name('settings.appearance');
        Route::get('/settings/general', App\Livewire\Settings\General::class)->name('settings.general');
        Route::get('/settings/mikrotik', App\Livewire\Settings\Mikrotik::class)->name('settings.mikrotik');
        Route::get('/settings/sms', App\Livewire\Settings\Sms::class)->name('settings.sms');

        Route::get('/users', App\Livewire\Users\UsersList::class)->name('users.index');
        Route::get('/users/{user}', App\Livewire\Users\UserDetails::class)->name('users.show');
        Route::get('/finances', App\Livewire\Finances\FinancesList::class)->name('finances.index');
        Route::get('/notifications', App\Livewire\Notifications\NotificationsList::class)->name('notifications.index');
        Route::post('logout', App\Livewire\Actions\Logout::class)->name('logout');
    });
    Volt::route('login', 'auth.login')
        ->name('login');
});

require __DIR__.'/auth.php';
