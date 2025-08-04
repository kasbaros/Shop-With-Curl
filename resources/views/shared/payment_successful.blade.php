@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-check-circle-fill text-success mb-4" style="font-size: 5rem;"></i>
                        <h1 class="text-success mb-3">Payment Successful!</h1>
                        <p class="lead text-muted mb-4">
                            Thank you for your purchase. Your payment has been processed successfully.
                        </p>

                        <div class="row text-start">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Order Details</h6>
                                        <p class="card-text">
                                            <strong>Order Number:</strong> {{ $payment->order->order_number }}<br>
                                            <strong>Order Date:</strong> {{ $payment->order->created_at->format('M d, Y H:i') }}<br>
                                            <strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($payment->order->status) }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Payment Details</h6>
                                        <p class="card-text">
                                            <strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}<br>
                                            <strong>Amount:</strong> {{ format_currency($payment->amount, $payment->currency) }}<br>
                                            <strong>Transaction ID:</strong> {{ $payment->gateway_transaction_id ?? $payment->transaction_id }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="bi bi-info-circle me-2"></i>
                            A confirmation email has been sent to <strong>{{ $payment->order->billing_email }}</strong>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <a href="{{ route('orders.show', $payment->order) }}" class="btn btn-primary">
                                <i class="bi bi-eye me-2"></i>View Order Details
                            </a>
                            <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                            <button class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i>Print Receipt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
