<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class UserDetails extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.users.user-details');
    }
}
