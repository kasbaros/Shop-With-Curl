<?php
//
//namespace App\Http\Controllers\Guest;
//
//use App\Http\Controllers\Controller;
//use App\Models\Product;
//use Illuminate\Http\Request;
//
//class ProductController extends Controller
//{
//    public function index(Request $request)
//    {
//        return view('livewire.guest.products.product-grid');
//    }
//
//    public function show(Product $product)
//    {
//        // Check if product is active
//        if (!$product->is_active) {
//            abort(404);
//        }
//
//        // Load necessary relationships
//        $product->load([
//            'categories',
//            'variants' => function ($query) {
//                $query->where('is_active', true);
//            },
//            'reviews' => function ($query) {
//                $query->approved()->with('user')->latest();
//            },
//            'media'
//        ]);
//
//        return view('products.show', compact('product'));
//    }
//
//    public function search(Request $request)
//    {
//        $query = $request->get('q');
//
//        if (!$query) {
//            return redirect()->route('products.index');
//        }
//
//        return view('products.search', compact('query'));
//    }
//}


namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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

        return view('products.show', compact('product'));
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
}
