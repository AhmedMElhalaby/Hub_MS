<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\MikrotikService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ResetHotspotUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Booking $booking,
    ) {}

    public function handle(): void
    {

        try {
            $mikrotik = new MikrotikService();
            $mikrotik->resetUserCounters($this->booking->hotspot_username);
        } catch (\Exception $e) {
            Log::error('Failed to update hotspot user', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
