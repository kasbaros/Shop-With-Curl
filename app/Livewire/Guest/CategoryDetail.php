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
        public $search = ''; // Add this property

        public function mount(Category $category)
        {
            // Check if category is active
            if (!$category->is_active) {
                abort(404);
            }

            $this->category = $category;
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

        public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
        {
            // Load children categories with products count and apply search if needed
            $this->category->load(['children' => function ($query) {
                $query->active()
                    ->withCount('products')
                    ->when($this->search, function ($subQuery) {
                        $subQuery->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('description', 'like', '%' . $this->search . '%');
                    });
            }]);

            // Load products for this category
            $products = $this->category->products()
                ->active()
                ->published()
                ->take($this->productsLimit)
                ->get();

            // Get filtered subcategories based on search
            $categories = $this->category->children;

            return view('livewire.guest.categories.category-detail', [
                'products' => $products,
                'categories' => $categories,
                'hasMoreProducts' => $this->category->products()->active()->published()->count() > $this->productsLimit
            ]);
        }
    }
