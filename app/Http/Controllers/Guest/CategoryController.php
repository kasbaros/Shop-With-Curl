<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return Factory|View|Application|\Illuminate\View\View|object
     */
    public function index()
    {
        $categories = Category::active()
            ->parent()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category.
     *
     * @param Category $category
     * @return Factory|View|Application|\Illuminate\View\View|object
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }
}
