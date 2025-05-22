<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use App\Models\Tenant;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalTenants;
    public $activeTenants;
    public $totalSubscriptions;
    public $activeSubscriptions;
    public $recentTenants;
    public $recentSubscriptions;

    public function mount()
    {
        $this->totalTenants = Tenant::count();
        $this->activeTenants = Tenant::where('active', true)->count();
        $this->totalSubscriptions = Subscription::count();
        $this->activeSubscriptions = Subscription::where('status', 'active')->count();
        $this->recentTenants = Tenant::latest()->take(5)->get();
        $this->recentSubscriptions = Subscription::with(['tenant', 'plan'])->latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'totalTenants' => $this->totalTenants,
            'activeTenants' => $this->activeTenants,
            'totalSubscriptions' => $this->totalSubscriptions,
            'activeSubscriptions' => $this->activeSubscriptions,
            'recentTenants' => $this->recentTenants,
            'recentSubscriptions' => $this->recentSubscriptions,
        ]);
    }
}
