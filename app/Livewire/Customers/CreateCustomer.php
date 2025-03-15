<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use App\Traits\WithModal;
use Illuminate\Support\Facades\DB;
use App\Enums\Specialization;
use Livewire\Attributes\On;

class CreateCustomer extends Component
{
    use WithModal;

    public $customerId = null;
    public $name = '';
    public $email = '';
    public $mobile = '';
    public $address = '';
    public $specialization = '';
    public $showModal = false;

    #[On('edit-customer')]
    public function edit($customerId)
    {
        $customer = Customer::find($customerId);
        $this->customerId = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->mobile = $customer->mobile;
        $this->address = $customer->address;
        $this->specialization = $customer->specialization->value;
        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'specialization' => 'required',
        ]);

        try {
            DB::beginTransaction();

            if ($this->customerId) {
                $customer = Customer::find($this->customerId);
                $customer->update($validated);
            } else {
                $customer = Customer::create($validated);
            }

            DB::commit();

            $this->dispatch('customer-created', customerId: $customer->id);
            $this->reset();
            $this->showModal = false;

            session()->flash('message', __('Customer ' . ($this->customerId ? 'updated' : 'created') . ' successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', __('Failed to save customer.'));
        }
        $this->redirect(request()->header('Referer'), navigate: true);
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
        $this->showModal = true;
    }
}
