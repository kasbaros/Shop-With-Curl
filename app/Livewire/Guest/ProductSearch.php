<?php

    namespace App\Livewire\Guest;

    use App\Models\Product;
    use Livewire\Attributes\Layout;
    use Livewire\Attributes\Title;
    use Livewire\Component;
    use Livewire\Attributes\Url;


    #[Title('Shop - ShopWithCarl')]
    #[Layout('components.app-layout')]
    class ProductSearch extends Component
    {
        #[Url(as: 'q')]
        public $query = '';

        public $results = [];
        public $showResults = false;

        public function updatedQuery()
        {
            if (strlen($this->query) < 2) {
                $this->results = [];
                $this->showResults = false;
                return;
            }

            $this->results = Product::search($this->query)
                ->where('is_active', true)
                ->take(8)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->effective_price,
                        'image' => $product->featured_image,
                        'category' => $product->categories->first()?->name,
                    ];
                });

            $this->showResults = true;
        }

        public function selectResult($slug)
        {
            $this->redirect(route('products.show', $slug));
        }

        public function hideResults()
        {
            $this->showResults = false;
        }

        public function showResults()
        {
            if (!empty($this->results)) {
                $this->showResults = true;
            }
        }

        public function search()
        {
            if (strlen($this->query) >= 2) {
                $this->redirect(route('products.index', ['q' => $this->query]));
            }
        }

        public function render()
        {
            // Point to the correct Blade view location under resources/views/livewire/guest/products/
            return view('livewire.guest.products.product-search');
        }
    }
