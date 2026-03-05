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
        public $sortBy = 'popular';

        #[Url(as: 'min_price')]
        public $minPrice = '';

        #[Url(as: 'max_price')]
        public $maxPrice = '';

        #[Url(as: 'in_stock')]
        public $inStockOnly = false;

        public $perPage = 12;

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
            $this->sortBy = 'popular';
            $this->resetPage();
        }

        public function render()
        {
            $query = Product::query()
                ->active()
                ->published()
                ->with(['categories', 'media']);

            if ($this->search) {
                $search = $this->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'ilike', '%' . $search . '%')
                      ->orWhere('description', 'ilike', '%' . $search . '%')
                      ->orWhere('short_description', 'ilike', '%' . $search . '%');
                });
            }

            if ($this->selectedCategory) {
                $selected = $this->selectedCategory;
                $query->whereHas('categories', function ($q) use ($selected) {
                    if (is_numeric($selected)) {
                        $q->where('categories.id', (int) $selected);
                    } else {
                        $q->where('categories.slug', $selected);
                    }
                });
            }

            if ($this->minPrice) {
                $query->where('price', '>=', (float) $this->minPrice);
            }

            if ($this->maxPrice) {
                $query->where('price', '<=', (float) $this->maxPrice);
            }

            if ($this->inStockOnly) {
                $query->where('stock_quantity', '>', 0);
            }

            match ($this->sortBy) {
                'name' => $query->orderBy('name'),
                'price_low_high' => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
                'price_high_low' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
                'newest' => $query->orderByDesc('created_at'),
                default => $query->orderByDesc('created_at'), // 'popular' fallback
            };

            $products = $query->paginate($this->perPage);

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
