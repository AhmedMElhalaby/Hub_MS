<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class UserDetails extends Component
{
    use NotificationService;

    public $user;
    protected UserRepository $userRepository;

    public function boot(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function mount(User $user)
    {
        try {
            $this->user = $this->userRepository->findById($user->id);
        } catch (\Exception $e) {
            $this->notifyError('messages.user.not_found');
            return $this->redirect(route('tenant.users.index'));
        }
    }

    public function render()
    {
        return view('livewire.users.user-details');
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }
}
