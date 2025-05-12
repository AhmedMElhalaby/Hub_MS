<?php

namespace App\Livewire\Workspaces;

use App\Repositories\WorkspaceRepository;
use App\Services\NotificationService;
use App\Traits\WithSorting;
use App\Enums\WorkspaceStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class WorkspacesList extends Component
{
    use WithPagination, WithSorting, NotificationService;

    public $statusFilter = '';
    public $search = '';

    protected WorkspaceRepository $workspaceRepository;

    public function boot(WorkspaceRepository $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    public function mount()
    {
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }
    
    protected function getQueryString(): array
    {
        return array_merge(parent::getQueryString(), [
            'statusFilter' => ['except' => ''],
        ]);
    }

    public function render()
    {
        return view('livewire.workspaces.workspaces-list', [
            'workspaces' => $this->workspaceRepository->getAllPaginated(
                [
                    'q'=>$this->search,
                    'status'=>$this->statusFilter
                ],
                $this->sortField,
                $this->sortDirection,
                $this->perPage
            ),
            'statuses' => WorkspaceStatus::cases()
        ]);
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }
}
