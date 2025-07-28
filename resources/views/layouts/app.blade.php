{{--<!DOCTYPE html>--}}
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">--}}
{{--<head>--}}
{{--    @include('partials.head')--}}
{{--    @livewireStyles--}}
{{--</head>--}}
{{--<body class="min-h-screen bg-white dark:bg-zinc-800">--}}
{{--<flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">--}}
{{--    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>--}}

{{--    <a href="{{ route('dashboard') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0"--}}
{{--       wire:navigate>--}}
{{--        <x-app-logo/>--}}
{{--    </a>--}}

{{--    <flux:navbar class="-mb-px max-lg:hidden">--}}
{{--        <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')"--}}
{{--                          wire:navigate>--}}
{{--            {{ __('Dashboard') }}--}}
{{--        </flux:navbar.item>--}}
{{--    </flux:navbar>--}}

{{--    <flux:spacer/>--}}

{{--    <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">--}}
{{--        <flux:tooltip :content="__('Search')" position="bottom">--}}
{{--            <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')"/>--}}
{{--        </flux:tooltip>--}}
{{--        <flux:tooltip :content="__('Repository')" position="bottom">--}}
{{--            <flux:navbar.item--}}
{{--                class="h-10 max-lg:hidden [&>div>svg]:size-5"--}}
{{--                icon="folder-git-2"--}}
{{--                href="https://github.com/laravel/livewire-starter-kit"--}}
{{--                target="_blank"--}}
{{--                :label="__('Repository')"--}}
{{--            />--}}
{{--        </flux:tooltip>--}}
{{--        <flux:tooltip :content="__('Documentation')" position="bottom">--}}
{{--            <flux:navbar.item--}}
{{--                class="h-10 max-lg:hidden [&>div>svg]:size-5"--}}
{{--                icon="book-open-text"--}}
{{--                href="https://laravel.com/docs/starter-kits#livewire"--}}
{{--                target="_blank"--}}
{{--                label="Documentation"--}}
{{--            />--}}
{{--        </flux:tooltip>--}}
{{--        <!-- Add Cart Toggle Button -->--}}
{{--        <livewire:header/>--}}
{{--    </flux:navbar>--}}

{{--    <!-- Desktop User Menu -->--}}
{{--    <flux:dropdown position="top" align="end">--}}
{{--        <flux:profile--}}
{{--            class="cursor-pointer"--}}
{{--            :initials="auth()->user()->initials()"--}}
{{--        />--}}
{{--        <flux:menu>--}}
{{--            <flux:menu.radio.group>--}}
{{--                <div class="p-0 text-sm font-normal">--}}
{{--                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">--}}
{{--                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">--}}
{{--                                    <span--}}
{{--                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"--}}
{{--                                    >--}}
{{--                                        {{ auth()->user()->initials() }}--}}
{{--                                    </span>--}}
{{--                                </span>--}}
{{--                        <div class="grid flex-1 text-start text-sm leading-tight">--}}
{{--                            <span class="truncate font-semibold">{{ auth()->user()->name }}</span>--}}
{{--                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </flux:menu.radio.group>--}}
{{--            <flux:menu.separator/>--}}
{{--            <flux:menu.radio.group>--}}
{{--                <flux:menu.item :href="route('settings.profile')" icon="cog"--}}
{{--                                wire:navigate>{{ __('Settings') }}</flux:menu.item>--}}
{{--            </flux:menu.radio.group>--}}
{{--            <flux:menu.separator/>--}}
{{--            <form method="POST" action="{{ route('logout') }}" class="w-full">--}}
{{--                @csrf--}}
{{--                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">--}}
{{--                    {{ __('Log Out') }}--}}
{{--                </flux:menu.item>--}}
{{--            </form>--}}
{{--        </flux:menu>--}}
{{--    </flux:dropdown>--}}
{{--</flux:header>--}}

{{--<!-- Mobile Menu -->--}}
{{--<flux:sidebar stashable sticky--}}
{{--              class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">--}}
{{--    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>--}}
{{--    <a href="{{ route('dashboard') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>--}}
{{--        <x-app-logo/>--}}
{{--    </a>--}}
{{--    <flux:navlist variant="outline">--}}
{{--        <flux:navlist.group :heading="__('Platform')">--}}
{{--            <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')"--}}
{{--                               wire:navigate>--}}
{{--                {{ __('Dashboard') }}--}}
{{--            </flux:navlist.item>--}}
{{--        </flux:navlist.group>--}}
{{--    </flux:navlist>--}}
{{--    <flux:spacer/>--}}
{{--    <flux:navlist variant="outline">--}}
{{--        <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">--}}
{{--            {{ __('Repository') }}--}}
{{--        </flux:navlist.item>--}}
{{--        <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">--}}
{{--            {{ __('Documentation') }}--}}
{{--        </flux:navlist.item>--}}
{{--    </flux:navlist>--}}
{{--</flux:sidebar>--}}

{{--<!-- Cart Drawer -->--}}
{{--<livewire:cart.cart-drawer/>--}}

{{--{{ $slot }}--}}

{{--@fluxScripts--}}
{{--@livewireScripts--}}
{{--</body>--}}
{{--</html>--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        @livewireStyles
        <style>
            /* Custom styles for announcement bar animation */
            .announcement-bar {
                background-color: #6b46c1; /* bg_violet-1 equivalent */
                color: white;
                padding: 0.5rem 0;
                position: relative;
                text-align: center;
            }
            .wrap-announcement-bar {
                overflow: hidden;
                white-space: nowrap;
            }
            .box-sw-announcement-bar {
                display: inline-flex;
                animation: slide 15s linear infinite;
            }
            .announcement-bar-item {
                padding: 0 2rem;
                font-size: 0.875rem;
            }
            .close-announcement-bar {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                width: 1.5rem;
                height: 1.5rem;
                background: url('/images/icon-close.png') no-repeat center;
                background-size: contain;
            }
            @keyframes slide {
                0% { transform: translateX(0); }
                100% { transform: translateX(-100%); }
            }
            .header-default {
                background-color: #f7fafc; /* bg_grey-11 equivalent */
                border-bottom: 1px solid #e2e8f0;
            }
            .container-full {
                max-width: 100%;
                padding-left: 15px;
                padding-right: 15px;
            }
            @media (min-width: 1024px) {
                .container-full {
                    padding-left: 40px;
                    padding-right: 40px;
                }
            }
            .nav-icon-item {
                position: relative;
                color: #2d3748;
                font-size: 1.5rem;
            }
            .nav-icon-item:hover {
                color: #3182ce;
            }
            .count-box {
                position: absolute;
                top: -0.5rem;
                right: -0.5rem;
                background-color: #3182ce;
                color: white;
                border-radius: 9999px;
                width: 1.25rem;
                height: 1.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.75rem;
            }
            .box-nav-ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .item-link {
                color: #2d3748;
                font-size: 1rem;
                font-weight: 500;
                text-decoration: none;
            }
            .item-link:hover {
                color: #3182ce;
            }
            .submenu-default {
                display: none;
                position: absolute;
                background-color: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 0.25rem;
                padding: 0.5rem 0;
                z-index: 10;
            }
            .menu-item:hover .submenu-default {
                display: block;
            }
            .menu-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .menu-link-text {
                color: #2d3748;
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
                display: block;
                text-decoration: none;
            }
            .menu-link-text:hover {
                background-color: #f7fafc;
                color: #3182ce;
            }
            .demo-label {
                display: inline-block;
                margin-left: 0.5rem;
            }
            .demo-new {
                background-color: #e53e3e;
                color: white;
                padding: 0.1rem 0.5rem;
                border-radius: 0.25rem;
                font-size: 0.75rem;
            }
        </style>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <!-- Announcement Bar -->
        <div class="announcement-bar">
            <div class="wrap-announcement-bar">
                <div class="box-sw-announcement-bar">
                    @foreach (['FREE SHIPPING AND RETURNS', 'NEW SEASON, NEW STYLES: FASHION SALE YOU CAN\'T MISS', 'LIMITED TIME OFFER: FASHION SALE YOU CAN\'T RESIST'] as $message)
                        <div class="announcement-bar-item">
                            <p>{{ $message }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <span class="icon-close close-announcement-bar"></span>
        </div>

        <!-- Header -->
        <header id="header" class="header-default header-style-2">
            <div class="main-header">
                <div class="container-full px_15 lg-px_40">
                    <div class="row wrapper-header align-items-center">
                        <div class="col-xl-5 tf-md-hidden">
                            <div class="tf-cur">
                                <!-- Placeholder for currency/language selectors -->
                            </div>
                        </div>
                        <div class="col-md-4 col-3 tf-lg-hidden">
                            <a href="#mobileMenu" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16" fill="none">
                                    <path
                                        d="M2.00056 2.28571H16.8577C17.1608 2.28571 17.4515 2.16531 17.6658 1.95098C17.8802 1.73665 18.0006 1.44596 18.0006 1.14286C18.0006 0.839753 17.8802 0.549063 17.6658 0.334735C17.4515 0.120408 17.1608 0 16.8577 0H2.00056C1.69745 0 1.40676 0.120408 1.19244 0.334735C0.978109 0.549063 0.857702 0.839753 0.857702 1.14286C0.857702 1.44596 0.978109 1.73665 1.19244 1.95098C1.40676 2.16531 1.69745 2.28571 2.00056 2.28571ZM0.857702 8C0.857702 7.6969 0.978109 7.40621 1.19244 7.19188C1.40676 6.97755 1.69745 6.85714 2.00056 6.85714H22.572C22.8751 6.85714 23.1658 6.97755 23.3801 7.19188C23.5944 7.40621 23.7148 7.6969 23.7148 8C23.7148 8.30311 23.5944 8.59379 23.3801 8.80812C23.1658 9.02245 22.8751 9.14286 22.572 9.14286H2.00056C1.69745 9.14286 1.40676 9.02245 1.19244 8.80812C0.978109 8.59379 0.857702 8.30311 0.857702 8ZM0.857702 14.8571C0.857702 14.554 0.978109 14.2633 1.19244 14.049C1.40676 13.8347 1.69745 13.7143 2.00056 13.7143H12.2863C12.5894 13.7143 12.8801 13.8347 13.0944 14.049C13.3087 14.2633 13.4291 14.554 13.4291 14.8571C13.4291 15.1602 13.3087 15.4509 13.0944 15.6653C12.8801 15.8796 12.5894 16 12.2863 16H2.00056C1.69745 16 1.40676 15.8796 1.19244 15.6653C0.978109 15.4509 0.857702 15.1602 0.857702 14.8571Z"
                                        fill="currentColor"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 text-center">
                            <a href="{{ route('home') }}" class="logo-header" wire:navigate>
                                <img src="{{ asset('images/logo/logo@2x.png') }}" alt="ShopWithCarl" class="logo">
                            </a>
                        </div>
                        <div class="col-xl-5 col-md-4 col-3">
                            <ul class="nav-icon d-flex justify-content-end align-items-center gap-20">
                                <li class="nav-search">
                                    <a href="#searchModal" data-bs-toggle="modal" class="nav-icon-item">
                                        <flux:icon.magnifying-glass class="w-6 h-6" />
                                    </a>
                                </li>
                                <li class="nav-account">
                                    <a href="{{ route('login') }}" class="nav-icon-item" wire:navigate>
                                        <flux:icon.user class="w-6 h-6" />
                                    </a>
                                </li>
                                <li class="nav-wishlist">
                                    <a href="{{ route('user.wishlist') }}" class="nav-icon-item" wire:navigate>
                                        <flux:icon.heart class="w-6 h-6" />
                                        <span class="count-box">{{ $wishlistCount ?? (auth()->check() ? auth()->user()->wishlist()->count() : 0) }}</span>
                                    </a>
                                </li>
                                <li class="nav-cart">
                                    <livewire:header />
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom tf-md-hidden bg_grey-11">
                <div class="container-full px_15 lg-px_40">
                    <div class="wrapper-header d-flex justify-content-center align-items-center">
                        <nav class="box-navigation text-center">
                            <ul class="box-nav-ul d-flex align-items-center justify-content-center gap-30">
                                <li class="menu-item">
                                    <a href="{{ route('home') }}" class="item-link" wire:navigate>Home</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('shop.index') }}" class="item-link" wire:navigate>Shop</a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{ route('products.index') }}" class="item-link" wire:navigate>Products</a>
                                </li>
                                <li class="menu-item position-relative">
                                    <a href="#" class="item-link">Pages</a>
                                    <div class="sub-menu submenu-default">
                                        <ul class="menu-list">
                                            <li><a href="#" class="menu-link-text link text_black-2" wire:navigate>About us</a></li>
                                            <li class="menu-item-2">
                                                <a href="#" class="menu-link-text link text_black-2">Brands</a>
                                                <div class="sub-menu submenu-default">
                                                    <ul class="menu-list">
                                                        <li><a href="#" class="menu-link-text link text_black-2 position-relative" wire:navigate>Brands
                                                                <div class="demo-label"><span class="demo-new">New</span></div>
                                                            </a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="menu-item-2">
                                                <a href="#" class="menu-link-text link text_black-2">Contact</a>
                                                <div class="sub-menu submenu-default">
                                                    <ul class="menu-list">
                                                        <li><a href="#" class="menu-link-text link text_black-2" wire:navigate>Contact Us</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="menu-item-2">
                                                <a href="#" class="menu-link-text link text_black-2">FAQ</a>
                                                <div class="sub-menu submenu-default">
                                                    <ul class="menu-list">
                                                        <li><a href="#" class="menu-link-text link text_black-2" wire:navigate>FAQ 01</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="menu-item-2">
                                                <a href="#" class="menu-link-text link text_black-2">Store</a>
                                                <div class="sub-menu submenu-default">
                                                    <ul class="menu-list">
                                                        <li><a href="#" class="menu-link-text link text_black-2" wire:navigate>Our store</a></li>
                                                        <li><a href="#" class="menu-link-text link text_black-2" wire:navigate>Store locator</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="menu-item position-relative">
                                    <a href="#" class="item-link" wire:navigate>Blog</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            <a href="{{ route('dashboard') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>
            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')">
                    <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            <flux:spacer />
            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:navlist.item>
                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>
        </flux:sidebar>

        <!-- Cart Drawer -->
        <livewire:cart.cart-drawer />

        {{ $slot }}

        @fluxScripts
        @livewireScripts
    </body>
</html>
