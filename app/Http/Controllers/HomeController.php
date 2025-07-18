<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::active()
            ->featured()
            ->with(['categories', 'media'])
            ->limit(8)
            ->get();

        // Get latest products
        $latestProducts = Product::active()
            ->with(['categories', 'media'])
            ->latest()
            ->limit(8)
            ->get();

        // Get on sale products
        $onSaleProducts = Product::active()
            ->onSale()
            ->with(['categories', 'media'])
            ->limit(8)
            ->get();

        // Get main categories
        $categories = Category::active()
            ->parent()
            ->withCount('products')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return view('home', compact(
            'featuredProducts',
            'latestProducts',
            'onSaleProducts',
            'categories'
        ));
    }
}
