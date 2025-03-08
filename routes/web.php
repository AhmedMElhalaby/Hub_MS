<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Route::get('settings/general', App\Livewire\Settings\SettingsList::class)->name('settings.general');

    Route::get('/users', App\Livewire\Users\UsersList::class)->name('users.index');
    Route::get('/users/{user}', App\Livewire\Users\UserDetails::class)->name('users.show');
    Route::get('/finances', App\Livewire\Finances\FinancesList::class)->name('finances.index');
    Route::get('/notifications', App\Livewire\Notifications\NotificationsList::class)->name('notifications.index');
});

// Add this route with your other authenticated routes
require __DIR__.'/auth.php';
