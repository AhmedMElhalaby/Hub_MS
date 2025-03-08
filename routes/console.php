<?php

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

Artisan::command('bookings:update-status', function () {
    $this->info('Starting to update booking statuses...');
    UpdateBookingStatuses::dispatch();
    $this->info('Booking statuses update job has been dispatched.');
})->purpose('Update booking statuses based on end dates');

