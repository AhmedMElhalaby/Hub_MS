<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;

class Sms extends Component
{
    public $sms_settings = [
        'sms_enabled' => false,
        'sms_username' => '',
        'sms_password' => '',
        'sms_sender_id' => '',
    ];

    public function mount()
    {
        $this->sms_settings['sms_enabled'] = (bool) Setting::get('sms_enabled', false);
        $this->sms_settings['sms_username'] = Setting::get('sms_username', '');
        $this->sms_settings['sms_password'] = Setting::get('sms_password', '');
        $this->sms_settings['sms_sender_id'] = Setting::get('sms_sender_id', '');
    }

    public function save()
    {
        $this->validate([
            'sms_settings.sms_username' => 'required_if:sms_settings.sms_enabled,true',
            'sms_settings.sms_password' => 'required_if:sms_settings.sms_enabled,true',
            'sms_settings.sms_sender_id' => 'required_if:sms_settings.sms_enabled,true',
        ]);

        foreach ($this->sms_settings as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.settings.sms');
    }
}
