<div class="nav-cart">
    @php($privileged = (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isDeveloper())))
    @if($privileged)
        <a href="{{ route('admin.dashboard') }}" class="nav-icon-item d-flex align-items-center gap-1" title="Cart is unavailable for admin/developer users">
            <i class="icon icon-bag"></i>
            <span class="count-box">0</span>
            <span class="badge bg-secondary-subtle text-secondary fw-6" style="font-size:10px;">No Cart</span>
        </a>
    @else
        <a href="javascript:void(0);" class="nav-icon-item d-flex align-items-center gap-1" wire:click="toggleCart">
            <i class="icon icon-bag"></i>
            <span class="count-box">{{ $cartCount }}</span>
            <span class="badge bg-primary-subtle text-primary fw-6" style="font-size:10px;">UGX</span>
        </a>
    @endif
</div>
