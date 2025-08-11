<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    @foreach($notifications as $notification)
        <div class="alert alert-{{ $notification['type'] === 'success' ? 'success' : ($notification['type'] === 'error' ? 'danger' : 'info') }} alert-dismissible fade show mb-2"
             role="alert"
             wire:key="notification-{{ $notification['id'] }}">
            <div class="d-flex align-items-center">
                @if($notification['type'] === 'success')
                    <i class="bi bi-check-circle-fill me-2"></i>
                @elseif($notification['type'] === 'error')
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                @else
                    <i class="bi bi-info-circle-fill me-2"></i>
                @endif
                {{ $notification['message'] }}
            </div>
            <button type="button"
                    class="btn-close"
                    wire:click="dismiss('{{ $notification['id'] }}')"
                    aria-label="Close"></button>
        </div>
    @endforeach
</div>
