<?php

namespace App\Livewire\Users;

use App\Repositories\UserRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class UsersList extends Component
{
    use WithPagination, WithSorting, WithModal, NotificationService;

    public $name = '';
    public $email = '';
    public $password = '';
    public $userId;

    protected UserRepository $userRepository;

    public function boot(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit($userId)
    {
        $user = $this->userRepository->findById($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->openModal();
    }

    public function confirmDelete($userId)
    {
        $this->userId = $userId;
        $this->openDeleteModal();
    }

    public function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'password']);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => $this->userId ? 'nullable|min:8' : 'required|min:8',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        if ($this->userId) {
            $this->userRepository->update($this->userId, $data);
        } else {
            $this->userRepository->create($data);
        }

        $this->notifySuccess('messages.user.saved');
        $this->closeModal();
    }

    public function delete()
    {
        try {
            $this->userRepository->delete($this->userId);
            $this->notifySuccess('messages.user.deleted');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.user.delete_error');
        }
    }

    public function render()
    {
        return view('livewire.users.users-list', [
            'users' => $this->userRepository->getAllPaginated(
                $this->search,
                $this->sortField,
                $this->sortDirection,
                $this->perPage
            ),
        ]);
    }
}
