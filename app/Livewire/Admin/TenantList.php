<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;

class TenantList extends Component
{
    use WithPagination;

    public string $searchName = '';
    public string $searchDomain = '';
    public string $filterStatus = '';

    protected $queryString = [
        'searchName' => ['except' => ''],
        'searchDomain' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearchName()
    {
        $this->resetPage();
    }

    public function updatingSearchDomain()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tenants = Tenant::query()
            ->when($this->searchName, function ($query) {
                $query->where('name', 'like', '%' . $this->searchName . '%');
            })
            ->when($this->searchDomain, function ($query) {
                $query->where('domain', 'like', '%' . $this->searchDomain . '%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('active', $this->filterStatus === 'active' ? 1 : 0);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.tenant-list', [
            'tenants' => $tenants,
        ]);
    }

    public function activateTenant($tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if ($tenant) {
            $tenant->active = true;
            $tenant->save();
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Tenant activated successfully.']);
        }
    }

    public function deactivateTenant($tenantId)
    {
        $tenant = Tenant::find($tenantId);
        if ($tenant) {
            $tenant->active = false;
            $tenant->save();
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Tenant deactivated successfully.']);
        }
    }
}
