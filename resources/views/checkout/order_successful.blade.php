<x-app-layout>
    <x-slot name="title">Order Placed - ShopWithCarl</x-slot>

    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">Thank you!</div>
        </div>
    </div>

    <section class="flat-spacing-11">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border shadow-sm">
                        <div class="card-body p-5 text-center">
                            <div class="mb-3">
                                <span class="icon icon-check-circle text-success" style="font-size:64px"></span>
                            </div>
                            <h3 class="fw-6 mb-2">Your order has been placed</h3>
                            <p class="text-muted mb-4">Order Number: <span class="fw-6">{{ $order->order_number }}</span></p>

                            <div class="mb-4">
                                <p class="mb-1">Status: <span class="fw-6 text-capitalize">{{ $order->status }}</span></p>
                                <p class="mb-1">Payment Method: <span class="fw-6 text-capitalize">{{ str_replace('_',' ', $order->payment_method) }}</span></p>
                                <p class="mb-1">Payment Status: <span class="fw-6 text-capitalize">{{ str_replace('_',' ', $order->payment_status) }}</span></p>
                                <p class="mb-1">Total Paid on Delivery: <span class="fw-6">{{ money_format_ugx((float)$order->total_amount) }}</span></p>
                            </div>

                            <div class="text-start mb-4">
                                <h6 class="fw-6 mb-3">Items</h6>
                                <ul class="list-group list-group-flush text-start">
                                    @foreach($order->items as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-6">{{ $item->product_snapshot['name'] ?? ($item->product->name ?? 'Item') }}</div>
                                                @if(!empty($item->product_snapshot['variant']))
                                                    <small class="text-muted">Variant: {{ $item->product_snapshot['variant'] }}</small>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <div class="small text-muted">Qty: {{ $item->quantity }}</div>
                                                <div class="fw-6">{{ money_format_ugx((float)$item->total) }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">View My Orders</a>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 border rounded bg-light">
                        <h6 class="fw-6 mb-2">What happens next?</h6>
                        <ul class="small text-muted mb-0">
                            <li>We have received your order and will begin processing it shortly.</li>
                            <li>Since you chose Cash on Delivery, please have the exact amount ready upon delivery.</li>
                            <li>You will receive updates by email/SMS as your order status changes.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
