<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'ShopWithCarl' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
<div class="min-h-screen">
    <!-- Navigation -->
    <flux:navbar class="bg-white shadow-sm border-b border-gray-200">
        <flux:navbar.item>
            <flux:link href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                ShopWithCarl
            </flux:link>
        </flux:navbar.item>

        <!-- Search Bar -->
        <flux:navbar.item class="flex-1 max-w-lg mx-8">
            <livewire:products.product-search />
        </flux:navbar.item>

        <!-- Navigation Items -->
        <flux:navbar.item>
            <!-- Categories Dropdown -->
            <flux:dropdown>
                <flux:button variant="ghost" class="flex items-center space-x-1">
                    Categories
                    <flux:icon.chevron-down class="w-4 h-4" />
                </flux:button>

                <flux:menu>
                    @php
                        $categories = \App\Models\Category::active()->parent()->orderBy('sort_order')->take(8)->get();
                    @endphp
                    @foreach($categories as $category)
                        <flux:menu.item>
                            <flux:link href="{{ route('category.show', $category->slug) }}">
                                {{ $category->name }}
                            </flux:link>
                        </flux:menu.item>
                    @endforeach
                    <flux:menu.separator />
                    <flux:menu.item>
                        <flux:link href="{{ route('categories.index') }}" class="text-blue-600">
                            View All Categories
                        </flux:link>
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </flux:navbar.item>

        <flux:navbar.item>
            <flux:link href="{{ route('products.index') }}" variant="ghost">
                Products
            </flux:link>
        </flux:navbar.item>

        @auth
            <!-- User Menu -->
            <flux:navbar.item>
                <flux:dropdown>
                    <flux:button variant="ghost" class="flex items-center space-x-1">
                        <flux:icon.user class="w-5 h-5" />
                        {{ auth()->user()->name }}
                        <flux:icon.chevron-down class="w-4 h-4" />
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item>
                            <flux:link href="{{ route('user.profile') }}">Profile</flux:link>
                        </flux:menu.item>
                        <flux:menu.item>
                            <flux:link href="{{ route('user.orders') }}">Orders</flux:link>
                        </flux:menu.item>
                        <flux:menu.item>
                            <flux:link href="{{ route('user.wishlist') }}">Wishlist</flux:link>
                        </flux:menu.item>
                        <flux:menu.item>
                            <flux:link href="{{ route('user.addresses') }}">Addresses</flux:link>
                        </flux:menu.item>
                        <flux:menu.separator />
                        <flux:menu.item>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <flux:button type="submit" variant="ghost" class="w-full text-left">
                                    Logout
                                </flux:button>
                            </form>
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </flux:navbar.item>
        @else
            <!-- Login/Register -->
            <flux:navbar.item>
                <flux:link href="{{ route('login') }}" variant="ghost">
                    Login
                </flux:link>
            </flux:navbar.item>
            <flux:navbar.item>
                <flux:button href="{{ route('register') }}" variant="primary">
                    Register
                </flux:button>
            </flux:navbar.item>
        @endauth

        <!-- Cart -->
        <flux:navbar.item>
            <livewire:cart.mini-cart />
        </flux:navbar.item>
    </flux:navbar>

    <!-- Main Content -->
    <main class="py-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <flux:heading size="lg" class="text-white mb-4">ShopWithCarl</flux:heading>
                    <flux:text size="sm" class="text-gray-300">
                        Your trusted online shopping destination with quality products and excellent service.
                    </flux:text>
                </div>

                <!-- Quick Links -->
                <div>
                    <flux:heading size="base" class="text-white mb-4">Quick Links</flux:heading>
                    <div class="space-y-2">
                        <flux:link href="{{ route('home') }}" class="text-gray-300 hover:text-white block text-sm">Home</flux:link>
                        <flux:link href="{{ route('products.index') }}" class="text-gray-300 hover:text-white block text-sm">Products</flux:link>
                        <flux:link href="{{ route('categories.index') }}" class="text-gray-300 hover:text-white block text-sm">Categories</flux:link>
                        <flux:link href="#" class="text-gray-300 hover:text-white block text-sm">About Us</flux:link>
                        <flux:link href="#" class="text-gray-300 hover:text-white block text-sm">Contact</flux:link>
                    </div>
                </div>

                <!-- Customer Service -->
                <div>
                    <flux:heading size="base" class="text-white mb-4">Customer Service</flux:heading>
                    <div class="space-y-2">
                        <flux:link href="#" class="text-gray-300 hover:text-white block text-sm">Help Center</flux:link>
                        <flux:link href="#" class="text-gray-300 hover:text-white block text-sm">Shipping Info</flux:link>
                        <flux:link href="#" class="text-gray-300 hover:text-white block text-sm">Returns</flux:link>
                        <flux:link href="#" class="text-gray-300 hover:text-white block text-sm">Privacy Policy</flux:link>
                        <flux:link href="#" class="text-gray-300 hover:text-white block text-sm">Terms of Service</flux:link>
                    </div>
                </div>

                <!-- Newsletter -->
                <div>
                    <flux:heading size="base" class="text-white mb-4">Newsletter</flux:heading>
                    <flux:text size="sm" class="text-gray-300 mb-4">Subscribe for updates and offers</flux:text>
                    <div class="flex">
                        <flux:input type="email" placeholder="Enter your email" class="flex-1 rounded-r-none" />
                        <flux:button type="submit" variant="primary" class="rounded-l-none">
                            Subscribe
                        </flux:button>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <flux:text size="sm" class="text-gray-300">
                    &copy; {{ date('Y') }} ShopWithCarl. All rights reserved.
                </flux:text>
            </div>
        </div>
    </footer>
</div>

<!-- Cart Drawer -->
<livewire:cart.cart-drawer />

<!-- Notifications -->
<div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>

@livewireScripts

<script>
    // Notification system
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-notification', (event) => {
            const notification = document.createElement('div');
            notification.className = `px-4 py-2 rounded-lg shadow-lg text-white ${
                event.type === 'success' ? 'bg-green-500' :
                    event.type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            notification.textContent = event.message;

            document.getElementById('notifications').appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        });
    });
</script>
</body>
</html>
