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

    public $name = '';
    public $email = '';
    public $mobile = '';
    public $address = '';
    public $specialization = '';
    public $customerId;
    public $specializationFilter = '';

    public function resetForm()
    {
        $this->reset([
            'customerId',
            'name',
            'email',
            'mobile',
            'address',
            'specialization',
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit(Customer $customer)
    {
        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->mobile = $customer->mobile;
        $this->address = $customer->address;
        $this->specialization = $customer->specialization->value;
        $this->openModal();
    }

    public function confirmDelete(Customer $customer)
    {
        $this->customerId = $customer->id;
        $this->openDeleteModal();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $this->customerId,
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'specialization' => 'required',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'specialization' => $this->specialization,
        ];

        if ($this->customerId) {
            Customer::findOrFail($this->customerId)->update($data);
        } else {
            Customer::create($data);
        }

        session()->flash('message', __('Customer saved successfully.'));
        $this->closeModal();
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function delete()
    {
        try {
            $customer = Customer::findOrFail($this->customerId);
            $customer->delete();
            session()->flash('message', __('Customer deleted successfully.'));
            $this->closeDeleteModal();
        } catch (\Exception $e) {
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
