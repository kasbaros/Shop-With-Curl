<?php
//
//namespace App\Http\Controllers\Client;
//
//use App\Http\Controllers\Controller;
//use App\Models\Coupon;
//use App\Models\Product;
//use App\Models\ProductVariant;
//use Illuminate\Http\Request;
//
//class CartController extends Controller
//{
//    public function index()
//    {
//        $cart = $this->getCartWithDetails();
//        $subtotal = collect($cart)->sum('total');
//
//        return view('cart.index', compact('cart', 'subtotal'));
//    }
//
//    public function add(Request $request)
//    {
//        $request->validate([
//            'product_id' => 'required|exists:products,id',
//            'product_variant_id' => 'nullable|exists:product_variants,id',
//            'quantity' => 'required|integer|min:1',
//        ]);
//
//        $product = Product::findOrFail($request->product_id);
//        $variant = $request->product_variant_id ? ProductVariant::findOrFail($request->product_variant_id) : null;
//
//        // Check stock availability
//        $stockQuantity = $variant ? $variant->stock_quantity : $product->stock_quantity;
//        if ($stockQuantity < $request->quantity) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Not enough stock available.'
//            ], 400);
//        }
//
//        // Add to cart
//        $cart = session()->get('cart', []);
//        $cartKey = $variant ? 'variant_' . $variant->id : 'product_' . $product->id;
//
//        if (isset($cart[$cartKey])) {
//            $cart[$cartKey]['quantity'] += $request->quantity;
//        } else {
//            $cart[$cartKey] = [
//                'product_id' => $product->id,
//                'product_variant_id' => $variant?->id,
//                'quantity' => $request->quantity,
//                'price' => $variant ? $variant->effective_price : $product->effective_price,
//            ];
//        }
//
//        session()->put('cart', $cart);
//
//        return response()->json([
//            'success' => true,
//            'message' => 'Product added to cart!',
//            'cart_count' => collect($cart)->sum('quantity')
//        ]);
//    }
//
//    public function update(Request $request, $key)
//    {
//        $request->validate([
//            'quantity' => 'required|integer|min:1',
//        ]);
//
//        $cart = session()->get('cart', []);
//
//        if (isset($cart[$key])) {
//            $cart[$key]['quantity'] = $request->quantity;
//            session()->put('cart', $cart);
//        }
//
//        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
//    }
//
//    public function remove($key)
//    {
//        $cart = session()->get('cart', []);
//        unset($cart[$key]);
//        session()->put('cart', $cart);
//
//        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
//    }
//
//    public function clear()
//    {
//        session()->forget('cart');
//        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
//    }
//
//    public function applyCoupon(Request $request)
//    {
//        $request->validate([
//            'coupon_code' => 'required|string',
//        ]);
//
//        $coupon = Coupon::where('code', $request->coupon_code)
//            ->valid()
//            ->first();
//
//        if (!$coupon) {
//            return back()->withErrors(['coupon_code' => 'Invalid or expired coupon code.']);
//        }
//
//        $cart = $this->getCartWithDetails();
//        $subtotal = collect($cart)->sum('total');
//
//        $discount = $coupon->calculateDiscount($subtotal);
//
//        if ($discount <= 0) {
//            return back()->withErrors(['coupon_code' => 'This coupon is not applicable to your cart.']);
//        }
//
//        session()->put('applied_coupon', [
//            'code' => $coupon->code,
//            'discount' => $discount,
//            'type' => $coupon->type,
//        ]);
//
//        return back()->with('success', 'Coupon applied successfully!');
//    }
//
//    public function removeCoupon()
//    {
//        session()->forget('applied_coupon');
//        return back()->with('success', 'Coupon removed!');
//    }
//
//    private function getCartWithDetails()
//    {
//        $cart = session()->get('cart', []);
//        $cartWithDetails = [];
//
//        foreach ($cart as $key => $item) {
//            $product = Product::find($item['product_id']);
//            $variant = isset($item['product_variant_id']) && $item['product_variant_id'] ? ProductVariant::find($item['product_variant_id']) : null;
//
//            if ($product) {
//                $cartWithDetails[$key] = [
//                    'key' => $key,
//                    'product' => $product,
//                    'variant' => $variant,
//                    'quantity' => $item['quantity'],
//                    'price' => $item['price'],
//                    'total' => $item['price'] * $item['quantity'],
//                    'image' => $product->primary_image_url,
//                    'name' => $product->name . ($variant ? ' - ' . $variant->display_name : ''),
//                ];
//            }
//        }
//
//        return $cartWithDetails;
//    }
//}


namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

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
        if ($product->manage_stock && $stockQuantity < $request->quantity) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available.'
                ], 400);
            }
            return back()->with('error', 'Not enough stock available.');
        }

        // Build variants array for CartService
        $variants = [];
        if ($variant) {
            $variants['variant_id'] = $variant->id;
            if ($variant->size) $variants['size'] = $variant->size;
            if ($variant->color) $variants['color'] = $variant->color;
        }

        $success = $this->cartService->add($product->id, $request->quantity, $variants);

        if ($request->wantsJson() || $request->ajax()) {
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart!',
                    'cart_count' => $this->cartService->getCount()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not add product to cart.'
                ], 400);
            }
        }

        if ($success) {
            return back()->with('success', 'Product added to cart!');
        }
        return back()->with('error', 'Could not add product to cart.');
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $success = $this->cartService->update($key, $request->quantity);

        if ($request->wantsJson() || $request->ajax()) {
            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated!',
                    'cart_count' => $this->cartService->getCount(),
                    'cart_total' => $this->cartService->formatPrice($this->cartService->getTotal())
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Could not update cart.'], 400);
        }

        return redirect()->route('cart.index')->with($success ? 'success' : 'error',
            $success ? 'Cart updated successfully!' : 'Could not update cart.');
    }

    public function remove(Request $request, $key)
    {
        $success = $this->cartService->remove($key);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Item removed from cart!' : 'Item not found.',
                'cart_count' => $this->cartService->getCount(),
                'cart_total' => $this->cartService->formatPrice($this->cartService->getTotal())
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function clear(Request $request)
    {
        $this->cartService->clear();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared!',
                'cart_count' => 0
            ]);
        }

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
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.'], 400);
            }
            return back()->withErrors(['coupon_code' => 'Invalid or expired coupon code.']);
        }

        $subtotal = $this->cartService->getTotal();
        $discount = $coupon->calculateDiscount($subtotal);

        if ($discount <= 0) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This coupon is not applicable.'], 400);
            }
            return back()->withErrors(['coupon_code' => 'This coupon is not applicable to your cart.']);
        }

        session()->put('applied_coupon', [
            'code' => $coupon->code,
            'discount' => $discount,
            'type' => $coupon->type,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'discount' => $discount,
                'discount_formatted' => money_format_ugx($discount)
            ]);
        }

        return back()->with('success', 'Coupon applied successfully!');
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('applied_coupon');

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Coupon removed!']);
        }

        return back()->with('success', 'Coupon removed!');
    }

    private function getCartWithDetails(): array
    {
        $cartItems = $this->cartService->getCart();
        $cartWithDetails = [];

        foreach ($cartItems as $key => $item) {
            $product = Product::find($item['product_id']);
            $variantId = $item['variant_id'] ?? ($item['variants']['variant_id'] ?? null);
            $variant = $variantId ? ProductVariant::find($variantId) : null;

            if ($product) {
                $cartWithDetails[$key] = [
                    'key' => $key,
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                    'image' => $item['product_image'] ?? $product->primary_image_url,
                    'name' => $item['product_name'] ?? ($product->name . ($variant ? ' - ' . $variant->display_name : '')),
                ];
            }
        }

        return $cartWithDetails;
    }
}
