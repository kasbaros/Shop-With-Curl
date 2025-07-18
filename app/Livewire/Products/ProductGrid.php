<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

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

    public function setSortBy($sortBy, $order = 'asc')
    {
        $this->sortBy = $sortBy;
        $this->sortOrder = $order;
        $this->resetPage();
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->active()
            ->with(['categories', 'reviews', 'media'])
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
                $query->whereNotNull('sale_price');
            })
            ->when($this->featuredOnly, function ($query) {
                $query->where('is_featured', true);
            })
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->perPage);
    }

    public function getCategoriesProperty()
    {
        return Category::active()
            ->withCount('products')
            ->orderBy('name')
            ->get();
    }

    public function getPriceRangeProperty()
    {
        $products = Product::active();

        return [
            'min' => $products->min('price'),
            'max' => $products->max('price'),
        ];
    }

    public function render()
    {
        return view('livewire.products.product-grid');
    }
}
