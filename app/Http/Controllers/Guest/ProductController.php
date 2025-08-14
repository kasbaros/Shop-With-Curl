<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    // REMOVE: index method - now handled by ProductGrid Livewire component
    // public function index() - DELETE THIS METHOD

    public function show(Product $product)
    {
        // Keep exactly as-is - no changes
        if (!$product->is_active) {
            abort(404);
        }

        $product->load([
            'categories',
            'variants' => function ($query) {
                $query->where('is_active', true);
            },
            'reviews' => function ($query) {
                $query->approved()->with('user')->latest();
            },
            'media'
        ]);

        // Prepare the image data that would normally be provided by the Livewire component
        $images = $product->gallery_images ?? [];
        if (empty($images) && $product->featured_image) {
            $images = [[
                'url' => $product->featured_image,
                'thumb' => $product->featured_image,
                'large' => $product->featured_image,
            ]];
        } elseif (empty($images)) {
            $images = [[
                'url' => '/images/placeholder.png',
                'thumb' => '/images/placeholder.png',
                'large' => '/images/placeholder.png',
            ]];
        }

        $selectedImageIndex = 0;
        $selectedImage = $images[$selectedImageIndex] ?? null;

        // Get related products
        $relatedProducts = Product::active()
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        // Instead of rendering the view directly, we'll use the app-layout by returning a view
        // that extends the app-layout and embeds the Livewire component with necessary data
        return view('components.app-layout', [
            'slot' => view('livewire.guest.products.product-detail', [
                'product' => $product,
                'images' => $images,
                'selectedImage' => $selectedImage,
                'selectedImageIndex' => $selectedImageIndex,
                'relatedProducts' => $relatedProducts
            ])
        ]);
    }

    // Keep other methods exactly as-is
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->route('products.index');
        }

        return view('products.search', compact('query'));
    }

    /**
     * Display products for a specific category
     */
    public function category(Category $category): View
    {
        // Fetch products for this category so they're available in the view
        $products = $category->products()
            ->active()
            ->published()
            ->take(8)
            ->get();

        // Calculate if there are more products than what we're showing
        $hasMoreProducts = $category->products()->active()->published()->count() > $products->count();

        // Instead of rendering the view directly, we'll use the app-layout by returning a view
        // that extends the app-layout and embeds the Livewire component
        return view('components.app-layout', [
            'slot' => view('livewire.guest.categories.category-detail', [
                'category' => $category,
                'products' => $products,
                'hasMoreProducts' => $hasMoreProducts
            ])
        ]);
    }
}
