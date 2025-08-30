<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\WishList;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    /**
     * Display the product comparison page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // In a real application, you would fetch the compared products from the session or database
        $comparedProducts = session('compared_products', []);

        return view('compare.index', compact('comparedProducts'));
    }

    /**
     * Add a product to the comparison list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->input('product_id');

        // In a real application, you would add the product to the comparison list in the session or database
        $comparedProducts = session('compared_products', []);

        if (!in_array($productId, $comparedProducts)) {
            $comparedProducts[] = $productId;
            session(['compared_products' => $comparedProducts]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to comparison list.',
            'count' => count($comparedProducts)
        ]);
    }

    /**
     * Remove a product from the comparison list.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function remove($product)
    {
        // In a real application, you would remove the product from the comparison list in the session or database
        $comparedProducts = session('compared_products', []);

        $comparedProducts = array_filter($comparedProducts, function($id) use ($product) {
            return $id != $product;
        });

        session(['compared_products' => array_values($comparedProducts)]);

        return redirect()->route('compare.index')
            ->with('success', 'Product removed from comparison list.');
    }

    /**
     * Display the user's wishlist page.
     *
     * @return \Illuminate\Contracts\View\View
     */

    /**
     * Add or toggle a product in the user's wishlist.
     */
    public function addToWishlist(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = auth()->id();
        $productId = (int) $validated['product_id'];

        // Toggle behavior: if exists -> remove, else -> create
        $existing = WishList::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Product removed from wishlist.';
        } else {
            WishList::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $message = 'Product added to wishlist.';
        }

        $count = WishList::where('user_id', $userId)->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count,
        ]);
    }

    /**
     * Remove a product from the user's wishlist.
     */
    public function removeFromWishlist($product)
    {
        $userId = auth()->id();

        WishList::where('user_id', $userId)
            ->where('product_id', (int) $product)
            ->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Removed from wishlist.']);
        }

        return redirect()->route('account.page', ['section' => 'wishlist'])
            ->with('success', 'Removed from wishlist.');
    }
}
