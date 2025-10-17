<?php

namespace App\Livewire\Client\Cart;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Component;

class Cart extends Component
{
    public Product $product;
    public $selectedVariant = null;
    public int $quantity = 1;
    public $selectedSize = null;
    public $selectedColor = null;
    public $selectedMaterial = null;
    public $showVariantSelection = false;
    /**
     * @var ProductVariant[]|\Illuminate\Database\Eloquent\Collection|\LaravelIdea\Helper\App\Models\_IH_ProductVariant_C
     */
    private $cachedVariants = null;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->showVariantSelection = $this->product->variants()->exists();

        if ($this->showVariantSelection) {
            $this->initializeVariantSelections();
        }
    }

    private function initializeVariantSelections()
    {
        $variants = $this->product->variants()->where('is_active', true)->get();

        // Get unique values for each variant attribute
        $sizes = $variants->whereNotNull('size')->pluck('size')->unique()->values();
        $colors = $variants->whereNotNull('color')->pluck('color')->unique()->values();
        $materials = $variants->whereNotNull('material')->pluck('material')->unique()->values();

        // Auto-select if only one option available
        if ($sizes->count() === 1) {
            $this->selectedSize = $sizes->first();
        }
        if ($colors->count() === 1) {
            $this->selectedColor = $colors->first();
        }
        if ($materials->count() === 1) {
            $this->selectedMaterial = $materials->first();
        }
    }

//    public function updatedSelectedSize()
//    {
//        $this->updateSelectedVariant();
//    }
//
//    public function updatedSelectedColor()
//    {
//        $this->updateSelectedVariant();
//    }

    public function updatedSelectedSize($size): void
    {
        $this->selectedSize = $size;
        // Reset color and material if they are no longer valid
        if ($this->selectedColor && !$this->getAvailableColorsProperty()->contains($this->selectedColor)) {
            $this->selectedColor = null;
        }
        if ($this->selectedMaterial && !$this->getAvailableMaterialsProperty()->contains($this->selectedMaterial)) {
            $this->selectedMaterial = null;
        }
        $this->updateSelectedVariant();
    }

    public function updatedSelectedColor($color): void
    {
        $this->selectedColor = $color;
        // Reset size and material if they are no longer valid
        if ($this->selectedSize && !$this->getAvailableSizesProperty()->contains($this->selectedSize)) {
            $this->selectedSize = null;
        }
        if ($this->selectedMaterial && !$this->getAvailableMaterialsProperty()->contains($this->selectedMaterial)) {
            $this->selectedMaterial = null;
        }
        $this->updateSelectedVariant();
    }

    public function updatedSelectedMaterial($material): void
    {
        $this->selectedMaterial = $material;
        // Reset size and color if they are no longer valid
        if ($this->selectedSize && !$this->getAvailableSizesProperty()->contains($this->selectedSize)) {
            $this->selectedSize = null;
        }
        if ($this->selectedColor && !$this->getAvailableColorsProperty()->contains($this->selectedColor)) {
            $this->selectedColor = null;
        }
        $this->updateSelectedVariant();
    }

