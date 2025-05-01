<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;

class Mikrotik extends Component
{
    public bool $mikrotikEnabled = false;
    public string $mikrotikHost = '';
    public string $mikrotikUser = '';
    public string $mikrotikPassword = '';
    public int $mikrotikPort = 8728;

    public function mount()
    {
        $this->mikrotikEnabled = (bool) Setting::get('mikrotik_enabled', false);
        $this->mikrotikHost = Setting::get('mikrotik_host', '');
        $this->mikrotikUser = Setting::get('mikrotik_user', '');
        $this->mikrotikPassword = Setting::get('mikrotik_password', '');
        $this->mikrotikPort = (int) Setting::get('mikrotik_port', 8728);
    }

    public function save()
    {
        $this->validate([
            'mikrotikHost' => 'required_if:mikrotikEnabled,true',
            'mikrotikUser' => 'required_if:mikrotikEnabled,true',
            'mikrotikPassword' => 'required_if:mikrotikEnabled,true',
            'mikrotikPort' => 'required_if:mikrotikEnabled,true|integer',
        ]);

        Setting::set('mikrotik_enabled', $this->mikrotikEnabled);

        if ($this->mikrotikEnabled) {
            Setting::set('mikrotik_host', $this->mikrotikHost);
            Setting::set('mikrotik_user', $this->mikrotikUser);
            Setting::set('mikrotik_password', $this->mikrotikPassword);
            Setting::set('mikrotik_port', $this->mikrotikPort);
        }

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.settings.mikrotik');
    }
}
