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
            $this->notifySuccess(__('crud.customers.messages.deleted'));
            $this->dispatch('customer-deleted');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.delete_error', ['model' => __('crud.customers.model.singular')]));
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
