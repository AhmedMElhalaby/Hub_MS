<?php

namespace App\Livewire\Workspaces;

use App\Repositories\WorkspaceRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use App\Enums\WorkspaceStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules\Enum;

#[Layout('components.layouts.app')]
class WorkspacesList extends Component
{
    use WithPagination, WithSorting, WithModal, NotificationService;

    public $desk = '';
    public $status = '';
    public $workspaceId;
    public $statusFilter = '';

    protected WorkspaceRepository $workspaceRepository;

    public function boot(WorkspaceRepository $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    protected function getQueryString(): array
    {
        return array_merge(parent::getQueryString(), [
            'statusFilter' => ['except' => ''],
        ]);
    }

    public function resetForm()
    {
        $this->reset(['workspaceId', 'desk', 'status']);
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit($workspaceId)
    {
        $workspace = $this->workspaceRepository->findById($workspaceId);
        $this->workspaceId = $workspace->id;
        $this->desk = $workspace->desk;
        $this->status = $workspace->status;
        $this->openModal();
    }

    public function confirmDelete($workspaceId)
    {
        $this->workspaceId = $workspaceId;
        $this->openDeleteModal();
    }

    public function save()
    {
        $validated = $this->validate([
            'desk' => 'required|numeric',
            'status' => ['required', new Enum(WorkspaceStatus::class)],
        ]);

        try {
            if ($this->workspaceId) {
                $this->workspaceRepository->update($this->workspaceId, $validated);
            } else {
                $this->workspaceRepository->create($validated);
            }

            $this->notifySuccess('messages.workspace.saved');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.workspace.save_error');
        }
    }

    public function delete()
    {
        try {
            $this->workspaceRepository->delete($this->workspaceId);
            $this->notifySuccess('messages.workspace.deleted');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.workspace.delete_error');
        }
    }

    public function render()
    {
        return view('livewire.workspaces.workspaces-list', [
            'workspaces' => $this->workspaceRepository->getAllPaginated(
                $this->search,
                $this->sortField,
                $this->sortDirection,
                $this->perPage
            ),
            'statuses' => WorkspaceStatus::cases()
        ]);
    }
}
