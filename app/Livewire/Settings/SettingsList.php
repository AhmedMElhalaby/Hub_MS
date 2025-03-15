<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class SettingsList extends Component
{
    use WithFileUploads;

    public $appName;
    public $appLogo;
    public $appFavicon;
    public $primaryColor;
    public $secondaryColor;
    public $mikrotikEnabled = false;
    public $mikrotikHost;
    public $mikrotikUser;
    public $mikrotikPassword;
    public $mikrotikPort;
    // Add these properties
    public $sms_settings = [
        'sms_enabled' => false,
        'sms_username' => '',
        'sms_password' => '',
        'sms_sender_id' => '',
    ];

    protected $casts = [
        'mikrotikEnabled' => 'boolean',
    ];

    public function mount()
    {
        $this->appName = Setting::get('app_name', config('app.name'));
        $this->primaryColor = Setting::get('primary_color', '#4f46e5');
        $this->secondaryColor = Setting::get('secondary_color', '#1f2937');
        $this->mikrotikEnabled = (bool) Setting::get('mikrotik_enabled', false);
        $this->mikrotikHost = Setting::get('mikrotik_host');
        $this->mikrotikUser = Setting::get('mikrotik_user');
        $this->mikrotikPort = Setting::get('mikrotik_port', '8728');
        // Add this to your existing mount method
        $this->sms_settings = [
            'sms_enabled' => (bool) Setting::get('sms_enabled', false),
            'sms_username' => Setting::get('sms_username', ''),
            'sms_password' => Setting::get('sms_password', ''),
            'sms_sender_id' => Setting::get('sms_sender_id', ''),
        ];
    }

    public function updatedAppLogo()
    {
        $this->validate([
            'appLogo' => 'image|max:1024',
        ]);
    }

    public function updatedAppFavicon()
    {
        $this->validate([
            'appFavicon' => 'mimes:ico|max:1024',
        ]);
    }

    public function saveGeneral()
    {
        $this->validate([
            'appName' => 'required|string|max:255',
            'appLogo' => 'nullable|image|max:1024',
            'appFavicon' => 'nullable|mimes:ico|max:1024',
            'primaryColor' => 'required|string',
            'secondaryColor' => 'required|string',
        ]);

        Setting::set('app_name', $this->appName);
        Setting::set('primary_color', $this->primaryColor);
        Setting::set('secondary_color', $this->secondaryColor);

        if ($this->appLogo) {
            $logoPath = $this->appLogo->storeAs('logos', 'app-logo.' . $this->appLogo->getClientOriginalExtension(), 'public');
            Setting::set('app_logo', $logoPath);
        }

        if ($this->appFavicon) {
            $faviconPath = $this->appFavicon->storeAs('favicons', 'favicon.' . $this->appFavicon->getClientOriginalExtension(), 'public');
            Setting::set('app_favicon', $faviconPath);
        }

        session()->flash('message', __('General settings updated successfully.'));
        $this->reset(['appLogo', 'appFavicon']);
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function updatedMikrotikEnabled($value)
    {
        $this->mikrotikEnabled = $value;
        if (!$value) {
            $this->mikrotikHost = null;
            $this->mikrotikUser = null;
            $this->mikrotikPassword = null;
            $this->mikrotikPort = '8728';
        }
    }

    public function saveMikrotik()
    {
        Setting::set('mikrotik_enabled', $this->mikrotikEnabled);

        if ($this->mikrotikEnabled) {
            $this->validate([
                'mikrotikHost' => 'required',
                'mikrotikUser' => 'required',
                'mikrotikPassword' => 'required',
                'mikrotikPort' => 'required|numeric',
            ]);

            Setting::set('mikrotik_host', $this->mikrotikHost);
            Setting::set('mikrotik_user', $this->mikrotikUser);
            Setting::set('mikrotik_password', $this->mikrotikPassword);
            Setting::set('mikrotik_port', $this->mikrotikPort);
        } else {
            Setting::set('mikrotik_host', null);
            Setting::set('mikrotik_user', null);
            Setting::set('mikrotik_password', null);
            Setting::set('mikrotik_port', '8728');
        }
        session()->flash('message', __('Mikrotik settings updated successfully.'));
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.settings.settings-list');
    }

    // Add save method for SMS settings
    public function saveSmsSettings()
    {
        foreach ($this->sms_settings as $key => $value) {
            Setting::set($key, $value);
        }

        session()->flash('message', __('SMS settings saved successfully.'));
        $this->redirect(request()->header('Referer'), navigate: true);
    }
}
