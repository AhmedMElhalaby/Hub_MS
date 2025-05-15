<?php

namespace App\Livewire\Workspaces;

use App\Repositories\WorkspaceRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Enums\WorkspaceStatus;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\On;

class CreateWorkspace extends Component
{
    use WithModal, NotificationService;

    public $desk = '';
    public $status = '';

    protected WorkspaceRepository $workspaceRepository;

    public function boot(WorkspaceRepository $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    public function store()
    {
        $validated = $this->validate([
            'desk' => 'required|numeric',
            'status' => ['required', new Enum(WorkspaceStatus::class)],
        ]);

        try {
            $this->workspaceRepository->create($validated);
            $this->reset();
            $this->closeModal();
            $this->dispatch('workspace-created');
            $this->notifySuccess(__('crud.workspaces.messages.created'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.workspaces.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.workspaces.create-workspace', [
            'statuses' => WorkspaceStatus::cases()
        ]);
    }

    #[On('open-create-workspace')]
    public function open()
    {
        $this->openModal();
    }
}
