<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use App\Enums\Specialization;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class CustomersList extends Component
{
    use WithPagination, WithSorting, WithModal;

    public $customerId;
    public $specializationFilter = '';

    public function edit($customerId)
    {
        $this->dispatch('edit-customer', customerId: $customerId);
    }

    public function confirmDelete(Customer $customer)
    {
        $this->customerId = $customer->id;
        $this->openDeleteModal();
    }

    public function delete()
    {
        try {
            $customer = Customer::findOrFail($this->customerId);
            $customer->delete();
            session()->flash('message', __('Customer deleted successfully.'));
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error', __('An error occurred while deleting the customer.'));
        }
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.customers.customers-list', [
            'customers' => Customer::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            })
                ->when($this->specializationFilter, function ($query) {
                    $query->where('specialization', $this->specializationFilter);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage),
            'specializations' => Specialization::cases()
        ]);
    }
}