//    public function updatedSelectedMaterial()
//    {
//        $this->updateSelectedVariant();
//    }

    private function updateSelectedVariant()
    {
        if (!$this->showVariantSelection) {
            return;
        }

        $variant = $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->first();

        $this->selectedVariant = $variant?->id;

        // Notify frontend (Product Detail page) to update the gallery image when a variant image exists
        $imageUrl = null;
        if ($variant && !empty($variant->image_url)) {
            $imageUrl = $variant->image_url;
        }
        // Livewire front-end can listen via Livewire.on('product:variantImage', ...)
        $this->dispatch('product:variantImage', imageUrl: $imageUrl);
    }

    public function addToCart()
    {
        if ($this->showVariantSelection && !$this->selectedVariant) {
            $this->addError('variant', 'Please select product options.');
            return;
        }

        if ($this->quantity < 1) {
            $this->addError('quantity', 'Quantity must be at least 1.');
            return;
        }

        // Check stock availability
        $stockQuantity = $this->selectedVariant
            ? ProductVariant::find($this->selectedVariant)->stock_quantity
            : $this->product->stock_quantity;

        if ($stockQuantity < $this->quantity) {
            $this->addError('quantity', 'Not enough stock available.');
            return;
        }

        // Add to cart (using session-based cart)
        $cart = session()->get('cart', []);
        $cartKey = $this->selectedVariant ? 'variant_' . $this->selectedVariant : 'product_' . $this->product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $this->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $this->product->id,
                'product_variant_id' => $this->selectedVariant,
                'quantity' => $this->quantity,
                'price' => $this->selectedVariant
                    ? ProductVariant::find($this->selectedVariant)->effective_price
                    : $this->product->effective_price,
            ];
        }

        session()->put('cart', $cart);

        // Calculate new cart count
        $newCartCount = collect($cart)->sum('quantity');

        // Dispatch the correct event name that CartIcon is listening for
        $this->dispatch('cart:updated', ['count' => $newCartCount]);

        $this->dispatch('show-notification',
            type: 'success',
            message: 'Product added to cart!'
        );

        // Reset form
        $this->quantity = 1;
    }

    public function getAvailableSizesProperty(): \Illuminate\Support\Collection
    {
        return $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->whereNotNull('size')
            ->pluck('size')
            ->unique()
            ->values();
    }

    public function getAvailableColorsProperty()
    {
        return $this->getCachedVariants()
            ->when($this->selectedSize, fn($variants) => $variants->where('size', $this->selectedSize))
            ->when($this->selectedMaterial, fn($variants) => $variants->where('material', $this->selectedMaterial))
            ->whereNotNull('color')
            ->groupBy('color')
            ->map(function ($variants, $color) {
                // Get hex_code from first variant or fallback
                $hexCode = $variants->first()?->hex_code ?? $this->getHexCodeFallback($color);
                return [
                    'name' => $color,
                    'hex_code' => $hexCode,
                    'css_class' => 'bg-color-' . strtolower(str_replace(' ', '-', $color))
                ];
            })
            ->values();
    }

    private function getHexCodeFallback($color): string
    {
        // Normalize the color name to be case-insensitive and remove spaces for common pairings (e.g., 'darkblue' -> 'Darkblue')
        $normalizedColor = ucfirst(strtolower(str_replace(' ', '', $color)));

        $fallbacks = [
            // --- CORE COLORS ---
            'Red' => '#FF0000',
            'Green' => '#008000',
            'Blue' => '#0000FF',
            'Black' => '#000000',
            'White' => '#FFFFFF',
            'Yellow' => '#FFFF00',
            'Orange' => '#FFA500',
            'Purple' => '#800080',
            'Pink' => '#FFC0CB',
            'Gray' => '#808080',
            'Grey' => '#808080', // Alias

            // --- DARK / SHADES (Adding Black to the core color) ---
            // Red Shades
            'Darkred' => '#8B0000',
            'Maroon' => '#800000',
            'Crimson' => '#DC143C',
            'Firebrick' => '#B22222',

            // Green Shades
            'Darkgreen' => '#006400',
            'Forestgreen' => '#228B22',
            'Olive' => '#808000',
            'Darkolivegreen' => '#556B2F',

            // Blue Shades
            'Darkblue' => '#00008B',
            'Navy' => '#000080',
            'Midnightblue' => '#191970',
            'Darkslateblue' => '#483D8B',

            // Purple Shades
            'Darkmagenta' => '#8B008B',
            'Indigo' => '#4B0082',
            'Plum' => '#DDA0DD',
            'Violet' => '#EE82EE',

            // Brown / Earth Tones
            'Brown' => '#A52A2A',
            'Saddlebrown' => '#8B4513',
            'Sienna' => '#A0522D',
            'Chocolate' => '#D2691E',
            'Darkgoldenrod' => '#B8860B',

            // Neutrals
            'Charcoal' => '#36454F',
            'Darkgray' => '#A9A9A9',
            'Darkgrey' => '#A9A9A9',
            'Black' => '#000000',

            // --- LIGHT / TINTS (Adding White to the core color) ---
            // Red/Pink Tints
            'Lightcoral' => '#F08080',
            'Lightpink' => '#FFB6C1',
            'Hotpink' => '#FF69B4',
            'Deepskyblue' => '#00BFFF', // Used for "Electric" or "Bright"
            'Salmon' => '#FA8072',

            // Green Tints
            'Lightgreen' => '#90EE90',
            'Springgreen' => '#00FF7F',
            'Mintgreen' => '#98FB98',
            'Palegreen' => '#98FB98',

            // Blue Tints
            'Lightblue' => '#ADD8E6',
            'Skyblue' => '#87CEEB',
            'Powderblue' => '#B0E0E6',
            'Lightskyblue' => '#87CEFA',
            'Azure' => '#F0FFFF',

            // Yellow/Orange Tints
            'Lightyellow' => '#FFFFE0',
            'Lemonchiffon' => '#FFFACD',
            'Gold' => '#FFD700',
            'Lightsalmon' => '#FFA07A',
            'Lightgoldenrodyellow' => '#FAFAD2',

            // Purple Tints
            'Lavender' => '#E6E6FA',
            'Thistle' => '#D8BFD8',

            // Neutrals
            'Lightgray' => '#D3D3D3',
            'Lightgrey' => '#D3D3D3',
            'Beige' => '#F5F5DC',
            'Tan' => '#D2B48C',
            'Cream' => '#FFFDD0',
            'Ivory' => '#FFFFF0',

            // --- MISCELLANEOUS / FASHION COLORS ---
            'Teal' => '#008080',
            'Turquoise' => '#40E0D0',
            'Cyan' => '#00FFFF',
            'Aqua' => '#00FFFF',
            'Silver' => '#C0C0C0',
            'Khaki' => '#F0E68C',
            'OliveDrab' => '#6B8E23', // More Green-Brown
            'Fuchsia' => '#FF00FF',
            'Royalblue' => '#4169E1',
            'Steelblue' => '#4682B4',
        ];

        return $fallbacks[$normalizedColor] ?? '#CCCCCC';
    }

    private function getCachedVariants(): \Illuminate\Database\Eloquent\Collection|array|\LaravelIdea\Helper\App\Models\_IH_ProductVariant_C
    {
        if ($this->cachedVariants === null) {
            $this->cachedVariants = $this->product->variants()->where('is_active', true)->get();
        }
        return $this->cachedVariants;
    }

    public function getAvailableMaterialsProperty()
    {
        return $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->whereNotNull('material')
            ->pluck('material')
            ->unique()
            ->values();
    }

    public function getCurrentVariantProperty()
    {
        if (!$this->selectedVariant) {
            return null;
        }

        return ProductVariant::find($this->selectedVariant);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.client.cart.add-to-cart');
    }
}
