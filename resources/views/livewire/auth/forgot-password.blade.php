<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.app-layout')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="container ec__login-container animate__animated animate__fadeIn my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <!-- Header -->
            <div class="text-center mb-4">
                <h5 class="ec__form-title fw-bold">Forgot Password</h5>
                <p class="ec__form-description text-muted">Enter your email to receive a password reset link</p>
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

            <!-- Forgot Password Form -->
            <form wire:submit="sendPasswordResetLink" class="ec__form">
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

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary ec__btn w-100">Email Password Reset Link</button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="text-center mt-3">
                <span class="ec__text text-muted">Or, return to</span>
                <a class="ec__link ms-1" href="{{ route('login') }}" wire:navigate>Log in</a>
            </div>
        </div>
    </div>
</div>
