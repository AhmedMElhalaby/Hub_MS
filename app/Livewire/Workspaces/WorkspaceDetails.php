<?php

namespace App\Livewire\Workspaces;

use App\Models\Workspace;
use App\Repositories\WorkspaceRepository;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class WorkspaceDetails extends Component
{
    use NotificationService;

    public $workspace;
    protected WorkspaceRepository $workspaceRepository;

    public function boot(WorkspaceRepository $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    public function mount(Workspace $workspace)
    {
        try {
            $this->workspace = $this->workspaceRepository->findWithBookings($workspace->id);
        } catch (\Exception $e) {
            $this->notifyError('messages.workspace.not_found');
            return $this->redirect(tenant_route('workspaces.index'));
        }
    }

    public function render()
    {
        return view('livewire.workspaces.workspace-details');
    }
}
