<?php
namespace App\View\Components;

use App\Models\Setting;
use Illuminate\View\Component;

class AppLayout extends Component
{
    public function render()
    {
        $appName = Setting::get('app_name', config('app.name'));
        $primaryColor = Setting::get('primary_color', '#4f46e5');
        $secondaryColor = Setting::get('secondary_color', '#1f2937');
        $logo = Setting::get('app_logo');
        $favicon = Setting::get('app_favicon');

        return view('layouts.app', compact('appName', 'primaryColor', 'secondaryColor', 'logo', 'favicon'));
    }
}
