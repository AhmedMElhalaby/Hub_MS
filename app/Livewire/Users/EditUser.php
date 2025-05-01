<?php

namespace App\Livewire\Users;

use App\Repositories\UserRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class EditUser extends Component
{
    use WithModal, NotificationService;

    public $userId;
    public $name = '';
    public $email = '';
    public $password = '';

    protected UserRepository $userRepository;

    public function boot(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => 'nullable|min:8',
        ]);

        try {
            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $data['password'] = $validated['password'];
            }

            $this->userRepository->update($this->userId, $data);
            $this->reset();
            $this->closeModal();
            $this->dispatch('user-updated');
            $this->notifySuccess('messages.user.updated');
        } catch (\Exception $e) {
            $this->notifyError('messages.user.save_error');
        }
    }

    public function render()
    {
        return view('livewire.users.edit-user');
    }

    #[On('open-edit-user')]
    public function open($userId)
    {
        $user = $this->userRepository->findById($userId);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->openModal();
    }
}
