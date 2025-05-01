<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class General extends Component
{
    use WithFileUploads;

    public $appName;

    public function mount()
    {
        $this->appName = Setting::get('app_name');
    }

    public function save()
    {
        $this->validate([
            'appName' => 'required|string|max:255',
        ]);

        Setting::set('app_name', $this->appName);

        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        return view('livewire.settings.general');
    }
}
