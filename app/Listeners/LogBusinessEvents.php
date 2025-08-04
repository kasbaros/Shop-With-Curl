<?php

    namespace App\Listeners;

    use Illuminate\Support\Facades\Log;

    class LogBusinessEvents
    {
        public function handle($event)
        {
            $eventName = class_basename($event);

            Log::channel('business_analytics')->info("Business Event: {$eventName}", [
                'event_type' => $eventName,
                'timestamp' => now()->toISOString(),
                'data' => $this->extractEventData($event),
                'metadata' => [
                    'session_id' => session()->getId(),
                    'user_id' => auth()->id(),
                    'ip_address' => request()->ip(),
                ],
            ]);
        }

        private function extractEventData($event): array
        {
            $reflection = new \ReflectionClass($event);
            $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

            $data = [];
            foreach ($properties as $property) {
                $data[$property->getName()] = $property->getValue($event);
            }

            return $data;
        }
    }
