<?php

namespace App\Livewire\Components;

use App\Models\Product;
use App\Models\ProductVariant;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductQuickView extends Component
{
    public ?Product $product = null;
    public bool $showModal = false;
    public int $quantity = 1;
    public ?string $selectedColor = null;
    public ?string $selectedSize = null;
    public ?string $selectedMaterial = null;
    public array $selectedVariants = [];
    public ?int $selectedVariantId = null;

    #[On('product:quickView')]
    public function showQuickView($productId = null): void
    {
        // Support both primitive ID and object payloads { productId: X } or { id: X }
        if (is_array($productId)) {
            $productId = $productId['productId'] ?? $productId['id'] ?? null;
        }
        if (!$productId) return;

        $this->product = Product::with(['categories', 'media', 'variants'])->find($productId);
        if (!$this->product) return;

        $this->showModal = true;
        $this->quantity = 1;
        $this->resetVariantSelections();
        $this->initializeVariantSelections();

        // Dispatch event to initialize Swiper after modal is shown
        $this->dispatch('product:quickViewReady');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->product = null;
        $this->reset(['quantity', 'selectedColor', 'selectedSize', 'selectedMaterial', 'selectedVariants', 'selectedVariantId']);
    }

    private function resetVariantSelections()
    {
        $this->selectedColor = null;
        $this->selectedSize = null;
        $this->selectedMaterial = null;
        $this->selectedVariantId = null;
        $this->selectedVariants = [];
    }

    private function initializeVariantSelections()
    {
        if (!$this->product->variants()->exists()) {
            return;
        }

        $variants = $this->product->variants()->where('is_active', true)->get();

        // Auto-select if only one option available for each attribute
        $sizes = $variants->whereNotNull('size')->pluck('size')->unique()->values();
        $colors = $variants->whereNotNull('color')->pluck('color')->unique()->values();
        $materials = $variants->whereNotNull('material')->pluck('material')->unique()->values();

        if ($sizes->count() === 1) {
            $this->selectedSize = $sizes->first();
        }
        if ($colors->count() === 1) {
            $this->selectedColor = $colors->first();
        }
        if ($materials->count() === 1) {
            $this->selectedMaterial = $materials->first();
        }

        $this->updateSelectedVariant();
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updatedSelectedColor()
    {
        $this->updateSelectedVariant();
    }

    public function updatedSelectedSize()
    {
        $this->updateSelectedVariant();
    }

    public function updatedSelectedMaterial()
    {
        $this->updateSelectedVariant();
    }

    private function updateSelectedVariant()
    {
        if (!$this->product || !$this->product->variants()->exists()) {
            return;
        }

        $variant = $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedColor, fn($q) => $q->where('color', $this->selectedColor))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->first();

        $this->selectedVariantId = $variant?->id;

        // Update selectedVariants array for compatibility
        $this->selectedVariants = array_filter([
            'size' => $this->selectedSize,
            'color' => $this->selectedColor,
            'material' => $this->selectedMaterial,
            'variant_id' => $this->selectedVariantId,
        ]);
    }

    public function addToCart()
    {
        if (!$this->product) return;

        // Validate variant selection if product has variants
        if ($this->product->variants()->exists() && !$this->selectedVariantId) {
            $this->dispatch('notify', [
                'message' => 'Please select product options.',
                'type' => 'error'
            ]);
            return;
        }

        // Check stock availability
        if ($this->selectedVariantId) {
            $variant = ProductVariant::find($this->selectedVariantId);
            if (!$variant || $variant->stock_quantity < $this->quantity) {
                $this->dispatch('notify', [
                    'message' => 'Not enough stock available.',
                    'type' => 'error'
                ]);
                return;
            }
        } elseif ($this->product->manage_stock && $this->product->stock_quantity < $this->quantity) {
            $this->dispatch('notify', [
                'message' => 'Not enough stock available.',
                'type' => 'error'
            ]);
            return;
        }

        // Actually add item to cart using CartService
        $cartService = app(\App\Services\CartService::class);
        $success = $cartService->add($this->product->id, $this->quantity, $this->selectedVariants);

        if ($success) {
            // Emit event for cart addition (use positional args for compatibility)
            $this->dispatch('cart:add', $this->product->id, $this->quantity, $this->selectedVariants);

            // Show success message
            $this->dispatch('notify', [
                'message' => $this->product->name . ' added to cart!',
                'type' => 'success'
            ]);

            $this->closeModal();
        } else {
            $this->dispatch('notify', [
                'message' => 'Failed to add item to cart.',
                'type' => 'error'
            ]);
        }
    }

    public function toggleWishlist()
    {
        if (!$this->product) return;

        $this->dispatch('wishlist:toggle', ['id' => $this->product->id]);
    }

    public function toggleCompare()
    {
        if (!$this->product) return;

        $this->dispatch('compare:toggle', ['id' => $this->product->id]);
    }

    public function getImageUrlProperty()
    {
        if (!$this->product) return asset('images/placeholder-product.jpg');

        // Prefer current variant image when selected
        if ($this->currentVariant && !empty($this->currentVariant->image_url)) {
            return $this->currentVariant->image_url;
        }

        // Use the product's built-in primary image accessor
        return $this->product->primary_image_url;
    }

    public function getAvailableSizesProperty()
    {
        if (!$this->product) return collect([]);

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
        if (!$this->product) return collect([]);

        $colors = $this->product->variants()
            ->where('is_active', true)
            ->when($this->selectedSize, fn($q) => $q->where('size', $this->selectedSize))
            ->when($this->selectedMaterial, fn($q) => $q->where('material', $this->selectedMaterial))
            ->whereNotNull('color')
            ->pluck('color')
            ->unique()
            ->values();

        return $colors->map(function ($name) {
            return [
                'name' => $name,
                'hex_code' => $this->resolveColorHex($name),
            ];
        });
    }

    protected function resolveColorHex(?string $name): string
    {
        if (!$name) return '#999999';

        $map = [
            // Neutrals & Grays
            'black' => '#000000',
            'white' => '#FFFFFF',
            'gray' => '#6B7280',
            'grey' => '#6B7280',
            'charcoal' => '#36454F',
            'silver' => '#C0C0C0',
            'platinum' => '#E5E4E2',
            'beige' => '#F5F5DC',
            'cream' => '#FFFDD0',
            'ivory' => '#FFFFF0',
            'off-white' => '#F8F8F8',
            'light gray' => '#D3D3D3',
            'light grey' => '#D3D3D3',
            'dark gray' => '#A9A9A9',
            'dark grey' => '#A9A9A9',

            // Blues
            'blue' => '#1D4ED8',
            'navy' => '#000080',
            'navy blue' => '#000080',
            'sky blue' => '#87CEEB',
            'light blue' => '#ADD8E6',
            'dark blue' => '#00008B',
            'royal blue' => '#4169E1',
            'cornflower blue' => '#6495ED',
            'steel blue' => '#4682B4',
            'slate blue' => '#6A5ACD',
            'midnight blue' => '#191970',
            'denim' => '#1560BD',
            'indigo' => '#4B0082',
            'periwinkle' => '#CCCCFF',
            'cobalt' => '#0047AB',
            'azure' => '#0080FF',
            'teal' => '#008080',
            'turquoise' => '#40E0D0',
            'cyan' => '#00FFFF',
            'aqua' => '#00FFFF',
            'light teal' => '#AFEEEE',
            'dark teal' => '#003333',
            'turquoise blue' => '#00CED1',

            // Greens
            'green' => '#16A34A',
            'light green' => '#90EE90',
            'dark green' => '#006400',
            'forest green' => '#228B22',
            'lime' => '#00FF00',
            'lime green' => '#32CD32',
            'olive' => '#808000',
            'olive green' => '#6B8E23',
            'sea green' => '#2E8B57',
            'spring green' => '#00FF7F',
            'medium sea green' => '#3CB371',
            'pale green' => '#98FB98',
            'mint' => '#98FF98',
            'mint green' => '#98FF98',
            'khaki' => '#F0E68C',
            'sage' => '#9DC183',
            'sage green' => '#9DC183',
            'hunter green' => '#355E3B',

            // Reds
            'red' => '#DC2626',
            'dark red' => '#8B0000',
            'crimson' => '#DC143C',
            'scarlet' => '#FF2400',
            'tomato' => '#FF6347',
            'Indian red' => '#CD5C5C',
            'light coral' => '#F08080',
            'salmon' => '#FA8072',
            'light salmon' => '#FFA07A',
            'coral' => '#FF7F50',
            'deep pink' => '#FF1493',
            'hot pink' => '#FF69B4',
            'light pink' => '#FFB6C1',
            'pink' => '#DB2777',
            'pale violet red' => '#DB7093',
            'maroon' => '#800000',
            'wine' => '#722F37',
            'burgundy' => '#800020',
            'rose' => '#FF007F',
            'rose pink' => '#FF66CC',

            // Purples & Violets
            'purple' => '#7C3AED',
            'dark purple' => '#301934',
            'blue purple' => '#663399',
            'blue violet' => '#8A2BE2',
            'violet' => '#EE82EE',
            'light purple' => '#DDA0DD',
            'plum' => '#DDA0DD',
            'orchid' => '#DA70D6',
            'medium orchid' => '#BA55D3',
            'dark orchid' => '#9932CC',
            'dark violet' => '#9400D3',
            'medium violet red' => '#C71585',
            'magenta' => '#FF00FF',
            'fuchsia' => '#FF00FF',
            'lavender' => '#E6E6FA',
            'thistle' => '#D8BFD8',
            'mauve' => '#E0B0FF',

            // Yellows & Oranges
            'yellow' => '#F59E0B',
            'gold' => '#FFD700',
            'light yellow' => '#FFFFE0',
            'pale yellow' => '#FFFFE0',
            'light goldenrod yellow' => '#FAFAD2',
            'dark yellow' => '#CCCC00',
            'orange' => '#F97316',
            'dark orange' => '#FF8C00',
            'orange red' => '#FF4500',
            'orange yellow' => '#FFA500',
            'light orange' => '#FFA500',
            'burnt orange' => '#CC5500',
            'apricot' => '#FBBC04',
            'peach' => '#FFDAB9',
            'moccasin' => '#FFE4B5',
            'papaya whip' => '#FFEFD5',
            'bisque' => '#FFE4C4',
            'tan' => '#D2B48C',
            'wheat' => '#F5DEB3',
            'burlywood' => '#DEB887',
            'rust' => '#B7410E',

            // Browns
            'brown' => '#92400E',
            'dark brown' => '#3E2723',
            'light brown' => '#A0522D',
            'saddle brown' => '#8B4513',
            'sienna' => '#A0522D',
            'dark sienna' => '#3C1410',
            'chocolate' => '#D2691E',
            'peru' => '#CD853F',
            'tan brown' => '#8B7355',
            'rosy brown' => '#BC8F8F',
            'coffee' => '#6F4E37',
            'tan' => '#D2B48C',
            'taupe' => '#B38B6D',
            'chestnut' => '#954535',
            'cognac' => '#A0351A',
            'caramel' => '#A67C52',
            'copper' => '#B87333',
            'bronze' => '#CD7F32',
            'terracotta' => '#E2725B',

            // Specialty & Fashion Colors
            'nude' => '#E8B4A8',
            'nude beige' => '#EFD5BC',
            'blush' => '#F0A4A8',
            'mauve' => '#E0B0FF',
            'champagne' => '#F7E7CE',
            'metallic gold' => '#FFD700',
            'metallic silver' => '#E8E8E8',
            'metallic copper' => '#E0A76D',
            'holographic' => '#D4ADFF',
            'pearl' => '#FDEEF4',
            'iridescent' => '#D4ADFF',
            'multicolor' => '#FFFFFF',
            'multi' => '#FFFFFF',
            'stripe' => '#999999',
            'stripes' => '#999999',
            'plaid' => '#999999',
            'checkered' => '#999999',
            'animal print' => '#8B7355',
            'leopard' => '#8B7355',
            'camouflage' => '#5C7C59',
            'camo' => '#5C7C59',
            'floral' => '#FF69B4',
            'neon' => '#FFFF00',
            'neon pink' => '#FF10F0',
            'neon green' => '#39FF14',
            'neon yellow' => '#CFFF00',
        ];

        $key = strtolower(trim($name));
        return $map[$key] ?? '#999999';
    }

    public function getAvailableMaterialsProperty()
    {
        if (!$this->product) return collect([]);

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
        if (!$this->selectedVariantId) return null;
        return ProductVariant::find($this->selectedVariantId);
    }

    public function getFormattedPriceProperty()
    {
        if (!$this->product) return '';

        // Use variant price if available, otherwise product price
        $price = $this->currentVariant?->price ?? ($this->product->sale_price ?: $this->product->price);
        $amount = (is_int($price) || ctype_digit((string)$price))
            ? (float)$price / 100
            : (float)$price;

        return money_format_ugx($amount);
    }

    public function getOriginalPriceProperty()
    {
        if (!$this->product || (!$this->product->sale_price && !$this->currentVariant)) return '';

        // Use variant price if available for comparison
        if ($this->currentVariant && $this->currentVariant->price) {
            $price = $this->currentVariant->price;
        } else {
            $price = $this->product->price;
        }

        $amount = (is_int($price) || ctype_digit((string)$price))
            ? (float)$price / 100
            : (float)$price;

        return money_format_ugx($amount);
    }

    public function render()
    {
        return view('livewire.components.product-quick-view');
    }
}
