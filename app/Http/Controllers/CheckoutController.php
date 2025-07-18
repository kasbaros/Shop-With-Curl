<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = $this->getCartWithDetails();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $user = Auth::user();
        $addresses = $user->addresses()->get();
        $subtotal = collect($cart)->sum('total');
        $appliedCoupon = session()->get('applied_coupon');
        $discount = $appliedCoupon['discount'] ?? 0;
        $shipping = $this->calculateShipping($subtotal);
        $tax = $this->calculateTax($subtotal - $discount);
        $total = $subtotal - $discount + $shipping + $tax;

        return view('checkout.index', compact(
            'cart',
            'addresses',
            'subtotal',
            'discount',
            'shipping',
            'tax',
            'total',
            'appliedCoupon'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address_id' => 'required|exists:user_addresses,id',
            'billing_address_id' => 'required|exists:user_addresses,id',
            'payment_method' => 'required|string|in:credit_card,paypal,bank_transfer',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = $this->getCartWithDetails();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $user = Auth::user();
        $subtotal = collect($cart)->sum('total');
        $appliedCoupon = session()->get('applied_coupon');
        $discount = $appliedCoupon['discount'] ?? 0;
        $shipping = $this->calculateShipping($subtotal);
        $tax = $this->calculateTax($subtotal - $discount);
        $total = $subtotal - $discount + $shipping + $tax;

        try {
            DB::beginTransaction();

            // Get addresses
            $shippingAddress = UserAddress::where('id', $request->shipping_address_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $billingAddress = UserAddress::where('id', $request->billing_address_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'currency' => 'USD',
                'shipping_address' => $shippingAddress->full_address,
                'billing_address' => $billingAddress->full_address,
                'notes' => $request->notes,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            // Create order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_variant_id' => $item['variant']?->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                    'product_snapshot' => [
                        'name' => $item['product']->name,
                        'sku' => $item['product']->sku,
                        'variant' => $item['variant']?->display_name,
                    ],
                ]);

                // Update stock
                if ($item['variant']) {
                    $item['variant']->decrement('stock_quantity', $item['quantity']);
                } else {
                    $item['product']->decrement('stock_quantity', $item['quantity']);
                }
            }

            DB::commit();

            // Clear cart and applied coupon
            session()->forget(['cart', 'applied_coupon']);

            return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    public function success(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product', 'items.productVariant');

        return view('checkout.success', compact('order'));
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
                ];
            }
        }

        return $cartWithDetails;
    }

    private function calculateShipping($subtotal)
    {
        // Free shipping over $100
        if ($subtotal >= 100) {
            return 0;
        }

        // Standard shipping rate
        return 9.99;
    }

    private function calculateTax($taxableAmount)
    {
        // 8.25% tax rate
        return round($taxableAmount * 0.0825, 2);
    }
}
