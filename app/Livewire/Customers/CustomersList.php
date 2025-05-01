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
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class CustomersList extends Component
{
    use WithPagination, WithSorting, WithModal, NotificationService;

    public $specializationFilter = '';

    protected CustomerRepository $customerRepository;

    public function boot(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function render()
    {
        return view('livewire.customers.customers-list', [
            'customers' => $this->customerRepository->getAllPaginated(
                [
                    'q'=>$this->search,
                    'specialization'=>$this->specializationFilter
                ],
                $this->sortField,
                $this->sortDirection,
                $this->perPage,
            ),
            'specializations' => Specialization::cases()
        ]);
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }
}
