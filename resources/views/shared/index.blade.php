@extends('layouts.app')

@section('title', 'Choose Payment Method')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Progress Steps -->
                <div class="progress-steps mb-4">
                    <div class="row text-center">
                        <div class="col">
                            <div class="step completed">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Cart</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="step completed">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Checkout</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="step active">
                                <i class="bi bi-credit-card"></i>
                                <span>Payment</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="step">
                                <i class="bi bi-check-circle"></i>
                                <span>Complete</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Payment Methods -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-credit-card me-2"></i>Choose Payment Method
                                </h5>
                            </div>
                            <div class="card-body">
                                <form id="paymentForm" action="{{ route('payment.initiate', $order) }}" method="POST">
                                    @csrf

                                    @foreach($availableGateways as $gateway => $details)
                                        <div class="payment-method mb-3" data-method="{{ $gateway }}">
                                            <div class="form-check payment-option">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                       id="payment_{{ $gateway }}" value="{{ $gateway }}"
                                                    {{ $loop->first ? 'checked' : '' }}>
                                                <label class="form-check-label w-100" for="payment_{{ $gateway }}">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-{{ $details['icon'] }} me-3 fs-4 text-primary"></i>
                                                            <div>
                                                                <strong>{{ $details['name'] }}</strong>
                                                                <div class="small text-muted">{{ $details['description'] }}</div>
                                                            </div>
                                                        </div>
                                                        @if($details['type'] === 'mobile_money')
                                                            <span class="badge bg-success">Popular</span>
                                                        @elseif($details['type'] === 'cod')
                                                            <span class="badge bg-info">No Fee</span>
                                                        @endif
                                                    </div>
                                                </label>
                                            </div>

                                            <!-- Additional fields for specific payment methods -->
                                            @if(in_array($gateway, ['mtn_momo', 'airtel_money', 'manual_momo']))
                                                <div class="payment-fields mt-3" id="fields_{{ $gateway }}" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <label for="phone_{{ $gateway }}" class="form-label">Mobile Money Number</label>
                                                            <input type="tel" class="form-control" id="phone_{{ $gateway }}"
                                                                   name="phone" placeholder="+256 7XX XXX XXX"
                                                                   value="{{ old('phone', $order->billing_phone) }}">
                                                            <div class="form-text">Enter the number to receive payment request</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Secure Payment:</strong> Your payment information is encrypted and secure.
                                        We don't store your financial details.
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg" id="submitPayment">
                                            <i class="bi bi-lock-fill me-2"></i>
                                            <span id="submitText">Continue to Payment</span>
                                            <span id="submitAmount" class="ms-2">{{ format_currency($order->total_amount) }}</span>
                                        </button>
                                        <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left me-2"></i>Back to Checkout
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Order Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>{{ format_currency($order->subtotal) }}</span>
                                </div>

                                @if($order->tax_amount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Delivery ({{ setting('tax_rate', 18) }}%):</span>
                                        <span>{{ format_currency($order->tax_amount) }}</span>
                                    </div>
                                @endif

                                @if($order->shipping_amount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping:</span>
                                        <span>{{ format_currency($order->shipping_amount) }}</span>
                                    </div>
                                @endif

                                <div id="codFeeRow" class="d-flex justify-content-between mb-2" style="display: none;">
                                    <span>COD Fee:</span>
                                    <span id="codFeeAmount">{{ format_currency(setting('cod_fee', 0)) }}</span>
                                </div>

                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span id="totalAmount">{{ format_currency($order->total_amount) }}</span>
                                </div>

                                <div class="mt-3">
                                    <h6>Order #{{ $order->order_number }}</h6>
                                    <small class="text-muted">{{ $order->items->count() }} item(s)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Security -->
                        <div class="card mt-3">
                            <div class="card-body text-center">
                                <i class="bi bi-shield-check fs-1 text-success mb-2"></i>
                                <h6>Secure Payment</h6>
                                <p class="small text-muted">
                                    Your payment is protected by 256-bit SSL encryption.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('paymentForm');
                const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
                const submitButton = document.getElementById('submitPayment');
                const submitText = document.getElementById('submitText');
                const codFeeRow = document.getElementById('codFeeRow');
                const totalAmount = document.getElementById('totalAmount');
                const submitAmount = document.getElementById('submitAmount');

                const codFee = {{ setting('cod_fee', 0) }};
                const baseTotal = {{ $order->total_amount }};

                // Handle payment method changes
                paymentMethods.forEach(method => {
                    method.addEventListener('change', function() {
                        // Hide all payment fields
                        document.querySelectorAll('.payment-fields').forEach(field => {
                            field.style.display = 'none';
                        });

                        // Show fields for selected method
                        const selectedFields = document.getElementById('fields_' + this.value);
                        if (selectedFields) {
                            selectedFields.style.display = 'block';
                        }

                        // Update submit button text
                        updateSubmitButton(this.value);

                        // Update total for COD
                        updateTotal(this.value);
                    });
                });

                // Initialize with first method
                const firstMethod = document.querySelector('input[name="payment_method"]:checked');
                if (firstMethod) {
                    firstMethod.dispatchEvent(new Event('change'));
                }

                // Form submission
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
                    const formData = new FormData(this);

                    // Show loading
                    submitButton.disabled = true;
                    submitText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                handlePaymentResponse(selectedMethod, data);
                            } else {
                                throw new Error(data.message || 'Payment failed');
                            }
                        })
                        .catch(error => {
                            console.error('Payment Error:', error);
                            alert('Payment failed: ' + error.message);

                            // Reset button
                            submitButton.disabled = false;
                            updateSubmitButton(selectedMethod);
                        });
                });

                function updateSubmitButton(method) {
                    const buttonTexts = {
                        'mtn_momo': 'Pay with MTN Mobile Money',
                        'airtel_money': 'Pay with Airtel Money',
                        'manual_momo': 'Get Payment Instructions',
                        'bank_transfer': 'Get Bank Details',
                        'cod': 'Confirm Cash on Delivery',
                        'paypal': 'Pay with PayPal'
                    };

                    submitText.textContent = buttonTexts[method] || 'Continue to Payment';
                }

                function updateTotal(method) {
                    let newTotal = baseTotal;

                    if (method === 'cod') {
                        newTotal += codFee;
                        codFeeRow.style.display = 'flex';
                    } else {
                        codFeeRow.style.display = 'none';
                    }

                    totalAmount.textContent = formatCurrency(newTotal);
                    submitAmount.textContent = formatCurrency(newTotal);
                }

                function handlePaymentResponse(method, data) {
                    if (method === 'paypal' && data.data.approval_url) {
                        // Redirect to PayPal
                        window.location.href = data.data.approval_url;
                    } else {
                        // Redirect to payment processing page
                        window.location.href = `/payment/process/${data.payment_id}`;
                    }
                }

                function formatCurrency(amount) {
                    return new Intl.NumberFormat('{{ app()->getLocale() }}', {
                        style: 'currency',
                        currency: '{{ setting('default_currency', 'UGX') }}'
                    }).format(amount);
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .progress-steps .step {
                position: relative;
                padding: 1rem 0;
            }

            .progress-steps .step i {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
                color: #dee2e6;
            }

            .progress-steps .step.completed i {
                color: #28a745;
            }

            .progress-steps .step.active i {
                color: #007bff;
            }

            .payment-option {
                border: 2px solid #e9ecef;
                border-radius: 0.5rem;
                padding: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .payment-option:hover {
                border-color: #007bff;
                background-color: #f8f9fa;
            }

            .payment-option input[type="radio"]:checked + label .payment-option,
            .payment-option:has(input[type="radio"]:checked) {
                border-color: #007bff;
                background-color: #e7f3ff;
            }

            .payment-fields {
                background-color: #f8f9fa;
                padding: 1rem;
                border-radius: 0.375rem;
                border-left: 4px solid #007bff;
            }
        </style>
    @endpush
@endsection
