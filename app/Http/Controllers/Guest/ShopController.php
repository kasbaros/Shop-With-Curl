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
        $category->load(['products', 'children', 'parent']);

        // You might have complex business rules here
        // that don't belong in a Livewire component

        return view('livewire.guest.shop.shop-grid', compact('category'));
    }

}
