<?php
namespace App\Livewire\Client\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.app-layout')]
class AccountDetails extends Component
{
    public string $name = '';
    public string $email = '';

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->name = (string) $user->name;
            $this->email = (string) $user->email;
        }
    }

    public function updateProfile(): void
    {
        $user = Auth::user();
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);
        session()->flash('success', 'Profile updated successfully.');
    }

    public function updatePassword(): void
    {
        $user = Auth::user();
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        // Clear password fields
        $this->current_password = $this->password = $this->password_confirmation = '';
        session()->flash('success', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.client.profile.account-details');
    }
}
