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

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
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

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
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

<div class="container ec__login-container animate__animated animate__fadeIn my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <!-- Header -->
            <div class="text-center mb-4">
                <h5 class="ec__form-title fw-bold">Log in to Your Account</h5>
                <p class="ec__form-description text-muted">Enter your email and password below to log in</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show ec__alert" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show ec__alert" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Login Form -->
            <form wire:submit="login" class="ec__form">
                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label ec__form-label">Email Address</label>
                    <input
                        type="email"
                        class="form-control ec__input"
                        id="email"
                        wire:model="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="email@example.com"
                    >
                </div>

                <!-- Password -->
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label ec__form-label">Password</label>
                    <input
                        type="password"
                        class="form-control ec__input"
                        id="password"
                        wire:model="password"
                        required
                        autocomplete="current-password"
                        placeholder="Password"
                    >
                    @if (Route::has('password.request'))
                        <a class="ec__link text-sm position-absolute end-0 top-0 mt-1" href="{{ route('password.request') }}" wire:navigate>
                            Forgot your password?
                        </a>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-check">
                    <input
                        type="checkbox"
                        class="form-check-input ec__checkbox"
                        id="remember"
                        wire:model="remember"
                    >
                    <label class="form-check-label ec__form-label" for="remember">Remember me</label>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary ec__btn w-100">Log in</button>
                </div>
            </form>

            <!-- Sign Up Link -->
            @if (Route::has('register'))
                <div class="text-center mt-3">
                    <span class="ec__text text-muted">Don't have an account?</span>
                    <a class="ec__link ms-1" href="{{ route('register') }}" wire:navigate>Sign up</a>
                </div>
            @endif
        </div>
    </div>
</div>
