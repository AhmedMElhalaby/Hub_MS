<?php

namespace App\Livewire\Workspaces;

use App\Models\Workspace;
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
    use WithPagination, WithSorting, WithModal;

    public $desk = '';
    public $status = '';
    public $workspaceId;
    public $statusFilter = '';

    protected function getQueryString(): array
    {
        return array_merge(parent::getQueryString(), [
            'statusFilter' => ['except' => ''],
        ]);
    }
    public function resetForm()
    {
        $this->reset([
            'workspaceId',
            'desk',
            'status',
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit(Workspace $workspace)
    {
        $this->workspaceId = $workspace->id;
        $this->desk = $workspace->desk;
        $this->status = $workspace->status;
        $this->openModal();
    }

    public function confirmDelete(Workspace $workspace)
    {
        $this->workspaceId = $workspace->id;
        $this->openDeleteModal();
    }

    public function save()
    {
        $this->validate([
            'desk' => 'required|numeric',
            'status' => ['required', new Enum(WorkspaceStatus::class)],
        ]);

        if ($this->workspaceId) {
            $Workspace = Workspace::findOrFail($this->workspaceId);
            $Workspace->update([
                'desk' => $this->desk,
                'status' => $this->status,
            ]);
        } else {
            Workspace::create([
                'desk' => $this->desk,
                'status' => $this->status,
            ]);
        }

        session()->flash('message', __('Workspace saved successfully.'));
        $this->closeModal();
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function delete()
    {
        try {
            $workspace = Workspace::findOrFail($this->workspaceId);
            $workspace->delete();
            session()->flash('message', __('Workspace deleted successfully.'));
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while deleting the workspace.'));
        }
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.workspaces.workspaces-list', [
            'workspaces' => Workspace::when($this->search, function ($query) {
                $query->where('desk', 'like', '%' . $this->search . '%');
            })
                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage),
            'statuses' => WorkspaceStatus::cases()
        ]);
    }
}
