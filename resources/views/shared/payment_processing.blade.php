@extends('layouts.app')

@section('title', 'Processing Payment')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <div id="processingState">
                            <div class="spinner-border text-primary mb-4" style="width: 4rem; height: 4rem;"></div>
                            <h3>Processing Your Payment</h3>
                            <p class="text-muted mb-4">Please wait while we process your payment request...</p>
                            <div id="paymentStatus" class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <span id="statusMessage">Initializing payment...</span>
                            </div>
                        </div>

                        <div id="successState" class="d-none">
                            <i class="bi bi-check-circle-fill text-success mb-4" style="font-size: 4rem;"></i>
                            <h3 class="text-success">Payment Successful!</h3>
                            <p class="text-muted mb-4">Your payment has been processed successfully.</p>
                            <div class="alert alert-success">
                                <strong>Transaction ID:</strong> <span id="transactionId"></span>
                            </div>
                            <a href="{{ route('orders.show', $payment->order) }}" class="btn btn-primary">
                                <i class="bi bi-eye me-2"></i>View Order
                            </a>
                        </div>

                        <div id="pendingState" class="d-none">
                            <i class="bi bi-clock text-warning mb-4" style="font-size: 4rem;"></i>
                            <h3 class="text-warning">Payment Pending</h3>
                            <p class="text-muted mb-4">Your payment is being processed. Please follow the instructions below.</p>
                            <div id="paymentInstructions" class="alert alert-warning text-start">
                                <!-- Instructions will be loaded here -->
                            </div>
                            <div class="d-grid gap-2">
                                <button id="checkStatusBtn" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Check Payment Status
                                </button>
                                <a href="{{ route('orders.show', $payment->order) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-eye me-2"></i>View Order Details
                                </a>
                            </div>
                        </div>

                        <div id="failedState" class="d-none">
                            <i class="bi bi-x-circle-fill text-danger mb-4" style="font-size: 4rem;"></i>
                            <h3 class="text-danger">Payment Failed</h3>
                            <p class="text-muted mb-4">We couldn't process your payment. Please try again.</p>
                            <div id="errorMessage" class="alert alert-danger">
                                <!-- Error message will be loaded here -->
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('payment.select', $payment->order) }}" class="btn btn-primary">
                                    <i class="bi bi-arrow-left me-2"></i>Try Again
                                </a>
                                <a href="{{ route('orders.show', $payment->order) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-eye me-2"></i>View Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Payment Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Order Number:</strong> {{ $payment->order->order_number }}<br>
                                <strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}<br>
                                <strong>Amount:</strong> {{ format_currency($payment->amount, $payment->currency) }}
                            </div>
                            <div class="col-md-6">
                                <strong>Payment ID:</strong> {{ $payment->transaction_id }}<br>
                                <strong>Date:</strong> {{ $payment->created_at->format('M d, Y H:i') }}<br>
                                <strong>Status:</strong> <span id="currentStatus" class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
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
                const paymentId = '{{ $payment->uuid }}';
                const paymentMethod = '{{ $payment->payment_method }}';
                let checkInterval;
                let attempts = 0;
                const maxAttempts = 60; // 5 minutes with 5-second intervals

                // Start checking payment status
                checkPaymentStatus();

                // Check status button
                document.getElementById('checkStatusBtn').addEventListener('click', function() {
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Checking...';
                    checkPaymentStatus();
                });

                function checkPaymentStatus() {
                    fetch(`/payment/verify/${paymentId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Payment Status:', data);

                            if (data.success) {
                                updatePaymentStatus(data.status, data);

                                // Continue checking if still pending/processing
                                if (['pending', 'processing'].includes(data.status) && attempts < maxAttempts) {
                                    attempts++;
                                    setTimeout(checkPaymentStatus, 5000); // Check every 5 seconds
                                } else if (attempts >= maxAttempts) {
                                    showTimeoutMessage();
                                }
                            } else {
                                showError(data.message || 'Payment verification failed');
                            }

                            // Re-enable check button
                            const checkBtn = document.getElementById('checkStatusBtn');
                            checkBtn.disabled = false;
                            checkBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Check Payment Status';
                        })
                        .catch(error => {
                            console.error('Payment Check Error:', error);
                            showError('Failed to check payment status');

                            // Re-enable check button
                            const checkBtn = document.getElementById('checkStatusBtn');
                            checkBtn.disabled = false;
                            checkBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Check Payment Status';
                        });
                }

                function updatePaymentStatus(status, data) {
                    // Update current status badge
                    const statusBadge = document.getElementById('currentStatus');
                    statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                    statusBadge.className = 'badge bg-' + getStatusColor(status);

                    // Show appropriate state
                    hideAllStates();

                    switch (status) {
                        case 'completed':
                            showSuccessState(data);
                            break;
                        case 'pending':
                        case 'processing':
                            showPendingState(data);
                            break;
                        case 'failed':
                        case 'cancelled':
                            showFailedState(data);
                            break;
                        default:
                            updateStatusMessage('Status: ' + status);
                    }
                }

                function showSuccessState(data) {
                    document.getElementById('successState').classList.remove('d-none');
                    if (data.transaction_id) {
                        document.getElementById('transactionId').textContent = data.transaction_id;
                    }
                }

                function showPendingState(data) {
                    document.getElementById('pendingState').classList.remove('d-none');

                    const instructionsDiv = document.getElementById('paymentInstructions');
                    let instructions = data.message || 'Payment is being processed...';

                    // Add specific instructions based on payment method
                    if (paymentMethod === 'manual_momo') {
                        instructions = getManualMomoInstructions();
                    } else if (paymentMethod === 'bank_transfer') {
                        instructions = getBankTransferInstructions();
                    } else if (paymentMethod === 'cod') {
                        instructions = getCODInstructions();
                    }

                    instructionsDiv.innerHTML = '<i class="bi bi-info-circle me-2"></i>' + instructions;
                }

                function showFailedState(data) {
                    document.getElementById('failedState').classList.remove('d-none');
                    const errorDiv = document.getElementById('errorMessage');
                    errorDiv.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>' +
                        (data.message || 'Payment failed. Please try again.');
                }

                function showError(message) {
                    hideAllStates();
                    document.getElementById('failedState').classList.remove('d-none');
                    document.getElementById('errorMessage').innerHTML =
                        '<i class="bi bi-exclamation-triangle me-2"></i>' + message;
                }

                function showTimeoutMessage() {
                    updateStatusMessage('Payment verification timeout. Please contact support if payment was made.');
                    document.getElementById('paymentStatus').className = 'alert alert-warning';
                }

                function hideAllStates() {
                    document.getElementById('processingState').classList.add('d-none');
                    document.getElementById('successState').classList.add('d-none');
                    document.getElementById('pendingState').classList.add('d-none');
                    document.getElementById('failedState').classList.add('d-none');
                }

                function updateStatusMessage(message) {
                    document.getElementById('statusMessage').textContent = message;
                }

                function getStatusColor(status) {
                    switch (status) {
                        case 'completed': return 'success';
                        case 'pending': return 'warning';
                        case 'processing': return 'info';
                        case 'failed': case 'cancelled': return 'danger';
                        default: return 'secondary';
                    }
                }

                function getManualMomoInstructions() {
                    return `
            <strong>Mobile Money Payment Instructions:</strong><br><br>
            1. Send <strong>{{ format_currency($payment->amount) }}</strong> to: <strong>{{ setting('momo_business_number') }}</strong><br>
            2. Account Name: <strong>{{ setting('momo_business_name') }}</strong><br>
            3. Use Reference: <strong>{{ $payment->transaction_id }}</strong><br>
            4. Take screenshot of confirmation<br>
            5. Wait for our confirmation (usually within 30 minutes)<br><br>
            <small>Contact us at {{ setting('store_phone') }} if you need help</small>
        `;
                }

                function getBankTransferInstructions() {
                    return `
            <strong>Bank Transfer Instructions:</strong><br><br>
            Bank: <strong>{{ setting('bank_name') }}</strong><br>
            Account Number: <strong>{{ setting('bank_account_number') }}</strong><br>
            Account Name: <strong>{{ setting('bank_account_name') }}</strong><br>
            Branch: <strong>{{ setting('bank_branch') }}</strong><br>
            Amount: <strong>{{ format_currency($payment->amount) }}</strong><br>
            Reference: <strong>{{ $payment->transaction_id }}</strong><br><br>
            <small>Please include the reference number in your transfer description.</small>
        `;
                }

                function getCODInstructions() {
                    return `
            <strong>Cash on Delivery Confirmed!</strong><br><br>
            Your order has been confirmed and will be delivered to your address.<br>
            Please have the exact amount ready: <strong>{{ format_currency($payment->amount + setting('cod_fee', 0)) }}</strong><br><br>
            <small>{{ setting('cod_instructions') }}</small>
        `;
                }
            });
        </script>
    @endpush
@endsection
