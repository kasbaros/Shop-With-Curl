<?php

    namespace App\Livewire\Guest;

    use App\Models\Product;
    use App\Models\Category;
    use Livewire\Component;
    use Livewire\WithPagination;
    use Livewire\Attributes\Title;
    use Livewire\Attributes\Layout;
    use Livewire\Attributes\Url;

    #[Title('Shop - ShopWithCarl')]
    #[Layout('components.app-layout')]

    class ShopGrid extends Component
    {
        use WithPagination;

        #[Url(as: 'search')]
        public $search = '';

        #[Url(as: 'category')]
        public $selectedCategory = '';

        #[Url(as: 'sort')]
        public $sortBy = 'name';

        #[Url(as: 'min_price')]
        public $minPrice = '';

        #[Url(as: 'max_price')]
        public $maxPrice = '';

        #[Url(as: 'in_stock')]
        public $inStockOnly = false;

        public $perPage = 12;

        public function mount()
        {
            // Initialize any default values if needed
        }

        public function updatedSearch()
        {
            $this->resetPage();
        }

        public function updatedSelectedCategory()
        {
            $this->resetPage();
        }

        public function updatedSortBy()
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

        public function clearFilters()
        {
            $this->reset(['search', 'selectedCategory', 'minPrice', 'maxPrice', 'inStockOnly']);
            $this->sortBy = 'name';
            $this->resetPage();
        }

        public function render()
        {
            $products = Product::query()
                ->active()
                ->published()
                ->with(['categories', 'media'])
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('short_description', 'like', '%' . $this->search . '%');
                })
                ->when($this->selectedCategory, function ($query) {
                    $query->whereHas('categories', function ($q) {
                        $q->where('categories.id', $this->selectedCategory);
                    });
                })
                ->when($this->minPrice, function ($query) {
                    $query->where('price', '>=', $this->minPrice * 100); // Convert to cents
                })
                ->when($this->maxPrice, function ($query) {
                    $query->where('price', '<=', $this->maxPrice * 100); // Convert to cents
                })
                ->when($this->inStockOnly, function ($query) {
                    $query->where('stock_quantity', '>', 0);
                })
                ->when($this->sortBy === 'name', function ($query) {
                    $query->orderBy('name');
                })
                ->when($this->sortBy === 'price_low_high', function ($query) {
                    $query->orderBy('price');
                })
                ->when($this->sortBy === 'price_high_low', function ($query) {
                    $query->orderByDesc('price');
                })
                ->when($this->sortBy === 'newest', function ($query) {
                    $query->orderByDesc('created_at');
                })
                ->when($this->sortBy === 'popular', function ($query) {
                    $query->orderByDesc('views_count');
                })
                ->paginate($this->perPage);

            $categories = Category::active()
                ->withCount('products')
                ->orderBy('name')
                ->get();

            return view('livewire.guest.shop.shop-grid', [
                'products' => $products,
                'categories' => $categories,
            ]);
        }
    }
