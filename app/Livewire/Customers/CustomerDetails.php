<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class CustomerDetails extends Component
{
    use NotificationService;

    public $customer;
    protected CustomerRepository $customerRepository;

    public function boot(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function mount(Customer $customer)
    {
        try {
            $this->customer = $this->customerRepository->findWithBookings($customer->id);
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.not_found', ['model' => __('crud.customers.model.singular')]));
            return $this->redirect(route('tenant.customers.index'));
        }
    }

    public function getTotalPaymentsProperty()
    {
        return $this->customer->bookings->sum('total');
    }

    public function render()
    {
        return view('livewire.customers.customer-details');
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }
}
