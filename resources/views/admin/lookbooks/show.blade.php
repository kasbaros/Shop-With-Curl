@extends('admin.layouts.app')

@section('title', 'Lookbook Details')
@section('page-title', 'Lookbook Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Lookbook Details</h2>
            <p class="text-muted mb-0">View details of the selected lookbook</p>
        </div>
        <div>
            <a href="{{ route('admin.lookbooks.edit', $lookbook) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i> Edit Lookbook
            </a>
            <a href="{{ route('admin.lookbooks.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Lookbooks
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Lookbook Information</h5>

                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                        <tr>
                            <th class="fw-medium">Title:</th>
                            <td>{{ $lookbook->title }}</td>
                        </tr>
                        @if($lookbook->label)
                            <tr>
                                <th class="fw-medium">Label:</th>
                                <td>{{ $lookbook->label }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th class="fw-medium">Status:</th>
                            <td>
                                <span class="badge {{ $lookbook->active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $lookbook->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-medium">Priority:</th>
                            <td>{{ $lookbook->priority }}</td>
                        </tr>
                        @if($lookbook->starts_at || $lookbook->ends_at)
                            <tr>
                                <th class="fw-medium">Schedule:</th>
                                <td>
                                    @if($lookbook->starts_at)
                                        From: {{ $lookbook->starts_at->format('M d, Y g:i A') }}<br>
                                    @endif
                                    @if($lookbook->ends_at)
                                        To: {{ $lookbook->ends_at->format('M d, Y g:i A') }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th class="fw-medium">Created At:</th>
                            <td>{{ $lookbook->created_at->format('M d, Y H:i A') }}</td>
                        </tr>
                        <tr>
                            <th class="fw-medium">Last Updated:</th>
                            <td>{{ $lookbook->updated_at->format('M d, Y H:i A') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                @if($lookbook->imageUrl)
                    <h5 class="mt-4">Main Image</h5>
                    <div class="text-center mt-3">
                        <img src="{{ $lookbook->imageUrl }}" alt="{{ $lookbook->title }}" class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                    </div>
                @endif

                <h5 class="mt-4">Included Products</h5>
                @if($lookbook->items->isEmpty())
                    <p class="text-muted">No products are associated with this lookbook.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lookbook->items as $item)
                                @if($item->product)
                                    <tr>
                                        <td>
                                            @if($item->product->thumbnail_url)
                                                <img src="{{ $item->product->thumbnail_url }}" alt="{{ $item->product->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('images/placeholder-product.jpg') }}" alt="No Image" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.products.show', $item->product) }}">
                                                {{ $item->product->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                                <span class="text-danger">UGX {{ number_format($item->product->sale_price, 0) }}</span>
                                                <span class="text-muted text-decoration-line-through">UGX {{ number_format($item->product->price, 0) }}</span>
                                            @else
                                                UGX {{ number_format($item->product->price, 0) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
