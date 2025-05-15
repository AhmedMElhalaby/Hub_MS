<?php

namespace App\Jobs;

use App\Enums\BookingStatus;
use App\Models\Workspace;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckWorkspaceStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get all workspaces that are currently marked as booked
            $bookedWorkspaces = Workspace::all();

            $count = 0;
            $count1 = 0;
            foreach ($bookedWorkspaces as $workspace) {
                // Check if there are any active bookings for this workspace
                $hasActiveBookings = $workspace->bookings()
                    ->whereIn('status', [BookingStatus::Draft, BookingStatus::Confirmed])
                    ->where(function ($query) {
                        $query->where('ended_at', '>', now())
                            ->orWhereNull('ended_at');
                    })
                    ->exists();

                // If no active bookings, mark workspace as available
                if (!$hasActiveBookings) {
                    $workspace->markAsAvailable();
                    $count++;
                    Log::info("Workspace {$workspace->desk} marked as available due to no active bookings");
                }else{
                    $workspace->markAsBooked();
                    $count1++;
                    Log::info("Workspace {$workspace->desk} marked as booked due to active bookings");
                }
            }

            Log::info("Workspace status check completed. {$count} workspaces marked as available.");
            Log::info("Workspace status check completed. {$count1} workspaces marked as booked.");
        } catch (\Exception $e) {
            Log::error("Error checking workspace status: " . $e->getMessage());
        }
    }
}
