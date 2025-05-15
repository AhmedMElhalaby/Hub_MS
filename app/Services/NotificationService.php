<?php

namespace App\Services;

trait NotificationService
{
    public function notify(string $type, string $message): void
    {
        $this->dispatch('notify', [
            'type' => $type,
            'message' => $message
        ]);
    }

    public function notifySuccess(string $message): void
    {
        $this->notify('success', $message);
    }

    public function notifyError(string $message): void
    {
        $this->notify('error', $message);
    }
}
