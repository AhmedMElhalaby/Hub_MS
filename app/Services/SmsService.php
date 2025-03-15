<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
class SmsService
{
    protected $baseUrl = 'http://int.mtcsms.com/sendsms.aspx';

    public function send($to, $message)
    {
        try {
            $response = Http::get($this->baseUrl, [
                'username' => Setting::get('sms_username'),
                'password' => Setting::get('sms_password'),
                'from' => Setting::get('sms_sender_id'),
                'to' => $to,
                'msg' => $message,
                'type' => '0'
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SMS sending failed', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }
}
