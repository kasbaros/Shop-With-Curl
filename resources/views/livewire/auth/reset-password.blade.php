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

<div class="py-5">
    <div class="container">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                <div class="text-center mb-4">
                    <h5 class="fw-bold">Reset Password</h5>
                    <p class="mt-1 text-secondary">Please enter your new password below</p>
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

                <form wire:submit="resetPassword">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="mb-2 fw-5">Email Address</label>
                        <div class="tf-field">
                            <input type="email" class="tf-input" id="email" name="email"
                                   wire:model="email" required autocomplete="email"
                                   placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="mb-2 fw-5">New Password</label>
                        <div class="tf-field">
                            <input type="password" class="tf-input" id="password" name="password"
                                   wire:model="password" required autocomplete="new-password"
                                   placeholder="New password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="mb-2 fw-5">Confirm Password</label>
                        <div class="tf-field">
                            <input type="password" class="tf-input" id="password_confirmation" name="password_confirmation"
                                   wire:model="password_confirmation" required autocomplete="new-password"
                                   placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="tf-btn btn-fill radius-60 animate-hover-btn w-100 justify-content-center">
                            <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                            <span wire:loading wire:target="resetPassword">Resetting...</span>
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <span class="text-secondary">Remember your password?</span>
                    <a class="fw-5 text-decoration-underline ms-1" href="{{ route('login') }}" wire:navigate>Log in</a>
                </div>
        </div>
    </div>
</div>
