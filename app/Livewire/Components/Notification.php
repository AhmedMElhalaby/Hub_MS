<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Notification extends Component
{
    public $notifications = [];

    protected $listeners = ['notify' => 'handleNotification'];

    public function handleNotification($data)
    {
        $this->notifications[] = [
            'id' => uniqid(),
            'type' => $data['type'],
            'message' => $data['message'],
        ];
    }

    public function remove($id)
    {
        $this->notifications = array_filter($this->notifications, function($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }

    public function render()
    {
        return view('livewire.components.notification');
    }
}
