<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;

class SubscriptionList extends Component
{
    use WithPagination;

    public string $filterStatus = '';
    public string $filterTenantId = '';
    public string $filterPlanId = '';

    public $tenants = [];
    public $plans = [];

    protected $queryString = [
        'filterStatus' => ['except' => ''],
        'filterTenantId' => ['except' => ''],
        'filterPlanId' => ['except' => ''],
    ];

    public function mount()
    {
        $this->tenants = Tenant::orderBy('name')->get();
        $this->plans = Plan::orderBy('name')->get();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterTenantId()
    {
        $this->resetPage();
    }

    public function updatingFilterPlanId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $subscriptions = Subscription::with(['tenant', 'plan'])
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterTenantId, function ($query) {
                $query->where('tenant_id', $this->filterTenantId);
            })
            ->when($this->filterPlanId, function ($query) {
                $query->where('plan_id', $this->filterPlanId);
            })
            ->latest('created_at')
            ->paginate(10);

        return view('livewire.admin.subscription-list', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function updateSubscriptionStatus($subscriptionId, $newStatus)
    {
        $subscription = Subscription::find($subscriptionId);

        if (!$subscription) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Subscription not found.']);
            return;
        }

        $allowedStatuses = ['active', 'inactive', 'cancelled'];
        if (!in_array($newStatus, $allowedStatuses)) {
            $this->dispatch('alert', ['type' => 'error', 'message' => 'Invalid status provided.']);
            return;
        }

        $subscription->status = $newStatus;
        $subscription->save();

        $this->dispatch('alert', ['type' => 'success', 'message' => 'Subscription status updated successfully.']);
    }
}
