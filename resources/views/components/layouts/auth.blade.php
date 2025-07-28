<?php

use App\Traits\SharedLayoutData;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;
use Illuminate\Contracts\View\View;

new class extends Component {
    use SharedLayoutData;

    public array $languages = [];
    public string $selectedLanguage = 'English';
    public int $cartCount = 0;
    public int $wishlistCount = 0;

    public function mount(): void
    {
        $layoutData = $this->getLayoutData();
        $this->cartCount = $layoutData['cartCount'] ?? 0; // Fallback to 0
        $this->wishlistCount = $layoutData['wishlistCount'] ?? 0; // Fallback to 0
        $this->languages = $layoutData['languages'] ?? [];
        $this->selectedLanguage = $layoutData['selectedLanguage'] ?? 'English';
    }

    public function getCartCountProperty(): int
    {
        return $this->getLayoutData()['cartCount'] ?? 0;
    }

    public function getWishlistCountProperty(): int
    {
        return $this->getLayoutData()['wishlistCount'] ?? 0;
    }

    public function render(): View
    {
        return view('layouts.app', [
            'cartCount' => $this->cartCount,
            'wishlistCount' => $this->wishlistCount,
            'languages' => $this->languages,
            'selectedLanguage' => $this->selectedLanguage,
        ]);
    }
};
?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'ShopWithCarl' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="ec__body">
<div class="min-vh-100">
    <!-- Top Bar: Currency and Language Selectors -->
    <!-- Announcement Bar -->
    <div class="announcement-bar bg_violet-1">
        <div class="wrap-announcement-bar">
            <div class="box-sw-announcement-bar speed-1">
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
                        </div>
                    </div>
                    <div class="col-md-4 col-3 tf-lg-hidden">
                        <a href="#mobileMenu" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16"
                                 fill="none">
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
                                    <i class="icon icon-search"></i>
                                </a>
                            </li>
                            <li class="nav-account">
                                <a href="{{ route('login') }}" class="nav-icon-item" wire:navigate>
                                    <i class="icon icon-account"></i>
                                </a>
                            </li>
                            {{--                        <li class="nav-wishlist">--}}
                            {{--                            <a href="{{ route('user.wishlist') }}" class="nav-icon-item" wire:navigate>--}}
                            {{--                                <i class="icon icon-heart"></i>--}}
                            {{--                                <span class="count-box">{{ $this->wishlistCount }}</span>--}}
                            {{--                            </a>--}}
                            {{--                        </li>--}}
                            {{--                        <li class="nav-cart">--}}
                            {{--                            <a href="{{ route('cart.index') }}" class="nav-icon-item" wire:navigate>--}}
                            {{--                                <i class="icon icon-bag"></i>--}}
                            {{--                                <span class="count-box">{{ $this->cartCount }}</span>--}}
                            {{--                            </a>--}}
                            {{--                        </li>--}}
                            <li class="nav-wishlist">
                                <a href="{{ route('user.wishlist') }}" class="nav-icon-item" wire:navigate>
                                    <i class="icon icon-heart"></i>
                                    {{--                                    <span class="count-box">{{ $wishlistCount }}</span>--}}
                                </a>
                            </li>
                            <li class="nav-cart">
                                <a href="{{ route('cart.index') }}" class="nav-icon-item" wire:navigate>
                                    <i class="icon icon-bag"></i>
                                    {{--                                    <span class="count-box">{{ $cartCount }}</span>--}}
                                </a>
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
                                        {{--                                        <li><a href="{{ route('about-us') }}" class="menu-link-text link text_black-2"--}}
                                        <li><a href="#" class="menu-link-text link text_black-2"
                                               wire:navigate>About us</a></li>
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">Brands</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                    <li><a href="{{ route('brands') }}"--}}
                                                    <li><a href="#"
                                                           class="menu-link-text link text_black-2 position-relative"
                                                           wire:navigate>Brands
                                                            <div class="demo-label"><span class="demo-new">New</span>
                                                            </div>
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">Contact</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                    <li><a href="{{ route('contact-1') }}"--}}
                                                    <li><a href="#"
                                                           class="menu-link-text link text_black-2" wire:navigate>Contact
                                                            Us</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">FAQ</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                    <li><a href="{{ route('faq-1') }}"--}}
                                                    <li><a href="#"
                                                           class="menu-link-text link text_black-2" wire:navigate>FAQ
                                                            01</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">Store</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                    <li><a href="{{ route('our-store') }}"--}}
                                                    <li><a href="#"
                                                           class="menu-link-text link text_black-2" wire:navigate>Our
                                                            store</a></li>
                                                    {{--                                                    <li><a href="{{ route('store-locator') }}"--}}
                                                    <li><a href="#"
                                                           class="menu-link-text link text_black-2" wire:navigate>Store
                                                            locator</a></li>
                                                </ul>
                                            </div>
                                        </li>
                                        {{--                                        <li><a href="{{ route('timeline') }}"--}}
                                        {{--                                               class="menu-link-text link text_black-2 position-relative" wire:navigate>Timeline--}}
                                        {{--                                                <div class="demo-label"><span class="demo-new">New</span></div>--}}
                                        {{--                                            </a></li>--}}
                                        {{--                                        <li><a href="{{ route('view-cart') }}"--}}
                                        {{--                                               class="menu-link-text link text_black-2 position-relative" wire:navigate>View--}}
                                        {{--                                                cart</a></li>--}}
                                        {{--                                        <li><a href="{{ route('checkout') }}"--}}
                                        {{--                                               class="menu-link-text link text_black-2 position-relative" wire:navigate>Check--}}
                                        {{--                                                out</a></li>--}}
                                        {{--                                        <li class="menu-item-2">--}}
                                        {{--                                            <a href="#" class="menu-link-text link text_black-2">Payment</a>--}}
                                        {{--                                            <div class="sub-menu submenu-default">--}}
                                        {{--                                                <ul class="menu-list">--}}
                                        {{--                                                    <li><a href="{{ route('payment-confirmation') }}"--}}
                                        {{--                                                           class="menu-link-text link text_black-2" wire:navigate>Payment--}}
                                        {{--                                                            Confirmation</a></li>--}}
                                        {{--                                                    <li><a href="{{ route('payment-failure') }}"--}}
                                        {{--                                                           class="menu-link-text link text_black-2" wire:navigate>Payment--}}
                                        {{--                                                            Failure</a></li>--}}
                                        {{--                                                </ul>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </li>--}}
                                        {{--                                        <li class="menu-item-2">--}}
                                        {{--                                            <a href="#" class="menu-link-text link text_black-2">My account</a>--}}
                                        {{--                                            <div class="sub-menu submenu-default">--}}
                                        {{--                                                <ul class="menu-list">--}}
                                        {{--                                                    <li><a href="{{ route('my-account') }}"--}}
                                        {{--                                                           class="menu-link-text link text_black-2" wire:navigate>My--}}
                                        {{--                                                            account</a></li>--}}
                                        {{--                                                    <li><a href="{{ route('my-account-orders') }}"--}}
                                        {{--                                                           class="menu-link-text link text_black-2" wire:navigate>My--}}
                                        {{--                                                            order</a></li>--}}
                                        {{--                                                    <li><a href="{{ route('my-account-orders-details') }}"--}}
                                        {{--                                                           class="menu-link-text link text_black-2" wire:navigate>My--}}
                                        {{--                                                            order details</a></li>--}}
                                        {{--                                                    <li><a href="{{ route('my-account-address') }}"--}}
                                        {{--                                                           class="menu-link-text link text_black-2" wire:navigate>My--}}
                                        {{--                                                            address</a></li>--}}
                                        {{--                                                    <li><a href="{{ route('my-account-edit') }}"--}}
                                        {{--                                                           class="menu-link-text link text_black-2" wire:navigate>My--}}
                                        {{--                                                            account details</a></li>--}}
                                        {{--                                                    <li><a href="{{ route('my-account-wishlist') }}"--}}
                                        {{--                                                           class="menu-link-text link text_black-2" wire:navigate>My--}}
                                        {{--                                                            wishlist</a></li>--}}
                                        {{--                                                </ul>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </li>--}}
                                        {{--                                        <li><a href="{{ route('invoice') }}"--}}
                                        {{--                                               class="menu-link-text link text_black-2 position-relative" wire:navigate>Invoice</a>--}}
                                        {{--                                        </li>--}}
                                        {{--                                        <li><a href="{{ route('404') }}"--}}
                                        {{--                                               class="menu-link-text link text_black-2 position-relative" wire:navigate>404</a>--}}
                                        {{--                                        </li>--}}
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item position-relative">
                                {{--                                <a href="{{ route('blog') }}" class="item-link" wire:navigate>Blog</a>--}}
                                <a href="#" class="item-link" wire:navigate>Blog</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Offcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="box-nav-ul">
                <li class="menu-item"><a href="{{ route('home') }}" class="item-link" wire:navigate>Home</a></li>
                {{--                <li class="menu-item"><a href="{{ route('shop') }}" class="item-link" wire:navigate>Shop</a></li>--}}
                <li class="menu-item"><a href="{{ route('products.index') }}" class="item-link"
                                         wire:navigate>Products</a>
                </li>
                <li class="menu-item position-relative">
                    <a href="#" class="item-link">Pages</a>
                    <div class="sub-menu submenu-default">
                        {{--                        <ul class="menu-list">--}}
                        {{--                            <li><a href="{{ route('about-us') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                   wire:navigate>About us</a></li>--}}
                        {{--                            <li class="menu-item-2">--}}
                        {{--                                <a href="#" class="menu-link-text link text_black-2">Brands</a>--}}
                        {{--                                <div class="sub-menu submenu-default">--}}
                        {{--                                    <ul class="menu-list">--}}
                        {{--                                        <li><a href="{{ route('brands') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                               wire:navigate>Brands</a></li>--}}
                        {{--                                        <li><a href="{{ route('brands-v2') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                               wire:navigate>Brand V2</a></li>--}}
                        {{--                                    </ul>--}}
                        {{--                                </div>--}}
                        {{--                            </li>--}}
                        {{--                            <li class="menu-item-2">--}}
                        {{--                                <a href="#" class="menu-link-text link text_black-2">Contact</a>--}}
                        {{--                                <div class="sub-menu submenu-default">--}}
                        {{--                                    <ul class="menu-list">--}}
                        {{--                                        <li><a href="{{ route('contact-1') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                               wire:navigate>Contact 1</a></li>--}}
                        {{--                                        <li><a href="{{ route('contact-2') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                               wire:navigate>Contact 2</a></li>--}}
                        {{--                                    </ul>--}}
                        {{--                                </div>--}}
                        {{--                            </li>--}}
                        {{--                            <li class="menu-item-2">--}}
                        {{--                                <a href="#" class="menu-link-text link text_black-2">FAQ</a>--}}
                        {{--                                <div class="sub-menu submenu-default">--}}
                        {{--                                    <ul class="menu-list">--}}
                        {{--                                        <li><a href="{{ route('faq-1') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                               wire:navigate>FAQ 01</a></li>--}}
                        {{--                                        <li><a href="{{ route('faq-2') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                               wire:navigate>FAQ 02</a></li>--}}
                        {{--                                    </ul>--}}
                        {{--                                </div>--}}
                        {{--                            </li>--}}
                        {{--                            <li class="menu-item-2">--}}
                        {{--                                <a href="#" class="menu-link-text link text_black-2">Store</a>--}}
                        {{--                                <div class="sub-menu submenu-default">--}}
                        {{--                                    <ul class="menu-list">--}}
                        {{--                                        <li><a href="{{ route('our-store') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                               wire:navigate>Our store</a></li>--}}
                        {{--                                        <li><a href="{{ route('store-locator') }}"--}}
                        {{--                                               class="menu-link-text link text_black-2" wire:navigate>Store locator</a>--}}
                        {{--                                        </li>--}}
                        {{--                                    </ul>--}}
                        {{--                                </div>--}}
                        {{--                            </li>--}}
                        {{--                            <li><a href="{{ route('timeline') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                   wire:navigate>Timeline</a></li>--}}
                        {{--                            <li><a href="{{ route('view-cart') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                   wire:navigate>View cart</a></li>--}}
                        {{--                            <li><a href="{{ route('checkout') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                   wire:navigate>Check out</a></li>--}}
                        {{--                            <li class="menu-item-2">--}}
                        {{--                                <a href="#" class="menu-link-text link text_black-2">Payment</a>--}}
                        {{--                                <div class="sub-menu submenu-default">--}}
                        {{--                                    <ul class="menu-list">--}}
                        {{--                                        <li><a href="{{ route('payment-confirmation') }}"--}}
                        {{--                                               class="menu-link-text link text_black-2" wire:navigate>Payment--}}
                        {{--                                                Confirmation</a></li>--}}
                        {{--                                        <li><a href="{{ route('payment-failure') }}"--}}
                        {{--                                               class="menu-link-text link text_black-2" wire:navigate>Payment--}}
                        {{--                                                Failure</a></li>--}}
                        {{--                                    </ul>--}}
                        {{--                                </div>--}}
                        {{--                            </li>--}}
                        {{--                            <li class="menu-item-2">--}}
                        {{--                                <a href="#" class="menu-link-text link text_black-2">My account</a>--}}
                        {{--                                <div class="sub-menu submenu-default">--}}
                        {{--                                    <ul class="menu-list">--}}
                        {{--                                        <li><a href="{{ route('my-account') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                               wire:navigate>My account</a></li>--}}
                        {{--                                        <li><a href="{{ route('my-account-orders') }}"--}}
                        {{--                                               class="menu-link-text link text_black-2" wire:navigate>My order</a></li>--}}
                        {{--                                        <li><a href="{{ route('my-account-orders-details') }}"--}}
                        {{--                                               class="menu-link-text link text_black-2" wire:navigate>My order--}}
                        {{--                                                details</a></li>--}}
                        {{--                                        <li><a href="{{ route('my-account-address') }}"--}}
                        {{--                                               class="menu-link-text link text_black-2" wire:navigate>My address</a>--}}
                        {{--                                        </li>--}}
                        {{--                                        <li><a href="{{ route('my-account-edit') }}"--}}
                        {{--                                               class="menu-link-text link text_black-2" wire:navigate>My account--}}
                        {{--                                                details</a></li>--}}
                        {{--                                        <li><a href="{{ route('my-account-wishlist') }}"--}}
                        {{--                                               class="menu-link-text link text_black-2" wire:navigate>My wishlist</a>--}}
                        {{--                                        </li>--}}
                        {{--                                    </ul>--}}
                        {{--                                </div>--}}
                        {{--                            </li>--}}
                        {{--                            <li><a href="{{ route('invoice') }}" class="menu-link-text link text_black-2" wire:navigate>Invoice</a>--}}
                        {{--                            </li>--}}
                        {{--                            <li><a href="{{ route('404') }}" class="menu-link-text link text_black-2"--}}
                        {{--                                   wire:navigate>404</a></li>--}}
                        {{--                        </ul>--}}
                    </div>
                </li>
                {{--                <li class="menu-item"><a href="{{ route('blog') }}" class="item-link" wire:navigate>Blog</a></li>--}}
                <li class="menu-item"><a href="#" class="item-link" wire:navigate>Blog</a></li>
            </ul>
        </div>
    </div>


    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show m-4 ec__alert" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show m-4 ec__alert" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-4">
        {{ $slot }}
    </main>


</div>

@livewireScripts
</body>
</html>
