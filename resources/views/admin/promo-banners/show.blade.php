@extends('admin.layouts.app')

@section('title', 'Promo Banner Details')
@section('page-title', 'Promo Banner Details')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 mb-0">Promo Banner Details</h2>
            <p class="text-muted mb-0">View details of the selected promo banner</p>
        </div>
        <div>
            <a href="{{ route('admin.promo-banners.edit', $promoBanner) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.promo-banners.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Promo Banners
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="stat-card p-4 mb-4">
                <h5 class="mb-3">Details</h5>

                {{-- Banner Information Table --}}
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                        <tr>
                            <th class="fw-medium">Heading:</th>
                            <td>{{ $promoBanner->heading }}</td>
                        </tr>
                        @if($promoBanner->subtitle)
                            <tr>
                                <th class="fw-medium">Subtitle:</th>
                                <td>{{ $promoBanner->subtitle }}</td>
                            </tr>
                        @endif
                        @if($promoBanner->features)
                            <tr>
                                <th class="fw-medium">Features:</th>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($promoBanner->features as $feature)
                                            <li>• {{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endif
                        @if($promoBanner->cta_text || $promoBanner->cta_link)
                            <tr>
                                <th class="fw-medium">CTA:</th>
                                <td>
                                    <strong>{{ $promoBanner->cta_text }}</strong><br>
                                    <a href="{{ $promoBanner->cta_link }}" target="_blank" class="text-primary">
                                        {{ $promoBanner->cta_link }} <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                        @if($promoBanner->price_badge)
                            <tr>
                                <th class="fw-medium">Price Badge:</th>
                                <td>{{ $promoBanner->price_badge }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th class="fw-medium">Status:</th>
                            <td>
                                <span class="badge {{ $promoBanner->active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $promoBanner->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-medium">Priority:</th>
                            <td>{{ $promoBanner->priority }}</td>
                        </tr>
                        @if($promoBanner->starts_at || $promoBanner->ends_at)
                            <tr>
                                <th class="fw-medium">Schedule:</th>
                                <td>
                                    @if($promoBanner->starts_at)
                                        From: {{ $promoBanner->starts_at->format('M d, Y g:i A') }}<br>
                                    @endif
                                    @if($promoBanner->ends_at)
                                        To: {{ $promoBanner->ends_at->format('M d, Y g:i A') }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                {{-- Images --}}
                <h5 class="mt-4">Images</h5>
                <div class="row g-3">
                    @if($promoBanner->image_desktop)
                        <div class="col-md-6">
                            <p><strong>Desktop:</strong></p>
                            <img src="{{ $promoBanner->image_desktop }}" alt="Desktop Image" class="img-fluid rounded">
                        </div>
                    @endif
                    @if($promoBanner->image_mobile)
                        <div class="col-md-6">
                            <p><strong>Mobile:</strong></p>
                            <img src="{{ $promoBanner->image_mobile }}" alt="Mobile Image" class="img-fluid rounded">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
