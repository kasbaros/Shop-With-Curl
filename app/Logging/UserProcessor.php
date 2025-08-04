<?php

    namespace App\Logging;

    use Monolog\Processor\ProcessorInterface;
    use Illuminate\Support\Facades\Auth;

    class UserProcessor implements ProcessorInterface
    {
        public function __invoke(array $record): array
        {
            if (Auth::check()) {
                $user = Auth::user();
                $record['extra']['user'] = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role ?? 'user',
                ];
            }

            return $record;
        }
    }
