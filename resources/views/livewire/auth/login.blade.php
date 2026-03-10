<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.app-layout')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public string $successMessage = '';
    public string $errorMessage = '';

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->successMessage = '';
        $this->errorMessage = '';

        try {
            $this->validate();

            $this->ensureIsNotRateLimited();

            if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }

            RateLimiter::clear($this->throttleKey());
            Session::regenerate();

            $this->successMessage = 'Login successful! Redirecting...';

            // Check if user is admin and redirect accordingly
            $user = auth()->user();
            if ($user && ($user->isAdmin() || $user->isDeveloper())) {
                $this->redirectIntended(default: route('admin.dashboard'), navigate: false);
            } else {
                $this->redirectIntended(default: route('account.dashboard'), navigate: false);
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->errorMessage = 'Something went wrong: ' . $e->getMessage();
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="py-5 mb-5">
    <div class="container">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                <div class="text-center mb-4">
                    <h5 class="fw-bold">Log in to Your Account</h5>
                    <p class="mt-1 text-secondary">Enter your email and password below to log in</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($successMessage)
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $successMessage }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errorMessage)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errorMessage }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form wire:submit="login" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="mb-2 fw-5">Email Address</label>
                        <div class="tf-field">
                            <input
                                type="email"
                                class="tf-input"
                                id="email"
                                name="email"
                                wire:model="email"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="email@example.com"
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="password" class="fw-5">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-secondary text-decoration-underline" href="{{ route('password.request') }}" wire:navigate>
                                    Forgot your password?
                                </a>
                            @endif
                        </div>
                        <div class="tf-field">
                            <input
                                type="password"
                                class="tf-input"
                                id="password"
                                name="password"
                                wire:model="password"
                                required
                                autocomplete="current-password"
                                placeholder="Password"
                            >
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                                wire:model="remember"
                            >
                            <label for="remember">Remember me</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="tf-btn btn-fill radius-60 animate-hover-btn w-100 justify-content-center">
                            <span wire:loading.remove wire:target="login">Log in</span>
                            <span wire:loading wire:target="login">Logging in...</span>
                        </button>
                    </div>
                </form>

                @if (Route::has('register'))
                    <div class="text-center mt-3">
                        <span class="text-secondary">Don't have an account?</span>
                        <a class="fw-5 text-decoration-underline ms-1" href="{{ route('register') }}" wire:navigate>Sign up</a>
                    </div>
                @endif
        </div>
    </div>
</div>
