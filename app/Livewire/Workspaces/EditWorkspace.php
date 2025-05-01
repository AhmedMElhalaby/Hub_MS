<?php

namespace App\Livewire\Workspaces;

use App\Repositories\WorkspaceRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Enums\WorkspaceStatus;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\On;

class EditWorkspace extends Component
{
    use WithModal, NotificationService;

    public $workspaceId;
    public $desk = '';
    public $status = '';

    protected WorkspaceRepository $workspaceRepository;

    public function boot(WorkspaceRepository $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    public function update()
    {
        $validated = $this->validate([
            'desk' => 'required|numeric',
            'status' => ['required', new Enum(WorkspaceStatus::class)],
        ]);

        try {
            $this->workspaceRepository->update($this->workspaceId, $validated);
            $this->reset();
            $this->closeModal();
            $this->dispatch('workspace-updated');
            $this->notifySuccess('messages.workspace.updated');
        } catch (\Exception $e) {
            $this->notifyError('messages.workspace.save_error');
        }
    }

    public function render()
    {
        return view('livewire.workspaces.edit-workspace', [
            'statuses' => WorkspaceStatus::cases()
        ]);
    }

    #[On('open-edit-workspace')]
    public function open($workspaceId)
    {
        $workspace = $this->workspaceRepository->findById($workspaceId);
        $this->workspaceId = $workspace->id;
        $this->desk = $workspace->desk;
        $this->status = $workspace->status;
        $this->openModal();
    }
}
