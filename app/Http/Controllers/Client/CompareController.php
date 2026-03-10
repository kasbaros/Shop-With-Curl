<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\WishList;
use App\Services\CompareService;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(CompareService $compareService)
    {
        $products = $compareService->getProducts();

        return view('client.compare', compact('products'));
    }

    public function add(Request $request, CompareService $compareService)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = (int) $request->input('product_id');

        if ($compareService->isInCompare($productId)) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in comparison.',
                'count' => $compareService->getCount(),
            ]);
        }

        if (!$compareService->canAdd()) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum ' . $compareService->getMaxItems() . ' products allowed.',
                'count' => $compareService->getCount(),
            ]);
        }

        $compareService->add($productId);

        return response()->json([
            'success' => true,
            'message' => 'Product added to comparison.',
            'count' => $compareService->getCount(),
        ]);
    }

    public function remove($product, CompareService $compareService)
    {
        $compareService->remove((int) $product);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Removed from comparison.',
                'count' => $compareService->getCount(),
            ]);
        }

        return redirect()->route('compare.index')->with('success', 'Product removed from comparison.');
    }

    public function addToWishlist(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = auth()->id();
        $productId = (int) $validated['product_id'];

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
