<?php
//
//namespace App\Http\Controllers;
//
//use App\Models\Product;
//use App\Models\Category;
//use App\Traits\SharedLayoutData;
//use Illuminate\Http\Request;
//
//class HomeController extends Controller
//{
//    use SharedLayoutData;
//
//    public function index()
//    {
//        $layoutData = $this->getLayoutData();
//
//        // Get featured products
//        $featuredProducts = Product::active()
//            ->featured()
//            ->with(['categories', 'media'])
//            ->limit(8)
//            ->get();
//
//        // Get latest products
//        $latestProducts = Product::active()
//            ->with(['categories', 'media'])
//            ->latest()
//            ->limit(8)
//            ->get();
//
//        // Get on sale products
//        $onSaleProducts = Product::active()
//            ->onSale()
//            ->with(['categories', 'media'])
//            ->limit(8)
//            ->get();
//
//        // Get main categories
//        $categories = Category::active()
//            ->parent()
//            ->withCount('products')
//            ->orderBy('sort_order')
//            ->limit(6)
//            ->get();
//
//        'isAuthPage' => false;
//            'cartCount' => $layoutData['cartCount'] ?? 0;
//            'wishlistCount' => $layoutData['wishlistCount'] ?? 0;
//            'languages' => $layoutData['languages'] ?? config('app.languages', ['English']);
//            'selectedLanguage' => $layoutData['selectedLanguage'] ?? config('app.default_language', 'English');
//            'selectedCurrency' => $layoutData['selectedCurrency'] ?? config('currencies.default_currency', 'UGX');
//            'title' => 'Home';
//
//
//        return view('home', compact(
//            'featuredProducts',
//            'latestProducts',
//            'onSaleProducts',
//            'categories',
//            'layoutData',
//
//        ));
//    }
//}


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Traits\SharedLayoutData;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use SharedLayoutData;

    public function index()
    {
        $layoutData = $this->getLayoutData();

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

        // Prepare view data
        $viewData = [
            'isAuthPage' => false,
            'cartCount' => $layoutData['cartCount'] ?? 0,
            'wishlistCount' => $layoutData['wishlistCount'] ?? 0,
            'languages' => $layoutData['languages'] ?? config('app.languages', ['English']),
            'selectedLanguage' => $layoutData['selectedLanguage'] ?? config('app.default_language', 'English'),
            'selectedCurrency' => $layoutData['selectedCurrency'] ?? config('currencies.default_currency', 'UGX'),
            'title' => 'Home'
        ];

        // Merge layout data with view data
        $layoutData = array_merge($layoutData, $viewData);

        return view('home', compact(
            'featuredProducts',
            'latestProducts',
            'onSaleProducts',
            'categories',
            'layoutData'
        ));
    }
}
