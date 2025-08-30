<div>
    <div class="tf-page-title">
        <div class="container-full">
            <div class="heading text-center">My Account</div>
        </div>
    </div>

    <section class="flat-spacing-11">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="wrap-sidebar-account">
                        <ul class="my-account-nav">
                            <li>
                                <a href="#" wire:click.prevent="showSection('dashboard')"
                                   class="my-account-nav-item {{ $activeSection === 'dashboard' ? 'active' : '' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#" wire:click.prevent="showSection('orders')"
                                   class="my-account-nav-item {{ $activeSection === 'orders' ? 'active' : '' }}">
                                    Orders
                                </a>
                            </li>
                            <li>
                                <a href="#" wire:click.prevent="showSection('address')"
                                   class="my-account-nav-item {{ $activeSection === 'address' ? 'active' : '' }}">
                                    Address
                                </a>
                            </li>
                            <li>
                                <a href="#" wire:click.prevent="showSection('details')"
                                   class="my-account-nav-item {{ $activeSection === 'details' ? 'active' : '' }}">
                                    Account Details
                                </a>
                            </li>
                            <li>
                                <a href="#" wire:click.prevent="showSection('wishlist')"
                                   class="my-account-nav-item {{ $activeSection === 'wishlist' ? 'active' : '' }}">
                                    Wishlist
                                </a>
                            </li>
                            <li>
                                <a href="#" wire:click.prevent="showSection('cart')"
                                   class="my-account-nav-item {{ $activeSection === 'cart' ? 'active' : '' }}">
                                    Cart
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="my-account-nav-item"
                                            style="background:none;border:none;padding:0;">Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="my-account-content">
                        {{-- Conditionally render the active component --}}
                        @if ($activeSection === 'dashboard')
                            <livewire:client.profile.dashboard />
                        @elseif ($activeSection === 'orders')
                            <livewire:client.profile.order-history />
                        @elseif ($activeSection === 'address')
                            <livewire:client.profile.addresses />
                        @elseif ($activeSection === 'details')
                            <livewire:client.profile.account-details />
                        @elseif ($activeSection === 'wishlist')
                            <livewire:user.wish-list />
                        @elseif ($activeSection === 'cart')
                            @php
                                $cart = app(\App\Http\Controllers\Client\CartController::class)->index()->getData()['cart'] ?? [];
                            @endphp
                            {{-- Reuse the full cart page via an iframe-like include for minimal duplication --}}
                            <livewire:client.cart.cart-drawer />
                            @include('livewire.client.profile.partials.cart-section', ['cart' => $cart, 'subtotal' => collect($cart)->sum('total')])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
