<?php

namespace App\Livewire\Plans;

use App\Repositories\PlanRepository;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use App\Enums\PlanType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class PlansList extends Component
{
    use WithPagination, WithSorting, WithModal;

    public $search = '';

    protected PlanRepository $planRepository;

    public function boot(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function render()
    {
        return view('livewire.plans.plans-list', [
            'plans' => $this->planRepository->getAllPaginated(
                $this->search,
                $this->sortField,
                $this->sortDirection,
                $this->perPage
            ),
            'types' => PlanType::cases()
        ]);
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }

    public function mount()
    {
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }
}
