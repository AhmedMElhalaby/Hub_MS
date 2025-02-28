<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class CustomerDetails extends Component
{
    public Customer $customer;

    public function mount(Customer $customer)
    {
        $this->customer = $customer->load(['bookings.finances']);
    }

    public function getTotalPaymentsProperty()
    {
        return $this->customer->bookings->sum('total');
    }

    public function render()
    {
        return view('livewire.customers.customer-details');
    }
}
