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

<div class="py-5">
    <div class="container">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                <div class="text-center mb-4">
                    <h5 class="fw-bold">Forgot Password</h5>
                    <p class="mt-1 text-secondary">Enter your email to receive a password reset link</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
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

                <form wire:submit="sendPasswordResetLink">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="mb-2 fw-5">Email Address</label>
                        <div class="tf-field">
                            <input type="email" class="tf-input" id="email" name="email"
                                   wire:model="email" required autofocus autocomplete="email"
                                   placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="tf-btn btn-fill radius-60 animate-hover-btn w-100 justify-content-center">
                            <span wire:loading.remove wire:target="sendPasswordResetLink">Email Password Reset Link</span>
                            <span wire:loading wire:target="sendPasswordResetLink">Sending...</span>
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <span class="text-secondary">Or, return to</span>
                    <a class="fw-5 text-decoration-underline ms-1" href="{{ route('login') }}" wire:navigate>Log in</a>
                </div>
        </div>
    </div>
</div>
