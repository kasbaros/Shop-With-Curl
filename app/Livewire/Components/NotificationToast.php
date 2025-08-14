<?php

    namespace App\Livewire\Components;

    use Livewire\Attributes\On;
    use Livewire\Component;

    class NotificationToast extends Component
    {
        public array $notifications = [];

        // No constructor needed - Livewire handles initialization

        #[On('notify')]
        public function addNotification($data = []): void
        {
            // Normalize payload: accept array or plain string
            $message = '';
            $type = 'success';
            $duration = 3000;

            if (is_array($data)) {
                $message = $data['message'] ?? '';
                $type = $data['type'] ?? 'success';
                $duration = $data['duration'] ?? 3000;
            } elseif (is_string($data)) {
                $message = $data;
            }

            if ($message === '') {
                $message = 'Action completed';
            }

            $id = uniqid();
            $this->notifications[] = [
                'id' => $id,
                'message' => $message,
                'type' => $type,
                'show' => true
            ];

            // Use JavaScript timeout for auto-removal
            $this->dispatch('setupAutoRemove', [
                'id' => $id,
                'duration' => $duration
            ]);
        }

        #[On('removeNotification')]
        public function removeNotification($data = []): void
        {
            $id = $data['id'] ?? null;
            if ($id) {
                $this->notifications = array_filter($this->notifications, fn($n) => $n['id'] !== $id);
            }
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
