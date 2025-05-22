<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use Livewire\Component;

class TenantDetail extends Component
{
    public Tenant $tenant;

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant->load('subscriptions.plan', 'settings');
    }

    public function render()
    {
        return view('livewire.admin.tenant-detail');
    }
}
