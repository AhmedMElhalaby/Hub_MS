<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\MikrotikService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CreateHotspotUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Booking $booking
    ) {}

    public function handle(): void
    {
        $username = $this->booking->id . '_' . mt_rand(1000, 9999);
        $password = mt_rand(1000, 9999);
        $duration = $this->booking->started_at->diffInSeconds($this->booking->ended_at);
        Log::info('duration : '.$duration);

        // Format duration to include days
        $days = floor($duration / 86400);
        $hours = floor(($duration % 86400) / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;
        $formattedUptime = sprintf('%dd %02d:%02d:%02d', $days, $hours, $minutes, $seconds);
        Log::info('uptime : '.$formattedUptime);
        try {
            $mikrotik = new MikrotikService();
            $mikrotik->createHotspotUser($username, $password, [
                'uptime' => $formattedUptime,
                'profile' => $this->booking->plan->mikrotik_profile ?? 'default',
                'bytes_total' => $this->booking->plan->data_limit ?? '0'
            ]);

            $this->booking->update([
                'hotspot_username' => $username,
                'hotspot_password' => $password
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create hotspot user', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
