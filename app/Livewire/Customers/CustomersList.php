<?php

namespace App\Livewire\Customers;

use App\Repositories\CustomerRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use App\Enums\Specialization;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class CustomersList extends Component
{
    use WithPagination, WithSorting, WithModal, NotificationService;

    public $customerId;
    public $specializationFilter = '';

    protected CustomerRepository $customerRepository;

    public function boot(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function edit($customerId)
    {
        $this->dispatch('edit-customer', customerId: $customerId);
    }

    public function confirmDelete($customerId)
    {
        $this->customerId = $customerId;
        $this->openDeleteModal();
    }

    public function delete()
    {
        try {
            $this->customerRepository->delete($this->customerId);
            $this->notifySuccess('messages.customer.deleted');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.customer.delete_error');
        }
    }

    public function render()
    {
        return view('livewire.customers.customers-list', [
            'customers' => $this->customerRepository->getAllPaginated(
                $this->search,
                $this->sortField,
                $this->sortDirection,
                $this->perPage
            ),
            'specializations' => Specialization::cases()
        ]);
    }
}
