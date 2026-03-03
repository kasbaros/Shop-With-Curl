<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.app-layout')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PasswordReset) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="container ec__login-container animate__animated animate__fadeIn my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <!-- Header -->
            <div class="text-center mb-4">
                <h5 class="ec__form-title fw-bold">Reset Password</h5>
                <p class="ec__form-description text-muted">Please enter your new password below</p>
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

            <!-- Reset Password Form -->
            <form wire:submit="resetPassword" class="ec__form">
                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label ec__form-label">Email Address</label>
                    <input
                        type="email"
                        class="form-control ec__input"
                        id="email"
                        wire:model="email"
                        required
                        autocomplete="email"
                        placeholder="email@example.com"
                    >
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label ec__form-label">New Password</label>
                    <input
                        type="password"
                        class="form-control ec__input"
                        id="password"
                        wire:model="password"
                        required
                        autocomplete="new-password"
                        placeholder="New password"
                    >
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label ec__form-label">Confirm Password</label>
                    <input
                        type="password"
                        class="form-control ec__input"
                        id="password_confirmation"
                        wire:model="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm new password"
                    >
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary ec__btn w-100">Reset Password</button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="text-center mt-3">
                <span class="ec__text text-muted">Remember your password?</span>
                <a class="ec__link ms-1" href="{{ route('login') }}" wire:navigate>Log in</a>
            </div>
        </div>
    </div>
</div>
