<?php

namespace App\Livewire\Workspaces;

use App\Models\Workspace;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class WorkspaceDetails extends Component
{
    public Workspace $workspace;

    public function mount(Workspace $workspace)
    {
        $this->workspace = $workspace;
    }

    public function render()
    {
        return view('livewire.workspaces.workspace-details');
    }
}
