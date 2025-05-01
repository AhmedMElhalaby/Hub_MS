<?php

namespace App\Livewire\Users;

use App\Repositories\UserRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class CreateUser extends Component
{
    use WithModal, NotificationService;

    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';

    protected UserRepository $userRepository;

    public function boot(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        try {
            $this->userRepository->create($validated);
            $this->reset();
            $this->closeModal();
            $this->dispatch('user-created');
            $this->notifySuccess('messages.user.created');
        } catch (\Exception $e) {
            $this->notifyError('messages.user.save_error');
        }
    }

    public function render()
    {
        return view('livewire.users.create-user');
    }

    #[On('open-create-user')]
    public function open()
    {
        $this->openModal();
    }
}
