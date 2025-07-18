<?php

use App\Traits\SharedLayoutData;
use Livewire\Volt\Component;

new class extends Component {
    use SharedLayoutData;

    public function mount(): void
    {
        // Remove the language and currency logic since we're not using them
    }

    public function getCartCountProperty(): int
    {
        return $this->getLayoutData()['cartCount'];
    }

    public function getWishlistCountProperty(): int
    {
        return $this->getLayoutData()['wishlistCount'];
    }
};
?>
