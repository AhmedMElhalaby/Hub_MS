<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\TenantRegistrationController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

// Main domain routes
Route::domain(config('app.url'))->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/register/tenant', [TenantRegistrationController::class, 'create'])
        ->name('register');

    Route::post('/register/tenant', [TenantRegistrationController::class, 'store'])
        ->name('register.store');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        // Admin Authentication Routes (accessible without admin login)
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login']);

        // Admin Panel Routes (require admin login)
        Route::middleware(['auth.admin'])->group(function () {
            Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
            Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/tenants', [\App\Http\Controllers\Admin\AdminTenantController::class, 'index'])->name('tenants.index');
            Route::get('/tenants/{tenant}', [\App\Http\Controllers\Admin\AdminTenantController::class, 'show'])->name('tenants.show');
            Route::get('/subscriptions', [\App\Http\Controllers\Admin\AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
            Route::get('/subscriptions/{subscription}', [\App\Http\Controllers\Admin\AdminSubscriptionController::class, 'show'])->name('subscriptions.show');
            Route::get('/admin-users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('admin_users.index');
            Route::get('/admin-users/create', [\App\Http\Controllers\Admin\AdminUserController::class, 'create'])->name('admin_users.create');
            Route::get('/admin-users/{admin}/edit', [\App\Http\Controllers\Admin\AdminUserController::class, 'edit'])->name('admin_users.edit');
            // Other admin routes will be added here later
        });
    });
});

Route::domain('{tenant}.'.config('app.url'))->name('tenant.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'create'])
            ->name('login');
        Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'store']);
    });

    Route::middleware([Authenticate::class])->group(function () {
        Route::get('/', App\Http\Controllers\DashboardController::class)->name('home');
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
});
