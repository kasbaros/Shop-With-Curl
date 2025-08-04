@extends('admin.layouts.app')

@section('title', 'Payment Management')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Payment Management</h1>
            <p class="text-muted">Manage and monitor all payment transactions</p>
        </div>
        <div>
            <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#analyticsModal">
                <i class="bi bi-graph-up me-2"></i>Analytics
            </button>
            <a href="{{ route('admin.payments.analytics') }}" class="btn btn-primary">
                <i class="bi bi-bar-chart-line me-2"></i>Detailed Analytics
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Payments</h5>
                            <h3 class="mb-0">{{ number_format($stats['total_payments']) }}</h3>
                        </div>
                        <i class="bi bi-credit-card fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Completed</h5>
                            <h3 class="mb-0">{{ number_format($stats['completed_payments']) }}</h3>
                            <small>{{ $stats['success_rate'] }}% success rate</small>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Pending</h5>
                            <h3 class="mb-0">{{ number_format($stats['pending_payments']) }}</h3>
                            <small>Needs attention</small>
                        </div>
                        <i class="bi bi-clock fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Month Revenue</h5>
                            <h3 class="mb-0">{{ format_currency($stats['month_revenue']) }}</h3>
                            <small>This month</small>
                        </div>
                        <i class="bi bi-currency-exchange fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="method" class="form-label">Method</label>
                    <select name="method" id="method" class="form-select">
                        <option value="">All Methods</option>
                        @foreach($methods as $method)
                            <option value="{{ $method }}" {{ request('method') === $method ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $method)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="gateway" class="form-label">Gateway</label>
                    <select name="gateway" id="gateway" class="form-select">
                        <option value="">All Gateways</option>
                        @foreach($gateways as $gateway)
                            <option value="{{ $gateway }}" {{ request('gateway') === $gateway ? 'selected' : '' }}>
                                {{ ucfirst($gateway) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control"
                           placeholder="Transaction ID..." value="{{ request('search') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payments ({{ $payments->total() }})</h5>
            <div>
                <button class="btn btn-sm btn-outline-primary" id="bulkActionBtn" disabled>
                    <i class="bi bi-gear me-2"></i>Bulk Action
                </button>
                <div class="btn-group ms-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
                            data-bs-toggle="dropdown">
                        <i class="bi bi-download me-2"></i>Export
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="exportPayments('csv')">Export as CSV</a></li>
                        <li><a class="dropdown-item" href="#" onclick="exportPayments('excel')">Export as Excel</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>Payment ID</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input payment-checkbox"
                                       value="{{ $payment->id }}">
                            </td>
                            <td>
                                <strong>{{ $payment->transaction_id }}</strong>
                                @if($payment->gateway_transaction_id)
                                    <br><small class="text-muted">{{ $payment->gateway_transaction_id }}</small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $payment->order) }}" class="text-decoration-none">
                                    #{{ $payment->order->order_number }}
                                </a>
                            </td>
                            <td>
                                @if($payment->order->user)
                                    <div>{{ $payment->order->user->name }}</div>
                                    <small class="text-muted">{{ $payment->order->user->email }}</small>
                                @else
                                    <span class="text-muted">Guest</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                </span>
                                <br><small class="text-muted">via {{ ucfirst($payment->gateway) }}</small>
                            </td>
                            <td>
                                <strong>{{ format_currency($payment->amount, $payment->currency) }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $this->getStatusColor($payment->status) }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                                @if($payment->status === 'pending' && $payment->gateway === 'manual')
                                    <br><small class="text-warning">
                                        <i class="bi bi-exclamation-triangle"></i> Needs approval
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div>{{ $payment->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.payments.show', $payment) }}"
                                       class="btn btn-outline-primary" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($payment->status === 'pending' && $payment->gateway === 'manual')
                                        <button class="btn btn-outline-success confirm-payment"
                                                data-payment-id="{{ $payment->id }}" title="Confirm Payment">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button class="btn btn-outline-danger reject-payment"
                                                data-payment-id="{{ $payment->id }}" title="Reject Payment">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    @endif

                                    @if(in_array($payment->status, ['pending', 'processing']) && $payment->gateway !== 'manual')
                                        <button class="btn btn-outline-info retry-payment"
                                                data-payment-id="{{ $payment->id }}" title="Retry/Check Status">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    @endif

                                    @if($payment->status === 'completed')
                                        <button class="btn btn-outline-warning refund-payment"
                                                data-payment-id="{{ $payment->id }}" title="Refund">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="bi bi-credit-card fs-1 text-muted"></i>
                                <p class="text-muted mt-2">No payments found</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payments->hasPages())
            <div class="card-footer">
                {{ $payments->withQueryString()->links() }}
            </div>
        @endif
    </div>

    @include('admin.payments.modals.confirm')
    @include('admin.payments.modals.reject')
    @include('admin.payments.modals.refund')
    @include('admin.payments.modals.bulk-action')

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const paymentCheckboxes = document.querySelectorAll('.payment-checkbox');
            const bulkActionBtn = document.getElementById('bulkActionBtn');

            selectAllCheckbox.addEventListener('change', function() {
                paymentCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActionButton();
            });

            paymentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActionButton);
            });

            function updateBulkActionButton() {
                const selectedCount = document.querySelectorAll('.payment-checkbox:checked').length;
                bulkActionBtn.disabled = selectedCount === 0;
                bulkActionBtn.textContent = selectedCount > 0
                    ? `Bulk Action (${selectedCount})`
                    : 'Bulk Action';
            }

            // Payment actions
            document.querySelectorAll('.confirm-payment').forEach(btn => {
                btn.addEventListener('click', function() {
                    const paymentId = this.dataset.paymentId;
                    showConfirmModal(paymentId);
                });
            });

            document.querySelectorAll('.reject-payment').forEach(btn => {
                btn.addEventListener('click', function() {
                    const paymentId = this.dataset.paymentId;
                    showRejectModal(paymentId);
                });
            });

            document.querySelectorAll('.retry-payment').forEach(btn => {
                btn.addEventListener('click', function() {
                    const paymentId = this.dataset.paymentId;
                    retryPayment(paymentId);
                });
            });

            document.querySelectorAll('.refund-payment').forEach(btn => {
                btn.addEventListener('click', function() {
                    const paymentId = this.dataset.paymentId;
                    showRefundModal(paymentId);
                });
            });

            // Bulk action
            bulkActionBtn.addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.payment-checkbox:checked'))
                    .map(cb => cb.value);
                showBulkActionModal(selectedIds);
            });
        });

        function showConfirmModal(paymentId) {
            document.getElementById('confirmPaymentId').value = paymentId;
            new bootstrap.Modal(document.getElementById('confirmPaymentModal')).show();
        }

        function showRejectModal(paymentId) {
            document.getElementById('rejectPaymentId').value = paymentId;
            new bootstrap.Modal(document.getElementById('rejectPaymentModal')).show();
        }

        function showRefundModal(paymentId) {
            document.getElementById('refundPaymentId').value = paymentId;
            new bootstrap.Modal(document.getElementById('refundPaymentModal')).show();
        }

        function showBulkActionModal(selectedIds) {
            document.getElementById('bulkPaymentIds').value = JSON.stringify(selectedIds);
            new bootstrap.Modal(document.getElementById('bulkActionModal')).show();
        }

        function retryPayment(paymentId) {
            if (!confirm('Retry payment verification?')) return;

            fetch(`/admin/payments/${paymentId}/retry`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', 'Payment retry completed');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'Payment retry failed');
                });
        }

        function exportPayments(format) {
            const selectedIds = Array.from(document.querySelectorAll('.payment-checkbox:checked'))
                .map(cb => cb.value);

            if (selectedIds.length === 0) {
                alert('Please select payments to export');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.payments.bulk-action") }}';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'export';
            form.appendChild(actionInput);

            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'payment_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

            document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.d-flex'));

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
@endpush

@php
    function getStatusColor($status) {
        return match($status) {
            'completed' => 'success',
            'pending' => 'warning',
            'processing' => 'info',
            'failed', 'cancelled' => 'danger',
            default => 'secondary'
        };
    }
@endphp
@endsection
