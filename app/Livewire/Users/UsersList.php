<?php

namespace App\Livewire\Users;

use App\Repositories\UserRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class UsersList extends Component
{
    use WithPagination, WithSorting, WithModal, NotificationService;

    protected UserRepository $userRepository;

    public function boot(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }
}
