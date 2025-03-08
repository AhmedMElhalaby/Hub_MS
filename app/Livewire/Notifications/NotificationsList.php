<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class NotificationsList extends Component
{
    use WithPagination;

    #[On('notification-received')]
    public function refreshNotifications()
    {
        // This method will be called when new notifications are received
        $this->resetPage();
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        $this->dispatch('notifications-updated');
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        $this->dispatch('notifications-updated');
    }

    public function redirectAndMarkAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $link = $notification->data['link'] ?? '#';

        $notification->markAsRead();
        $this->dispatch('notifications-updated');

        return $this->redirect($link);
    }

    public function render()
    {
        return view('livewire.notifications.notifications-list', [
            'notifications' => auth()->user()->notifications()->latest()->paginate(10)
        ]);
    }
}
