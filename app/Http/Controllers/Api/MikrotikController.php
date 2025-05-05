<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\MikrotikProfile;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MikrotikController extends ApiController
{
    protected function getTenantFromApiKey(): ?Tenant
    {
        $apiKey = request()->header('X-API-Key');
        if (!$apiKey) {
            return null;
        }
        return Tenant::where('api_key', $apiKey)->first();
    }

    public function getPendingCredentials(): JsonResponse
    {
        $tenant = $this->getTenantFromApiKey();
        if (!$tenant) {
            return response()->json(['message' => 'Invalid API key'], 401);
        }

        $bookings = Booking::where('hotspot_is_created', false)
            ->where('tenant_id', $tenant->id)
            ->whereNotNull('hotspot_username')
            ->whereNotNull('hotspot_password')
            ->with(['plan'])
            ->get()
            ->map(function ($booking) {
                return [
                    'username' => $booking->hotspot_username,
                    'password' => $booking->hotspot_password,
                    'profile' => $booking->plan->mikrotik_profile ?? 'default'
                ];
            });
            $output = collect($bookings)
            ->map(fn ($u) => "{$u['username']}:{$u['password']}:{$u['profile']}")
            ->implode('|');

        return response()->json($output);
    }

    public function updateHotspotStatus(): JsonResponse
    {
        $tenant = $this->getTenantFromApiKey();
        if (!$tenant) {
            return response()->json(['message' => 'Invalid API key'], 401);
        }

        $username = request()->input('username');
        if (!$username) {
            return response()->json(['message' => 'Hotspot username is required'], 422);
        }

        $booking = Booking::where('tenant_id', $tenant->id)
            ->where('hotspot_username', $username)
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        try {
            $booking->update(['hotspot_is_created' => true]);
            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to update hotspot status', [
                'booking_id' => $booking->id,
                'username' => $username,
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Failed to update status'], 500);
        }
    }

    public function syncProfiles(): JsonResponse
    {
        $tenant = $this->getTenantFromApiKey();
        if (!$tenant) {
            return response()->json(['message' => 'Invalid API key'], 401);
        }

        $profiles = request()->input('profiles');
        if (!$profiles || !is_array($profiles)) {
            return response()->json(['message' => 'Profiles array is required'], 422);
        }

        try {
            // Get all profile names from the request
            $profileNames = collect($profiles)->pluck('name')->toArray();

            // Delete profiles that don't exist in the request
            MikrotikProfile::where('tenant_id', $tenant->id)
                ->whereNotIn('name', $profileNames)
                ->delete();

            // Create or update existing profiles
            foreach ($profiles as $profile) {
                if (!isset($profile['name'])) {
                    continue;
                }

                MikrotikProfile::updateOrCreate(
                    [
                        'name' => $profile['name'],
                        'tenant_id' => $tenant->id
                    ],
                    ['tenant_id' => $tenant->id]
                );
            }

            return response()->json([
                'message' => 'Profiles synchronized successfully',
                'profiles' => $profiles,
                'deleted_count' => MikrotikProfile::where('tenant_id', $tenant->id)->whereNotIn('name', $profileNames)->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to sync Mikrotik profiles', [
                'tenant_id' => $tenant->id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Failed to sync profiles'], 500);
        }
    }
}
