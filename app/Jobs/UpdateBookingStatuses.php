<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Enums\BookingStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\DB;

class UpdateBookingStatuses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            // Find confirmed bookings that have ended
            $expiredBookings = Booking::query()
                ->where('status', BookingStatus::Confirmed)
                ->where('ended_at', '<=', now())
                ->get();
            Log::info('Found ' . count($expiredBookings) . ' expired bookings to process');

            foreach ($expiredBookings as $booking) {
                try {
                    DB::beginTransaction();

                    $booking->update(['status' => BookingStatus::Completed]);
                    $booking->workspace->markAsAvailable();

                    DB::commit();

                    Log::info("Successfully processed booking #{$booking->id}", [
                        'booking_id' => $booking->id,
                        'workspace' => $booking->workspace->desk,
                        'end_date' => $booking->ended_at
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Failed to process booking #{$booking->id}", [
                        'error' => $e->getMessage(),
                        'booking_id' => $booking->id
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to process expired bookings', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
