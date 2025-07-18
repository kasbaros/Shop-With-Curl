<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCartWithDetails();
        $subtotal = collect($cart)->sum('total');

        return view('cart.index', compact('cart', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $request->product_variant_id ? ProductVariant::findOrFail($request->product_variant_id) : null;

        // Check stock availability
        $stockQuantity = $variant ? $variant->stock_quantity : $product->stock_quantity;
        if ($stockQuantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.'
            ], 400);
        }

        // Add to cart
        $cart = session()->get('cart', []);
        $cartKey = $variant ? 'variant_' . $variant->id : 'product_' . $product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $request->quantity,
                'price' => $variant ? $variant->effective_price : $product->effective_price,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'cart_count' => collect($cart)->sum('quantity')
        ]);
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->valid()
            ->first();

        if (!$coupon) {
            return back()->withErrors(['coupon_code' => 'Invalid or expired coupon code.']);
        }

        $cart = $this->getCartWithDetails();
        $subtotal = collect($cart)->sum('total');

        $discount = $coupon->calculateDiscount($subtotal);

        if ($discount <= 0) {
            return back()->withErrors(['coupon_code' => 'This coupon is not applicable to your cart.']);
        }

        session()->put('applied_coupon', [
            'code' => $coupon->code,
            'discount' => $discount,
            'type' => $coupon->type,
        ]);

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        session()->forget('applied_coupon');
        return back()->with('success', 'Coupon removed!');
    }

    private function getCartWithDetails()
    {
        $cart = session()->get('cart', []);
        $cartWithDetails = [];

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            $variant = $item['product_variant_id'] ? ProductVariant::find($item['product_variant_id']) : null;

            if ($product) {
                $cartWithDetails[$key] = [
                    'key' => $key,
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                    'image' => $product->featured_image ?? '/images/placeholder.png',
                    'name' => $product->name . ($variant ? ' - ' . $variant->display_name : ''),
                ];
            }
        }

        return $cartWithDetails;
    }
}
