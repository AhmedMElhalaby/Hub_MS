<?php

use App\Jobs\CheckWorkspaceStatus;
use App\Jobs\UpdateBookingStatuses;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('bookings:update-status')
    ->everyMinute()
    ->withoutOverlapping();

// Schedule::command('workspace:check-status')
//         ->everyMinute()
//         ->withoutOverlapping();

Artisan::command('bookings:update-status', function () {
    $this->info('Starting to update booking statuses...');
    UpdateBookingStatuses::dispatch();
    $this->info('Booking statuses update job has been dispatched.');
})->purpose('Update booking statuses based on end dates');


// Artisan::command('workspace:check-status', function () {
//     $this->info('Starting to checking workspace statuses...');
//     CheckWorkspaceStatus::dispatch();
//     $this->info('Workspace statuses check job has been dispatched.');
// })->purpose('Check Workspace statuses based on active booking');

