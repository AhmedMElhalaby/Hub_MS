<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use Livewire\Component;

class SubscriptionDetail extends Component
{
    public Subscription $subscription;

    public function mount(Subscription $subscription)
    {
        $this->subscription = $subscription->load(['tenant', 'plan']);
    }

    public function render()
    {
        return view('livewire.admin.subscription-detail');
    }
}
