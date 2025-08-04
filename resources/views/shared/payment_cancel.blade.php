@extends('layouts.app')

@section('title', 'Payment Cancelled')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-x-circle-fill text-warning mb-4" style="font-size: 5rem;"></i>
                        <h1 class="text-warning mb-3">Payment Cancelled</h1>
                        <p class="lead text-muted mb-4">
                            Your payment was cancelled. Don't worry, no charges were made to your account.
                        </p>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Your order is still pending. You can try payment again or choose a different payment method.
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <a href="{{ route('payment.select', $payment->order) }}" class="btn btn-primary">
                                <i class="bi bi-credit-card me-2"></i>Try Payment Again
                            </a>
                            <a href="{{ route('orders.show', $payment->order) }}" class="btn btn-outline-primary">
                                <i class="bi bi-eye me-2"></i>View Order Details
                            </a>
                            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
