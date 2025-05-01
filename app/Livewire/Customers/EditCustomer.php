<?php

namespace App\Livewire\Customers;

use App\Repositories\CustomerRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Enums\Specialization;
use Livewire\Attributes\On;

class EditCustomer extends Component
{
    use WithModal, NotificationService;
    public $customerId;
    public $name = '';
    public $email = '';
    public $mobile = '';
    public $address = '';
    public $specialization = '';

    protected CustomerRepository $customerRepository;

    public function boot(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'specialization' => 'required',
        ]);

        try {
            $this->customerRepository->update($this->customerId, $validated);
            $this->reset();
            $this->closeModal();
            $this->dispatch('customer-updated');
            $this->notifySuccess('messages.customer.updated');
        } catch (\Exception $e) {
            $this->notifyError('messages.customer.save_error');
        }
    }

    public function render()
    {
        return view('livewire.customers.edit-customer', [
            'specializations' => Specialization::cases()
        ]);
    }

    #[On('open-edit-customer')]
    public function open($customerId)
    {
        $customer = $this->customerRepository->findById($customerId);
        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->mobile = $customer->mobile;
        $this->address = $customer->address;
        $this->specialization = $customer->specialization->value;
        $this->openModal();
    }
}
