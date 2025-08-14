<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class ShopController extends Controller
{
    // index() method removed - now handled by ShopGrid Livewire component

    /**
     * Handle category-specific shop page with complex logic
     * (Keep this if you need complex category processing)
     */
    public function category(Category $category): View
    {
        // Complex category logic, SEO meta, analytics tracking, etc.
        $category->load(['children', 'parent']);

        // Query products that belong to this category
        // You might have complex business rules here that don't belong in a Livewire component
        $products = \App\Models\Product::whereHas('categories', function($query) use ($category) {
            $query->where('categories.id', $category->id);
        })
        ->active()
        ->with(['categories', 'media', 'variants'])
        ->paginate(12);

        // Define sort options for the template dropdown
        $sortOptions = [
            'featured' => 'Featured',
            'name' => 'Best selling',
            'name_asc' => 'Alphabetically, A-Z',
            'name_desc' => 'Alphabetically, Z-A',
            'price_asc' => 'Price, low to high',
            'price_desc' => 'Price, high to low',
            'created_asc' => 'Date, old to new',
            'created_desc' => 'Date, new to old',
        ];

        // Set default sort by
        $sortBy = 'featured';

        // Set the selected category to the current category's ID
        $selectedCategory = $category->id;

        // Get all categories for the filter
        $categories = Category::active()->withCount('products')->orderBy('name')->get();

        // Initialize price filter variables
        $minPrice = null;
        $maxPrice = null;

        // Initialize filter options
        $inStockOnly = false;
        $onSaleOnly = false;
        $featuredOnly = false;

        return view('livewire.guest.shop.shop-grid', compact(
            'category',
            'products',
            'sortBy',
            'sortOptions',
            'selectedCategory',
            'categories',
            'minPrice',
            'maxPrice',
            'inStockOnly',
            'onSaleOnly',
            'featuredOnly'
        ));
    }

}
