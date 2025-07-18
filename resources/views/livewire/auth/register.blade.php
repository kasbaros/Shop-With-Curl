<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
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

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>


<div class="container ec__register-container animate__animated animate__fadeIn my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <!-- Header -->
            <div class="text-center mb-4">
                <h5 class="ec__form-title fw-bold">Create an Account</h5>
                <p class="ec__form-description text-muted">Enter your details below to create your account</p>
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

            <!-- Register Form -->
            <form wire:submit="register" class="ec__form">
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label ec__form-label">Name</label>
                    <input
                        type="text"
                        class="form-control ec__input"
                        id="name"
                        wire:model="name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Full name"
                    >
                </div>

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
                    <label for="password" class="form-label ec__form-label">Password</label>
                    <input
                        type="password"
                        class="form-control ec__input"
                        id="password"
                        wire:model="password"
                        required
                        autocomplete="new-password"
                        placeholder="Password"
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
                        placeholder="Confirm password"
                    >
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary ec__btn w-100">Create Account</button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-3">
                <span class="ec__text text-muted">Already have an account?</span>
                <a class="ec__link ms-1" href="{{ route('login') }}" wire:navigate>Log in</a>
            </div>
        </div>
    </div>
</div>
