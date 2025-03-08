<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class MikrotikService
{
    protected $client;

    public function __construct()
    {
        $config = new Config([
            'host' => config('mikrotik.host'),
            'user' => config('mikrotik.user'),
            'pass' => config('mikrotik.password'),
            'port' => (int) config('mikrotik.port', 8728),
        ]);

        $this->client = new Client($config);
    }

    public function getHotspotProfiles()
    {
        $query = new Query('/ip/hotspot/user/profile/print');
        return $this->client->query($query)->read();
    }

    public function createHotspotUser(string $username, string $password, array $limits)
    {
        $query = new Query('/ip/hotspot/user/add');
        $query->equal('name', $username);
        $query->equal('password', $password);
        $query->equal('profile', $limits['profile'] ?? 'default');
        $query->equal('limit-uptime', $limits['uptime'] ?? '0');
        $query->equal('limit-bytes-total', $limits['bytes_total'] ?? '0');

        return $this->client->query($query)->read();
    }

    public function removeHotspotUser(string $username)
    {
        // First, find the user's ID
        $findQuery = new Query('/ip/hotspot/user/print');
        $findQuery->where('name', $username);
        $user = $this->client->query($findQuery)->read();

        if (!empty($user)) {
            // Remove the user using their .id
            $removeQuery = new Query('/ip/hotspot/user/remove');
            $removeQuery->equal('.id', $user[0]['.id']);
            return $this->client->query($removeQuery)->read();
        }

        return false;
    }

    public function createHotspotProfile(string $name, array $settings)
    {
        $query = new Query('/ip/hotspot/user/profile/add');
        $query->equal('name', $name);
        $query->equal('rate-limit', $settings['rate_limit'] ?? '0/0');
        $query->equal('shared-users', $settings['shared_users'] ?? '1');
        $query->equal('session-timeout', $settings['session_timeout'] ?? '0');

        return $this->client->query($query)->read();
    }

    public function removeHotspotProfile(string $name)
    {
        $query = new Query('/ip/hotspot/user/profile/remove');
        $query->where('name', $name);

        return $this->client->query($query)->read();
    }

    public function getHotspotProfileByName(string $name)
    {
        $query = new Query('/ip/hotspot/user/profile/print');
        $query->where('name', $name);

        return $this->client->query($query)->read();
    }
}
