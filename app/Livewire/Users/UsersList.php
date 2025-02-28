<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class UsersList extends Component
{
    use WithPagination, WithSorting, WithModal;

    public $name = '';
    public $email = '';
    public $password = '';
    public $userId;

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit(User $user)
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->openModal();
    }

    public function confirmDelete(User $user)
    {
        $this->userId = $user->id;
        $this->openDeleteModal();
    }

    public function resetForm()
    {
        $this->reset([
            'userId',
            'name',
            'email',
            'password',
        ]);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => $this->userId ? 'nullable|min:8' : 'required|min:8',
        ]);

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            if ($this->password) {
                $user->update(['password' => bcrypt($this->password)]);
            }
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);
        }

        session()->flash('message', __('User saved successfully.'));
        $this->closeModal();
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function delete()
    {
        try {
            $user = User::findOrFail($this->userId);
            $user->delete();
            session()->flash('message', __('User deleted successfully.'));
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while deleting the user.'));
        }
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.users.users-list', [
            'users' => User::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
