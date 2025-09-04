<?php

namespace App\Livewire\Guest;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size as ProductSize;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Image\Size;

#[Title('Categories - ShopWithCarl')]
#[Layout('components.app-layout')]
class CategoryGrid extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedType = '';
    public $availability = 'all';
    public $layout = 'grid-3';
    public $sortBy = 'featured';
    public $selectedBrands = [];
    public $selectedColors = [];
    public $selectedSizes = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedType' => ['except' => ''],
        'availability' => ['except' => 'all'],
        'layout' => ['except' => 'grid-3'],
        'sortBy' => ['except' => 'featured'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedType()
    {
        $this->resetPage();
    }

    public function updatingAvailability()
    {
        $this->resetPage();
    }

    public function updatingSelectedBrands()
    {
        $this->resetPage();
    }

    public function updatingSelectedColors()
    {
        $this->resetPage();
    }

    public function updatingSelectedSizes()
    {
        $this->resetPage();
    }

    public function setType($type)
    {
        $this->selectedType = $type;
        $this->resetPage();
    }

    public function setLayout($layout): void
    {
        $this->layout = $layout;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        $this->resetPage();
    }

    public function render()
    {
        // Start with all active categories that have children (the main category types)
        $query = Category::query()
            ->active()
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->active()->withCount('products');
            }]);

        // Get the main category types
        $types = $query->orderBy('sort_order')->get()->keyBy(fn ($c) => $c->name);

        // Now build a unified categories collection for the template-style display
        $categoriesQuery = Category::query()
            ->active()
            ->whereNotNull('parent_id') // Get child categories (the actual product categories)
            ->withCount('products')
            ->with(['parent']);

        // Apply search filter
        if ($this->search) {
            $categoriesQuery->where('name', 'like', '%' . $this->search . '%')
                           ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        // Apply type filter (based on parent category)
        if ($this->selectedType) {
            $categoriesQuery->whereHas('parent', function($q) {
                $q->where('slug', $this->selectedType);
            });
        }

        // Apply availability filter
        switch ($this->availability) {
            case 'with_products':
                $categoriesQuery->has('products');
                break;
            case 'empty':
                $categoriesQuery->doesntHave('products');
                break;
            case 'all':
            default:
                // No additional filter needed
                break;
        }

        // Apply brand filter
        if (!empty($this->selectedBrands)) {
            $categoriesQuery->whereHas('products.brand', function($q) {
                $q->whereIn('brands.id', $this->selectedBrands);
            });
        }

        // Apply color filter
        if (!empty($this->selectedColors)) {
            $categoriesQuery->whereHas('products.colors', function($q) {
                $q->whereIn('colors.value', $this->selectedColors);
            });
        }

        // Apply size filter
        if (!empty($this->selectedSizes)) {
            $categoriesQuery->whereHas('products.sizes', function($q) {
                $q->whereIn('sizes.value', $this->selectedSizes);
            });
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'name_asc':
                $categoriesQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $categoriesQuery->orderBy('name', 'desc');
                break;
            case 'created_at_desc':
                $categoriesQuery->orderBy('created_at', 'desc');
                break;
            case 'created_at_asc':
                $categoriesQuery->orderBy('created_at', 'asc');
                break;
            case 'featured':
            default:
                $categoriesQuery->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
                break;
        }

        $categories = $categoriesQuery->paginate(12);

        // Get filter options from products in categories
        $brands = Brand::whereHas('products.categories', function($q) {
            $q->whereNotNull('parent_id'); // Only consider child categories
        })->withCount('products')->get();

        $colors = Color::whereHas('products.categories', function($q) {
            $q->whereNotNull('parent_id');
        })->withCount('products')->get();

        $sizes = ProductSize::whereHas('products.categories', function($q) {
            $q->whereNotNull('parent_id');
        })->withCount('products')->get();

        // Also prepare the legacy data structure for backward compatibility
        $filter = fn ($children) => $children->when($this->search, function ($q) {
            return $q->filter(function ($item) {
                return stripos($item->name, $this->search) !== false;
            });
        });

        $byStyle = isset($types['By Style']) ? $filter($types['By Style']->children) : collect();
        $byOccasion = isset($types['By Occasion']) ? $filter($types['By Occasion']->children) : collect();
        $collections = isset($types['Collections']) ? $filter($types['Collections']->children) : collect();

        // Prepare sidebar dynamic data
        $saleProducts = \App\Models\Product::query()
            ->where('is_active', true)
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'price')
            ->latest('updated_at')
            ->take(3)
            ->get(['id','name','slug','price','sale_price']);

        // Build a simple gallery list from recent product media or fallback to category thumbnails
        $galleryImages = [];
        foreach (\App\Models\Product::query()->where('is_active', true)->latest('published_at')->take(12)->get() as $p) {
            $url = $p->getFirstMediaUrl('gallery') ?: $p->getFirstMediaUrl('images');
            if ($url) {
                $galleryImages[] = [
                    'url' => $url,
                    'href' => route('products.show', $p->slug),
                    'alt' => $p->name,
                ];
            }
            if (count($galleryImages) >= 6) break;
        }
        if (empty($galleryImages)) {
            foreach ($categories->items() as $cat) {
                $url = $cat->thumbnail_url ?? null;
                if ($url) {
                    $galleryImages[] = [
                        'url' => $url,
                        'href' => route('products.category', $cat->slug),
                        'alt' => $cat->name,
                    ];
                }
                if (count($galleryImages) >= 6) break;
            }
        }

        // Get parent categories for filter
        $parentCategories = Category::active()
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('livewire.guest.categories.category-grid', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
            'brands' => $brands,
            'colors' => $colors,
            'sizes' => $sizes,
            'hasFilters' => $this->hasActiveFilters(),
            // Legacy data for backward compatibility
            'byStyle' => $byStyle,
            'byOccasion' => $byOccasion,
            'collections' => $collections,
            // Sidebar dynamic data
            'saleProducts' => $saleProducts,
            'galleryImages' => $galleryImages,
        ]);
    }

    private function hasActiveFilters()
    {
        return !empty($this->search) ||
               !empty($this->selectedType) ||
               $this->availability !== 'all' ||
               !empty($this->selectedBrands) ||
               !empty($this->selectedColors) ||
               !empty($this->selectedSizes);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->selectedType = '';
        $this->availability = 'all';
        $this->selectedBrands = [];
        $this->selectedColors = [];
        $this->selectedSizes = [];
        $this->resetPage();
    }

    public function updatedSearch()
    {
        // Live updates handled via query string + re-render
    }
}
