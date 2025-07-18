<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->parent()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        // Check if category is active
        if (!$category->is_active) {
            abort(404);
        }

        // Load children categories
        $category->load(['children' => function ($query) {
            $query->active()->withCount('products');
        }]);

        return view('categories.show', compact('category'));
    }
}
