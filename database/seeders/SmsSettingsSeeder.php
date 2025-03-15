<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SmsSettingsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        Setting::create([
            'key' => 'sms_enabled',
            'value' => false,
            'type' => 'boolean',
            'group' => 'sms',
        ]);

        Setting::create([
            'key' => 'sms_username',
            'value' => '',
            'type' => 'string',
            'group' => 'sms',
        ]);

        Setting::create([
            'key' => 'sms_password',
            'value' => '',
            'type' => 'string',
            'group' => 'sms',
        ]);

        Setting::create([
            'key' => 'sms_sender_id',
            'value' => '',
            'type' => 'string',
            'group' => 'sms',
        ]);
    }
}
