<?php

    namespace App\Livewire\Guest;

    use App\Models\Product;
    use App\Models\Category;
    use Livewire\Component;
    use Livewire\WithPagination;
    use Livewire\Attributes\Title;
    use Livewire\Attributes\Layout;
    use Livewire\Attributes\Url;
    use Illuminate\Support\Facades\Log;

    #[Title('Shop - ShopWithCarl')]
    #[Layout('components.app-layout')]
    class ProductGrid extends Component
    {
        use WithPagination;

        #[Url(as: 'category')]
        public $selectedCategory = null;

        #[Url(as: 'sort')]
        public $sortBy = 'name';

        #[Url(as: 'order')]
        public $sortOrder = 'asc';

        #[Url(as: 'min_price')]
        public $minPrice = null;

        #[Url(as: 'max_price')]
        public $maxPrice = null;

        #[Url(as: 'in_stock')]
        public $inStockOnly = false;

        #[Url(as: 'on_sale')]
        public $onSaleOnly = false;

        #[Url(as: 'featured')]
        public $featuredOnly = false;

        public $perPage = 12;

        // Add sort options for the template dropdown
        public $sortOptions = [
            'featured' => 'Featured',
            'name' => 'Best selling',
            'name_asc' => 'Alphabetically, A-Z',
            'name_desc' => 'Alphabetically, Z-A',
            'price_asc' => 'Price, low to high',
            'price_desc' => 'Price, high to low',
            'created_asc' => 'Date, old to new',
            'created_desc' => 'Date, new to old',
        ];

        public function updatedSelectedCategory()
        {
            $this->resetPage();
        }

        public function updatedSortBy()
        {
            $this->resetPage();
        }

        public function updatedSortOrder()
        {
            $this->resetPage();
        }

        public function updatedMinPrice()
        {
            $this->resetPage();
        }

        public function updatedMaxPrice()
        {
            $this->resetPage();
        }

        public function updatedInStockOnly()
        {
            $this->resetPage();
        }

        public function updatedOnSaleOnly()
        {
            $this->resetPage();
        }

        public function updatedFeaturedOnly()
        {
            $this->resetPage();
        }

        public function clearFilters()
        {
            $this->selectedCategory = null;
            $this->minPrice = null;
            $this->maxPrice = null;
            $this->inStockOnly = false;
            $this->onSaleOnly = false;
            $this->featuredOnly = false;
            $this->resetPage();
        }

        // Add the missing methods
        public function clearPriceFilter()
        {
            $this->minPrice = null;
            $this->maxPrice = null;
            $this->resetPage();
        }

        public function hasActiveFilters(): bool
        {
            return (bool) (
                $this->selectedCategory !== null && $this->selectedCategory !== ''
                || ($this->minPrice !== null && $this->minPrice !== '')
                || ($this->maxPrice !== null && $this->maxPrice !== '')
                || $this->inStockOnly
                || $this->onSaleOnly
                || $this->featuredOnly
            );
        }

        public function toggleWishlist($productId)
        {
            // Add wishlist logic here
            if (auth()->check()) {
                // Handle wishlist toggle
                // You can implement this based on your wishlist system
            } else {
                // Redirect to login or show message
                session()->flash('message', 'Please login to add items to wishlist');
            }
        }

        public function toggleCompare($productId)
        {
            // Add compare logic here
            // You can store compare items in session or database
        }

        public function getColorCode($colorName)
        {
            $colorMap = [
                'orange' => '#ff6600',
                'black' => '#000000',
                'white' => '#ffffff',
                'red' => '#ff0000',
                'blue' => '#0066cc',
                'green' => '#00cc66',
                'yellow' => '#ffcc00',
                'purple' => '#9966cc',
                'pink' => '#ff99cc',
                'brown' => '#996633',
                'gray' => '#666666',
                'grey' => '#666666',
            ];

            return $colorMap[strtolower($colorName)] ?? '#cccccc';
        }

        public function setSortBy($sortBy, $order = 'asc')
        {
            $this->sortBy = $sortBy;
            $this->sortOrder = $order;
            $this->resetPage();
        }

        // Update the render method to handle the new sorting system
        public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
        {
            $query = Product::query()
                ->active()
                ->published()
                ->with(['categories', 'media'])
                ->when($this->selectedCategory, function ($query) {
                    $query->whereHas('categories', function ($q) {
                        $q->where('categories.id', $this->selectedCategory);
                    });
                })
                ->when($this->minPrice, function ($query) {
                    $query->where('price', '>=', $this->minPrice);
                })
                ->when($this->maxPrice, function ($query) {
                    $query->where('price', '<=', $this->maxPrice);
                })
                ->when($this->inStockOnly, function ($query) {
                    $query->where('stock_quantity', '>', 0);
                })
                ->when($this->onSaleOnly, function ($query) {
                    $query->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price');
                })
                ->when($this->featuredOnly, function ($query) {
                    $query->where('is_featured', true);
                });

            // Handle the new sorting options
            switch ($this->sortBy) {
                case 'featured':
                    $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderByRaw('COALESCE(sale_price, price) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('COALESCE(sale_price, price) DESC');
                    break;
                case 'created_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'created_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy($this->sortBy, $this->sortOrder);
                    break;
            }

            $products = $query->paginate($this->perPage);

            $categories = Category::active()
                ->withCount('products')
                ->orderBy('name')
                ->get();

            // Debug the $categories variable
            Log::info('Categories: ', $categories->toArray());

            return view('livewire.guest.products.product-grid', [
                'products' => $products,
                'categories' => $categories,
            ]);
        }
    }
