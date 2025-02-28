<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Notification extends Component
{
    public $notifications = [];

    public function mount()
    {
        $this->checkFlashMessages();
    }

    public function checkFlashMessages()
    {
        if (session()->has('message')) {
            $this->addNotification(session('message'), 'success');
            session()->forget('message');
        }

        if (session()->has('error')) {
            $this->addNotification(session('error'), 'error');
            session()->forget('error');
        }
    }

    private function addNotification($message, $type)
    {
        $this->notifications[] = [
            'id' => uniqid(),
            'message' => $message,
            'type' => $type
        ];
    }

    public function remove($id)
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }

    public function render()
    {
        return view('livewire.components.notification');
    }
}
