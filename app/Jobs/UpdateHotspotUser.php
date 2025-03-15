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

class UpdateHotspotUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Booking $booking,
        protected Carbon $oldEndDate
    ) {}

    public function handle(): void
    {
        // Calculate total hours between start and end dates
        $totalHours = $this->booking->started_at->diffInHours($this->booking->ended_at);

        if ($totalHours < 24) {
            // If less than a day, use actual hours
            $duration = $totalHours * 3600;
        } else {
            // If more than a day, convert days to 8-hour periods
            $totalDays = ceil($totalHours / 24);
            $duration = $totalDays * 8 * 3600;
        }

        // Format duration
        $days = floor($duration / (86400));
        $hours = floor(($duration % (86400)) / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;
        $formattedUptime = sprintf('%dd %02d:%02d:%02d', $days, $hours, $minutes, $seconds);

        try {
            $mikrotik = new MikrotikService();
            $mikrotik->updateHotspotUser($this->booking->hotspot_username, [
                'uptime' => $formattedUptime,
                'profile' => $this->booking->plan->mikrotik_profile ?? 'default',
                'bytes_total' => $this->booking->plan->data_limit ?? '0'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update hotspot user', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
