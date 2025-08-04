<?php

    namespace App\Livewire\Guest;

    use App\Models\Category;
    use Livewire\Attributes\Layout;
    use Livewire\Component;
    use Livewire\Attributes\Title;

    #[Title('Shop - ShopWithCarl')]
    #[Layout('components.app-layout')]

    class CategoryGrid extends Component
    {
        #[Title('Categories - ShopWithCarl')]

        public $search = '';

        protected $queryString = [
            'search' => ['except' => ''],
        ];

        public function render()
        {
            $categories = Category::active()
                ->parent()
                ->withCount('products')
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->orderBy('sort_order')
                ->get();

            return view('livewire.guest.categories.category-grid', [
                'categories' => $categories
            ]);
        }

        public function updatedSearch()
        {
            // This will automatically re-render the component when search changes
        }
    }
