<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\MikrotikService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RemoveHotspotUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Booking $booking
    ) {}

    public function handle(): void
    {
        if ($this->booking->hotspot_username) {
            try {
                $mikrotik = new MikrotikService();
                Log::info($this->booking->hotspot_username);
                $mikrotik->removeHotspotUser($this->booking->hotspot_username);
            } catch (\Exception $e) {
                Log::error('Failed to remove hotspot user', [
                    'booking_id' => $this->booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
