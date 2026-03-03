<?php

namespace App\Livewire\Guest;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

#[Title('Search - ShopWithCarl')]
#[Layout('components.app-layout')]
class ProductSearch extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public $query = '';

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function selectResult($slug)
    {
        $this->redirect(route('products.show', $slug));
    }

    public function render()
    {
        $products = collect();

        if (strlen($this->query) >= 2) {
            $products = Product::where('is_active', true)
                ->where(function ($q) {
                    $q->where('name', 'ilike', "%{$this->query}%")
                      ->orWhere('description', 'ilike', "%{$this->query}%")
                      ->orWhere('sku', 'ilike', "%{$this->query}%");
                })
                ->latest()
                ->paginate(12);
        }

        return view('livewire.guest.products.product-search', [
            'products' => $products,
        ]);
    }
}
