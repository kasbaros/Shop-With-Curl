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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
