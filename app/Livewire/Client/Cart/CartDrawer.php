<?php
//
//namespace App\Livewire\Client\Cart;
//
//use Livewire\Component;
//
//class CartDrawer extends Component
//{
//    public $isOpen = false;
//    public $cart = [];
//    public $itemCount = 0;
//    public $total = 0;
//
//    // UI state used by the Blade view
//    public bool $showAddNote = false;
//    public bool $showAddGift = false;
//    public bool $showEstimateShipping = false;
//    public bool $agreeToTerms = false;
//
//    // Optional data used in the view
//    public array $recommendations = [];
//
//    // Free shipping threshold used for the progress bar
//    public float $freeShippingThreshold = 75.0;
//
//    protected $listeners = [
//        'cart-updated' => 'refreshCart',
//        'toggle-cart-drawer' => 'toggleDrawer',
//    ];
//
//    public function mount(): void
//    {
//        $this->refreshCart();
//        // Recommendations can be populated here if needed. Keep empty to avoid extra queries for now.
//        $this->recommendations = [];
//    }
//
//    public function toggleDrawer(): void
//    {
//        $this->isOpen = !$this->isOpen;
//    }
//
//    public function openDrawer(): void
//    {
//        $this->isOpen = true;
//    }
//
//    public function closeDrawer(): void
//    {
//        $this->isOpen = false;
//    }
//
//    // Modal toggles used by the included partials
//    public function toggleAddNote(): void
//    {
//        $this->showAddNote = !$this->showAddNote;
//    }
//
//    public function toggleAddGift(): void
//    {
//        $this->showAddGift = !$this->showAddGift;
//    }
//
//    public function toggleEstimateShipping(): void
//    {
//        $this->showEstimateShipping = !$this->showEstimateShipping;
//    }
//
//    public function refreshCart(): void
//    {
//        $this->cart = session()->get('cart', []);
//        $this->itemCount = collect($this->cart)->sum('quantity');
//        $this->total = collect($this->cart)->sum(function ($item) {
//            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
//        });
//    }
//
//    public function updateQuantity($key, $quantity): void
//    {
//        if ($quantity <= 0) {
//            $this->removeItem($key);
//            return;
//        }
//
//        $cart = session()->get('cart', []);
//        if (isset($cart[$key])) {
//            $cart[$key]['quantity'] = $quantity;
//            session()->put('cart', $cart);
//            $this->refreshCart();
//            $this->dispatch('cart-updated');
//        }
//    }
//
//    public function removeItem($key): void
//    {
//        $cart = session()->get('cart', []);
//        unset($cart[$key]);
//        session()->put('cart', $cart);
//        $this->refreshCart();
//        $this->dispatch('cart-updated');
//    }
//
//    public function clearCart(): void
//    {
//        session()->forget('cart');
//        $this->refreshCart();
//        $this->dispatch('cart-updated');
//    }
//
//    public function getSubtotalProperty()
//    {
//        return collect($this->cart)->sum(function ($item) {
//            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
//        });
//    }
//
//    // Formats total for display in the view
//    public function getFormattedTotalProperty(): string
//    {
//        if (function_exists('money_format_ugx')) {
//            return money_format_ugx($this->total);
//        }
//        // Fallback formatting
//        return 'UGX ' . number_format((float)$this->total, 0);
//    }
//
//    // Free shipping helpers used by the progress bar and message
//    public function getQualifiesForFreeShippingProperty(): bool
//    {
//        return $this->total >= $this->freeShippingThreshold;
//    }
//
//    public function getFreeShippingRemainingProperty(): float
//    {
//        $remaining = $this->freeShippingThreshold - (float)$this->total;
//        return max(0, $remaining);
//    }
//
//    public function getFreeShippingProgressProperty(): float
//    {
//        if ($this->freeShippingThreshold <= 0) {
//            return 0.0;
//        }
//        $progress = ((float)$this->total / $this->freeShippingThreshold) * 100;
//        return max(0.0, min(100.0, $progress));
//    }
//
//    public function render()
//    {
//        return view('livewire.client.cart.cart-drawer');
//    }
//}



namespace App\Livewire\Client\Cart;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;

class CartDrawer extends Component
{
    // Controls visibility for integrations that toggle the drawer externally (e.g., $set('isOpen', ...))
    public bool $isOpen = false;
    public $cart = [];
    public $itemCount = 0;
    public $total = 0;

    // UI state used by the Blade view
    public bool $showAddNote = false;
    public bool $showAddGift = false;
    public bool $showEstimateShipping = false;
    public bool $agreeToTerms = false;

    // Optional data used in the view
    public array $recommendations = [];

    // Free shipping threshold used for the progress bar
    public float $freeShippingThreshold = 75.0;

    #[On('cart-updated')]
    #[On('cart:add')]
    public function refreshCart(): void
    {
        $this->cart = session()->get('cart', []);
        $this->itemCount = collect($this->cart)->sum('quantity');
        $this->total = collect($this->cart)->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
        $this->loadRecommendations();
    }

    public function toggleAddNote(): void
    {
        $this->showAddNote = !$this->showAddNote;
    }

    public function toggleAddGift(): void
    {
        $this->showAddGift = !$this->showAddGift;
    }

    public function toggleEstimateShipping(): void
    {
        $this->showEstimateShipping = !$this->showEstimateShipping;
    }

    private function loadRecommendations()
    {
        try {
            $productIdsInCart = collect($this->cart)->pluck('product_id')->filter()->all();

            $products = Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->when($productIdsInCart, fn($q) => $q->whereNotIn('id', $productIdsInCart))
                ->inRandomOrder()
                ->take(3)
                ->get();

            $this->recommendations = $products->map(fn(Product $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => $p->effective_price,
                'image' => $p->getStorageImageUrl($p->featured_image),
            ])->toArray();
        } catch (\Exception $e) {
            $this->recommendations = [];
        }
    }

    public function updateQuantity($key, $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($key);
            return;
        }
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $quantity;
            session()->put('cart', $cart);
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($key): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);
        $this->dispatch('cart-updated');
    }

    public function clearCart(): void
    {
        session()->forget('cart');
        $this->dispatch('cart-updated');
    }

    public function getFormattedTotalProperty(): string
    {
        if (function_exists('money_format_ugx')) {
            return money_format_ugx($this->total);
        }
        return 'UGX ' . number_format((float)$this->total, 0);
    }

    public function getQualifiesForFreeShippingProperty(): bool
    {
        return $this->total >= $this->freeShippingThreshold;
    }

    public function getFreeShippingRemainingProperty(): float
    {
        return max(0, $this->freeShippingThreshold - (float)$this->total);
    }

    public function getFreeShippingProgressProperty(): float
    {
        if ($this->freeShippingThreshold <= 0) return 100.0;
        return max(0.0, min(100.0, ((float)$this->total / $this->freeShippingThreshold) * 100));
    }

    public function mount(): void
    {
        $this->refreshCart();
    }

    public function render()
    {
        return view('livewire.client.cart.cart-drawer');
    }
}
