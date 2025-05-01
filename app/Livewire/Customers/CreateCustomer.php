<?php

namespace App\Livewire\Customers;

use App\Repositories\CustomerRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Enums\Specialization;
use Livewire\Attributes\On;

class CreateCustomer extends Component
{
    use WithModal, NotificationService;

    public $customerId = null;
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

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'specialization' => 'required',
        ]);

        try {
            $this->customerRepository->create($validated);
            $this->reset();
            $this->closeModal();
            $this->dispatch('customer-created');
            $this->notifySuccess('messages.customer.created');
        } catch (\Exception $e) {
            $this->notifyError('messages.customer.save_error');
        }
    }

    public function render()
    {
        return view('livewire.customers.create-customer', [
            'specializations' => Specialization::cases()
        ]);
    }

    #[On('open-create-customer')]
    public function open()
    {
        $this->openModal();
    }
}
