<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;
    public $selectedImageIndex = 0;
    public $showSizeChart = false;

    public function mount(Product $product)
    {
        $this->product = $product->load(['categories', 'variants', 'reviews.user', 'media']);
    }

    public function selectImage($index)
    {
        $this->selectedImageIndex = $index;
    }

    public function toggleSizeChart()
    {
        $this->showSizeChart = !$this->showSizeChart;
    }

    public function getImagesProperty()
    {
        $images = $this->product->gallery_images;

        if (empty($images) && $this->product->featured_image) {
            $images = [[
                'url' => $this->product->featured_image,
                'thumb' => $this->product->featured_image,
                'large' => $this->product->featured_image,
            ]];
        }

        return $images ?: [[
            'url' => '/images/placeholder.png',
            'thumb' => '/images/placeholder.png',
            'large' => '/images/placeholder.png',
        ]];
    }

    public function getSelectedImageProperty()
    {
        $images = $this->images;
        return $images[$this->selectedImageIndex] ?? $images[0] ?? null;
    }

    public function getRelatedProductsProperty()
    {
        return Product::active()
            ->whereHas('categories', function ($query) {
                $query->whereIn('categories.id', $this->product->categories->pluck('id'));
            })
            ->where('id', '!=', $this->product->id)
            ->limit(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.products.product-detail');
    }
}
