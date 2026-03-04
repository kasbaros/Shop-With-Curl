<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.app-layout')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('account.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="tf-page-cart-wrap py-5">
    <div class="container">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                <div class="text-center mb-4">
                    <h5 class="fw-bold">Create an Account</h5>
                    <p class="mt-1 text-secondary">Enter your details below to create your account</p>
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

                <form wire:submit="register" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="mb-2 fw-5">Name</label>
                        <div class="tf-field">
                            <input type="text" class="tf-input" id="name" name="name"
                                   wire:model="name" required autofocus autocomplete="name"
                                   placeholder="Full name">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="mb-2 fw-5">Email Address</label>
                        <div class="tf-field">
                            <input type="email" class="tf-input" id="email" name="email"
                                   wire:model="email" required autocomplete="email"
                                   placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="mb-2 fw-5">Password</label>
                        <div class="tf-field">
                            <input type="password" class="tf-input" id="password" name="password"
                                   wire:model="password" required autocomplete="new-password"
                                   placeholder="Password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="mb-2 fw-5">Confirm Password</label>
                        <div class="tf-field">
                            <input type="password" class="tf-input" id="password_confirmation" name="password_confirmation"
                                   wire:model="password_confirmation" required autocomplete="new-password"
                                   placeholder="Confirm password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="tf-btn btn-fill radius-60 animate-hover-btn w-100 justify-content-center">
                            <span wire:loading.remove wire:target="register">Create Account</span>
                            <span wire:loading wire:target="register">Creating account...</span>
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <span class="text-secondary">Already have an account?</span>
                    <a class="fw-5 text-decoration-underline ms-1" href="{{ route('login') }}" wire:navigate>Log in</a>
                </div>
        </div>
    </div>
</div>
