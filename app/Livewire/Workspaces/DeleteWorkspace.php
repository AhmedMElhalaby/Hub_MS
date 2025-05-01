<?php

namespace App\Livewire\Workspaces;

use App\Repositories\WorkspaceRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class DeleteWorkspace extends Component
{
    use WithModal, NotificationService;

    public $workspaceId;

    protected WorkspaceRepository $workspaceRepository;

    public function boot(WorkspaceRepository $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    public function delete()
    {
        try {
            $this->workspaceRepository->delete($this->workspaceId);
            $this->notifySuccess('messages.workspace.deleted');
            $this->dispatch('workspace-deleted');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.workspace.delete_error');
        }
    }

    public function render()
    {
        return view('livewire.workspaces.delete-workspace');
    }

    #[On('open-delete-workspace')]
    public function open($workspaceId)
    {
        $this->workspaceId = $workspaceId;
        $this->openModal();
    }
}
