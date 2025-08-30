<?php
namespace App\Livewire\Client\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;



#[Title('Shop - ShopWithCarl')]
class Dashboard extends Component
{
    public $user;
    public $recentOrders = [];
    public $wishlistCount = 0;
    public $addressCount = 0;

    public function mount()
    {
        $this->user = Auth::user();
        if ($this->user) {
            $this->recentOrders = $this->user->orders()->latest()->limit(5)->get();
            $this->wishlistCount = $this->user->wishlist()->count();
            $this->addressCount = $this->user->addresses()->count();
        }
    }

    public function render()
    {
        // The ->layout() call is no longer needed here
        return view('livewire.client.profile.dashboard');
    }
}
