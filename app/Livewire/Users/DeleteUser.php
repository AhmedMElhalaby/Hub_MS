<?php

namespace App\Livewire\Users;

use App\Repositories\UserRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class DeleteUser extends Component
{
    use WithModal, NotificationService;
    public $userId;

    protected UserRepository $userRepository;

    public function boot(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function delete()
    {
        try {
            $this->userRepository->delete($this->userId);
            $this->notifySuccess(__('crud.users.messages.deleted'));
            $this->dispatch('user-deleted');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.delete_error', ['model' => __('crud.users.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.users.delete-user');
    }

    #[On('open-delete-user')]
    public function open($userId)
    {
        $this->userId = $userId;
        $this->openModal();
    }
}
