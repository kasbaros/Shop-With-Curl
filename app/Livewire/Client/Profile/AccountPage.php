<?php

namespace App\Livewire\Client\Profile;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Layout('components.app-layout')]
#[Title('My Account - ShopWithCarl')]
class AccountPage extends Component
{
    #[Url(as: 'section', history: true)]
    public $activeSection = 'dashboard';

    // Sync route parameter or query with the component state
    public function mount($section = null): void
    {
        if ($section) {
            $this->showSection($section);
        }
    }

    public function showSection($section)
    {
        $allowedSections = ['dashboard', 'orders', 'address', 'details', 'wishlist', 'cart'];
        if (in_array($section, $allowedSections, true)) {
            $this->activeSection = $section;
        }
    }

    public function render()
    {
        return view('livewire.client.profile.account-page', [
            'isAuthPage' => true,
        ]);
    }
}
