<?php

namespace App\Livewire\Components;

use Livewire\Component;

class NotificationListener extends Component
{
    public function getListeners()
    {
        return [
            "echo-private:App.Models.User.".auth()->id().",Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'notificationReceived'
        ];
    }

    public function notificationReceived($notification)
    {
        $this->dispatch('notification-received', $notification);
    }

    public function render()
    {
        return <<<'blade'
            <div></div>
        blade;
    }
}
