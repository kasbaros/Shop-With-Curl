<?php

namespace App\Livewire\Guest;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Shop - ShopWithCarl')]
#[Layout('components.app-layout')]

class CategoryDetail extends Component
{
    public Category $category;
    public $showSubcategories = true;
    public $showProducts = true;
    public $productsLimit = 8;
    public $search = '';
    public $layout = 'grid-3';
    public $sortBy = 'featured';
    public $selectedBrands = [];
    public $selectedColors = [];
    public $selectedSizes = [];
    public $hasFilters = false;

    public function mount(Category $category)
    {
        // Check if category is active
        if (!$category->is_active) {
            abort(404);
        }

        $this->category = $category;
        // Optionally set initial layout from query string if needed
        $this->layout = request()->query('layout', 'grid-3');
        $this->sortBy = request()->query('sortBy', 'featured');
    }

    public function title()
    {
        return $this->category->meta_title ?: $this->category->name . ' - ShopWithCarl';
    }

    public function loadMoreProducts(): void
    {
        $this->productsLimit += 8;
    }

    public function toggleSubcategories(): void
    {
        $this->showSubcategories = !$this->showSubcategories;
    }

    public function setLayout($layout): void
    {
        $this->layout = $layout;
    }

    public function setSortBy($sortBy): void
    {
        $this->sortBy = $sortBy;
    }

    public function toggleWishlist($productId): void
    {
        // Add your wishlist logic here
        // Example: $this->dispatch('wishlist-toggled', $productId);
    }

    public function toggleCompare($productId): void
    {
        // Add your compare logic here
        // Example: $this->dispatch('compare-toggled', $productId);
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->selectedBrands = [];
        $this->selectedColors = [];
        $this->selectedSizes = [];
        $this->hasFilters = false;
    }

    public function getColorCode($colorName): string
    {
        // Simple color mapping - you might want to store actual color codes in your database
        $colorMap = [
            'red' => '#ff0000',
            'blue' => '#0000ff',
            'green' => '#00ff00',
            'black' => '#000000',
            'white' => '#ffffff',
            'pink' => '#ffc0cb',
            'yellow' => '#ffff00',
            'purple' => '#800080',
            'orange' => '#ffa500',
            'gray' => '#808080',
            'grey' => '#808080',
        ];

        return $colorMap[strtolower($colorName)] ?? '#cccccc';
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        // Load children categories with products count and apply search if needed
        $this->category->load(['children' => function ($query) {
            $query->where('is_active', true)
                ->withCount('products')
                ->when($this->search, function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
        }]);

        // Load products for this category with variants eager-loaded
        $productsQuery = $this->category->products()
            ->with(['variants', 'brand'])
            ->where('is_active', true)
            ->where('status', 'published');

        // Apply search to products
        if ($this->search) {
            $productsQuery->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting to products
        switch ($this->sortBy) {
            case 'name_asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $productsQuery->orderBy('name', 'desc');
                break;
            case 'created_at_desc':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $productsQuery->orderBy('created_at', 'asc');
                break;
            case 'featured':
            default:
                $productsQuery->orderBy('id', 'desc');
                break;
        }

        // Apply brand filter if available
        if (!empty($this->selectedBrands)) {
            $productsQuery->whereIn('brand_id', $this->selectedBrands);
        }

        // Apply color filter if available
        if (!empty($this->selectedColors)) {
            $productsQuery->whereHas('variants', function($query) {
                $query->whereIn('color', $this->selectedColors);
            });
        }

        // Apply size filter if available
        if (!empty($this->selectedSizes)) {
            $productsQuery->whereHas('variants', function($query) {
                $query->whereIn('size', $this->selectedSizes);
            });
        }

        $products = $productsQuery->paginate($this->productsLimit);

        // Get filtered subcategories based on search
        $categories = $this->category->children;

        // Fetch brands, colors, sizes for filters
        $brands = collect(); // Initialize empty collection
        $colors = collect();
        $sizes = collect();

        // Only fetch filter data if there are products
        if ($products->count() > 0) {
            // Get brands from products in this category
            $brands = \App\Models\Brand::whereHas('products', function($query) {
                $query->whereHas('categories', function($catQuery) {
                    $catQuery->where('categories.id', $this->category->id);
                });
            })->withCount(['products' => function($query) {
                $query->whereHas('categories', function($catQuery) {
                    $catQuery->where('categories.id', $this->category->id);
                });
            }])->get();

            // Get colors from product variants in this category
            $colors = \App\Models\ProductVariant::whereNotNull('color')
                ->whereHas('product.categories', function($query) {
                    $query->where('categories.id', $this->category->id);
                })
                ->selectRaw('color as name, color, COUNT(*) as products_count')
                ->groupBy('color')
                ->get();

            // Get sizes from product variants in this category
            $sizes = \App\Models\ProductVariant::whereNotNull('size')
                ->whereHas('product.categories', function($query) {
                    $query->where('categories.id', $this->category->id);
                })
                ->selectRaw('size as value, COUNT(*) as products_count')
                ->groupBy('size')
                ->get();
        }

        // Determine if any filters are active
        $this->hasFilters = !empty($this->search) ||
            !empty($this->selectedBrands) ||
            !empty($this->selectedColors) ||
            !empty($this->selectedSizes);

        return view('livewire.guest.categories.category-detail', [
            'products' => $products,
            'categories' => $categories,
            'hasMoreProducts' => $products->hasMorePages(),
            'brands' => $brands,
            'colors' => $colors,
            'sizes' => $sizes,
            'hasFilters' => $this->hasFilters,
            'layout' => $this->layout,        // Explicitly pass layout
            'sortBy' => $this->sortBy,        // Explicitly pass sortBy
            'search' => $this->search,        // Explicitly pass search
        ]);
    }
}
