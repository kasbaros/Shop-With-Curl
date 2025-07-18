<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return view('products.index');
    }

    public function show(Product $product)
    {
        // Check if product is active
        if (!$product->is_active) {
            abort(404);
        }

        // Load necessary relationships
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

        return view('products.show', compact('product'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->route('products.index');
        }

        return view('products.search', compact('query'));
    }
}
