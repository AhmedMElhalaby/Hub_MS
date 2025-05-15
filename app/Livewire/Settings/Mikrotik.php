<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;

class Mikrotik extends Component
{
    public bool $showScript = false;
    public bool $mikrotikEnabled = false;

    public function mount()
    {
        $this->mikrotikEnabled = (bool) Setting::get('mikrotik_enabled', false);
    }

    public function save()
    {
        $this->validate([
            'mikrotikHost' => 'required_if:mikrotikEnabled,true',
        ]);

        Setting::set('mikrotik_enabled', $this->mikrotikEnabled);

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.settings.mikrotik');
    }
}
