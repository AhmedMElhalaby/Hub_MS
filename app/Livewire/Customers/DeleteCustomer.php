<?php

namespace App\Livewire\Customers;

use App\Repositories\CustomerRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class DeleteCustomer extends Component
{
    use WithModal, NotificationService;
    public $customerId;

    protected CustomerRepository $customerRepository;

    public function boot(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function delete()
    {
        try {
            $this->customerRepository->delete($this->customerId);
            $this->notifySuccess('messages.customer.deleted');
            $this->dispatch('customer-deleted');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.customer.delete_error');
        }
    }

    public function render()
    {
        return view('livewire.customers.delete-customer');
    }

    #[On('open-delete-customer')]
    public function open($customerId)
    {
        $this->customerId = $customerId;
        $this->openModal();
    }
}
