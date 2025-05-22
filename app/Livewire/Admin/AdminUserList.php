<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;

class AdminUserList extends Component
{
    use WithPagination;

    public string $searchName = '';
    public string $searchEmail = '';
    public string $filterRole = '';
    public string $filterStatus = '';

    protected $queryString = [
        'searchName' => ['except' => ''],
        'searchEmail' => ['except' => ''],
        'filterRole' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearchName()
    {
        $this->resetPage();
    }

    public function updatingSearchEmail()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function getRoles(): array
    {
        return Admin::query()->distinct()->pluck('role')->filter()->sort()->toArray();
    }

    public function render()
    {
        $adminUsers = Admin::query()
            ->when($this->searchName, function ($query) {
                $query->where('name', 'like', '%' . $this->searchName . '%');
            })
            ->when($this->searchEmail, function ($query) {
                $query->where('email', 'like', '%' . $this->searchEmail . '%');
            })
            ->when($this->filterRole, function ($query) {
                $query->where('role', $this->filterRole);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('active', $this->filterStatus === 'active' ? 1 : 0);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.admin-user-list', [
            'adminUsers' => $adminUsers,
            'roles' => $this->getRoles(),
        ]);
    }

    public function activateAdminUser($adminId)
    {
        $admin = Admin::find($adminId);
        if ($admin) {
            $admin->active = true;
            $admin->save();
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Admin user activated.']);
        }
    }

    public function deactivateAdminUser($adminId)
    {
        $admin = Admin::find($adminId);
        if ($admin) {
            // Optional: Prevent deactivating self - for now, omitted as per instructions
            // if (auth('admin')->id() == $adminId) {
            //     $this->dispatch('alert', ['type' => 'error', 'message' => 'You cannot deactivate yourself.']);
            //     return;
            // }
            $admin->active = false;
            $admin->save();
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Admin user deactivated.']);
        }
    }
}
