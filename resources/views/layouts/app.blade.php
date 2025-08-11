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
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
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
                                <span class="icon icon-search"></span>
                            </a>
                        </li>
                        <li class="nav-account">
                            @auth
                                <a href="{{ route('account.dashboard') }}" class="nav-icon-item" wire:navigate>
                                    <span class="icon icon-user"></span>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="nav-icon-item" wire:navigate>
                                    <span class="icon icon-user"></span>
                                </a>
                            @endauth
                        </li>
                        @auth
                            <li class="nav-wishlist">
                                <a href="{{ route('wishlist.index') }}" class="nav-icon-item" wire:navigate>
                                    <span class="icon icon-heart"></span>
                                    <span class="count-box">{{ auth()->user()->wishlist()->count() }}</span>
                                </a>
                            </li>
                        @endauth
                        <li class="nav-cart">
                            <livewire:shared.header/>
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
                            <a href="{{ route('categories.index') }}" class="item-link" wire:navigate>Categories</a>
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
                                    <li><a href="{{ route('pages.about') }}" class="menu-link-text link text_black-2"
                                           wire:navigate>About us</a></li>
                                    <li class="menu-item-2">
                                        <a href="#" class="menu-link-text link text_black-2">Contact</a>
                                        <div class="sub-menu submenu-default">
                                            <ul class="menu-list">
                                                <li><a href="{{ route('pages.contact') }}"
                                                       class="menu-link-text link text_black-2" wire:navigate>Contact
                                                        Us</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="menu-item-2">
                                        <a href="#" class="menu-link-text link text_black-2">FAQ</a>
                                        <div class="sub-menu submenu-default">
                                            <ul class="menu-list">
                                                <li><a href="{{ route('pages.faq') }}"
                                                       class="menu-link-text link text_black-2" wire:navigate>FAQ</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li><a href="{{ route('pages.privacy') }}" class="menu-link-text link text_black-2"
                                           wire:navigate>Privacy Policy</a></li>
                                    <li><a href="{{ route('pages.terms') }}" class="menu-link-text link text_black-2"
                                           wire:navigate>Terms & Conditions</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="menu-item position-relative">
                            <a href="{{ route('blog.index') }}" class="item-link" wire:navigate>Blog</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<flux:sidebar stashable sticky
              class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>
    <a href="{{ route('home') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
        <x-app-logo/>
    </a>

    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Navigation')">
            <flux:navlist.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                {{ __('Home') }}
            </flux:navlist.item>
            <flux:navlist.item icon="grid-3x3" :href="route('categories.index')"
                               :current="request()->routeIs('categories.*')" wire:navigate>
                {{ __('Categories') }}
            </flux:navlist.item>
            <flux:navlist.item icon="shopping-bag" :href="route('shop.index')" :current="request()->routeIs('shop.*')"
                               wire:navigate>
                {{ __('Shop') }}
            </flux:navlist.item>
            <flux:navlist.item icon="cube" :href="route('products.index')" :current="request()->routeIs('products.*')"
                               wire:navigate>
                {{ __('Products') }}
            </flux:navlist.item>
            <flux:navlist.item icon="newspaper" :href="route('blog.index')" :current="request()->routeIs('blog.*')"
                               wire:navigate>
                {{ __('Blog') }}
            </flux:navlist.item>
        </flux:navlist.group>

        @auth
            <flux:navlist.group :heading="__('Account')">
                <flux:navlist.item icon="user-circle" :href="route('account.dashboard')"
                                   :current="request()->routeIs('account.*')" wire:navigate>
                    {{ __('My Account') }}
                </flux:navlist.item>
                <flux:navlist.item icon="heart" :href="route('wishlist.index')"
                                   :current="request()->routeIs('wishlist.*')" wire:navigate>
                    {{ __('Wishlist') }}
                </flux:navlist.item>
                <flux:navlist.item icon="shopping-cart" :href="route('cart.index')"
                                   :current="request()->routeIs('cart.*')" wire:navigate>
                    {{ __('Cart') }}
                </flux:navlist.item>
                <flux:navlist.item icon="clipboard-document-list" :href="route('orders.index')"
                                   :current="request()->routeIs('orders.*')" wire:navigate>
                    {{ __('My Orders') }}
                </flux:navlist.item>
            </flux:navlist.group>
        @else
            <flux:navlist.group :heading="__('Account')">
                <flux:navlist.item icon="arrow-right-end-on-rectangle" :href="route('login')" wire:navigate>
                    {{ __('Login') }}
                </flux:navlist.item>
                <flux:navlist.item icon="user-plus" :href="route('register')" wire:navigate>
                    {{ __('Register') }}
                </flux:navlist.item>
            </flux:navlist.group>
        @endauth

        <flux:navlist.group :heading="__('Information')">
            <flux:navlist.item icon="information-circle" :href="route('pages.about')" wire:navigate>
                {{ __('About Us') }}
            </flux:navlist.item>
            <flux:navlist.item icon="phone" :href="route('pages.contact')" wire:navigate>
                {{ __('Contact') }}
            </flux:navlist.item>
            <flux:navlist.item icon="question-mark-circle" :href="route('pages.faq')" wire:navigate>
                {{ __('FAQ') }}
            </flux:navlist.item>
        </flux:navlist.group>
    </flux:navlist>

    <flux:spacer/>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="shield-check" :href="route('pages.privacy')" wire:navigate>
            {{ __('Privacy Policy') }}
        </flux:navlist.item>
        <flux:navlist.item icon="document-text" :href="route('pages.terms')" wire:navigate>
            {{ __('Terms & Conditions') }}
        </flux:navlist.item>
    </flux:navlist>
</flux:sidebar>

<!-- Cart Drawer -->
<livewire:client.cart.cart-drawer/>

{{ $slot }}

@fluxScripts
@livewireScripts
</body>
</html>
