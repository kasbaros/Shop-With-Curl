<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Component;

class NotificationToast extends Component
{
    public array $notifications = [];

    #[On('notify')]
    public function addNotification($message, $type = 'success', $duration = 3000)
    {
        $id = uniqid();
        $this->notifications[] = [
            'id' => $id,
            'message' => $message,
            'type' => $type,
            'show' => true
        ];

        // Auto-remove after duration
        $this->dispatch('removeNotification', ['id' => $id])->delay($duration);
    }

    #[On('removeNotification')]
    public function removeNotification($id)
    {
        $this->notifications = array_filter($this->notifications, fn($n) => $n['id'] !== $id);
    }

    public function dismiss($id)
    {
        $this->notifications = array_filter($this->notifications, fn($n) => $n['id'] !== $id);
    }

    public function render()
    {
        return view('livewire.components.notification-toast');
    }
}
