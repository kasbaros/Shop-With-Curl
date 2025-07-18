<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'ShopWithCarl') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @fluxAppearance
</head>
<body style="font-family: 'Instrument Sans', sans-serif; -webkit-font-smoothing: antialiased;">
<div style="min-height: 100vh; background-color: #f5f5f5;">
    <!-- Announcement Bar -->
    @if(config('announcements.messages') && count(config('announcements.messages')) > 0)
        <div class="announcement-bar bg_violet-1">
            <div class="wrap-announcement-bar">
                <div class="box-sw-announcement-bar speed-1">
                    @foreach (config('announcements.messages') as $message)
                        <div class="announcement-bar-item">
                            <p>{{ $message }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <span class="icon-close close-announcement-bar" aria-label="Close announcement bar"></span>
        </div>
    @endif

    <!-- Header -->
    <header id="header" class="header-default header-style-2">
        <div class="main-header">
            <div class="container-full px-3 px-lg-5">
                <div class="row wrapper-header align-items-center">
                    <div class="col-xl-5 d-none d-xl-block">
                        {{--                            <div class="tf-cur">--}}
                        {{--                                <div class="tf-currencies">--}}
                        {{--                                    <select class="image-select center style-default type-currencies">--}}
                        {{--                                        @foreach ([--}}
                        {{--                                            ['value' => 'USD', 'label' => 'USD $ | United States', 'flag' => 'images/country/us.svg'],--}}
                        {{--                                            ['value' => 'UGX', 'label' => 'UGX Shs | Uganda', 'flag' => 'images/country/us.svg', 'selected' => true],--}}

                        {{--                                        ] as $currency)--}}
                        {{--                                            <option value="{{ $currency['value'] }}" data-thumbnail="{{ $currency['flag'] }}" @if ($currency['selected'] ?? false) selected @endif>--}}
                        {{--                                                {{ $currency['label'] }}--}}
                        {{--                                            </option>--}}
                        {{--                                        @endforeach--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                                <div class="tf-languages">--}}
                        {{--                                    <select class="image-select center style-default type-languages">--}}
                        {{--                                        @foreach (['English'] as $language)--}}
                        {{--                                            <option>{{ $language }}</option>--}}
                        {{--                                        @endforeach--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                    </div>
                    <div class="col-md-4 col-3 d-xl-none">
                        <a href="#mobileMenu" data-bs-toggle="offcanvas" aria-controls="offcanvasLeft"
                           aria-label="Toggle navigation">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16"
                                 fill="none">
                                <path
                                    d="M2.00056 2.28571H16.8577C17.1608 2.28571 17.4515 2.16531 17.6658 1.95098C17.8802 1.73665 18.0006 1.44596 18.0006 1.14286C18.0006 0.839753 17.8802 0.549063 17.6658 0.334735C17.4515 0.120408 17.1608 0 16.8577 0H2.00056C1.69745 0 1.40676 0.120408 1.19244 0.334735C0.978109 0.549063 0.857702 0.839753 0.857702 1.14286C0.857702 1.44596 0.978109 1.73665 1.19244 1.95098C1.40676 2.16531 1.69745 2.28571 2.00056 2.28571ZM0.857702 8C0.857702 7.6969 0.978109 7.40621 1.19244 7.19188C1.40676 6.97755 1.69745 6.85714 2.00056 6.85714H22.572C22.8751 6.85714 23.1658 6.97755 23.3801 7.19188C23.5944 7.40621 23.7148 7.6969 23.7148 8C23.7148 8.30311 23.5944 8.59379 23.3801 8.80812C23.1658 9.02245 22.8751 9.14286 22.572 9.14286H2.00056C1.69745 9.14286 1.40676 9.02245 1.19244 8.80812C0.978109 8.59379 0.857702 8.30311 0.857702 8ZM0.857702 14.8571C0.857702 14.554 0.978109 14.2633 1.19244 14.049C1.40676 13.8347 1.69745 13.7143 2.00056 13.7143H12.2863C12.5894 13.7143 12.8801 13.8347 13.0944 14.049C13.3087 14.2633 13.4291 14.554 13.4291 14.8571C13.4291 15.1602 13.3087 15.4509 13.0944 15.6653C12.8801 15.8796 12.5894 16 12.2863 16H2.00056C1.69745 16 1.40676 15.8796 1.19244 15.6653C0.978109 15.4509 0.857702 15.1602 0.857702 14.8571Z"
                                    fill="currentColor"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6 text-center">
                        <a href="{{ route('home') }}" class="logo-header">
                            <img src="{{ asset('images/logo/SWC 2.png') }}"
                                 alt="{{ config('app.name', 'ShopWithCarl') }} logo" class="logo">
                        </a>
                    </div>
                    <div class="col-xl-5 col-md-4 col-3">
                        <ul class="nav-icon d-flex justify-content-end align-items-center" style="gap: 20px;">
                            <li class="nav-search"><a href="#canvasSearch" data-bs-toggle="offcanvas"
                                                      aria-controls="offcanvasLeft" class="nav-icon-item"
                                                      aria-label="Search"><i class="icon icon-search"></i></a></li>
                            <li class="nav-account">
                                @auth
                                    <div class="dropdown">
                                        <button class="nav-icon-item dropdown-toggle" id="userDropdown"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="icon icon-account"></i>
                                            <span>{{ Auth::user()->name }}</span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                            <li><a class="dropdown-item"
                                                   href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a></li>
                                            <li><a class="dropdown-item"
                                                   href="{{ route('user.orders') }}">{{ __('Orders') }}</a></li>
                                            <li><a class="dropdown-item"
                                                   href="{{ route('user.addresses') }}">{{ __('Addresses') }}</a></li>
                                            <li><a class="dropdown-item"
                                                   href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                                       onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</a>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                @else
                                    <a href="{{ route('login') }}" class="nav-icon-item" aria-label="Login"><i
                                            class="icon icon-account"></i>Sign in</a>
                                @endauth
                            </li>
                            <li class="nav-wishlist"><a href="{{ route('user.wishlist') }}" class="nav-icon-item"
                                                        aria-label="Wishlist"><i class="icon icon-heart"></i><span
                                        class="count-box">{{ Auth::check() ? Auth::user()->wishlist()->count() : 0 }}</span>Wishlist</a>
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
                                <a href="{{ route('products.index') }}" class="item-link" wire:navigate>Shop</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('products.index') }}" class="item-link" wire:navigate>Products</a>
                            </li>
                            <li class="menu-item position-relative">
                                <a href="#" class="item-link">Pages</a>
                                <div class="sub-menu submenu-default">
                                    <ul class="menu-list">
                                        {{--                                    <li><a href="{{ route('about-us') }}" class="menu-link-text link text_black-2" wire:navigate>About us</a></li>--}}
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">Brands</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                <li><a href="{{ route('brands') }}" class="menu-link-text link text_black-2 position-relative" wire:navigate>Brands<div class="demo-label"><span class="demo-new">New</span></div></a></li>--}}
                                                    {{--                                                <li><a href="{{ route('brands-v2') }}" class="menu-link-text link text_black-2" wire:navigate>Brand V2</a></li>--}}
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">Contact</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                <li><a href="{{ route('contact-1') }}" class="menu-link-text link text_black-2" wire:navigate>Contact 1</a></li>--}}
                                                    {{--                                                <li><a href="{{ route('contact-2') }}" class="menu-link-text link text_black-2" wire:navigate>Contact 2</a></li>--}}
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">FAQ</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                <li><a href="{{ route('faq-1') }}" class="menu-link-text link text_black-2" wire:navigate>FAQ 01</a></li>--}}
                                                    {{--                                                <li><a href="{{ route('faq-2') }}" class="menu-link-text link text_black-2" wire:navigate>FAQ 02</a></li>--}}
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">Store</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                <li><a href="{{ route('our-store') }}" class="menu-link-text link text_black-2" wire:navigate>Our store</a></li>--}}
                                                    {{--                                                <li><a href="{{ route('store-locator') }}" class="menu-link-text link text_black-2" wire:navigate>Store locator</a></li>--}}
                                                </ul>
                                            </div>
                                        </li>
                                        {{--                                    <li><a href="{{ route('timeline') }}" class="menu-link-text link text_black-2 position-relative" wire:navigate>Timeline<div class="demo-label"><span class="demo-new">New</span></div></a></li>--}}
                                        {{--                                    <li><a href="{{ route('view-cart') }}" class="menu-link-text link text_black-2 position-relative" wire:navigate>View cart</a></li>--}}
                                        {{--                                    <li><a href="{{ route('checkout') }}" class="menu-link-text link text_black-2 position-relative" wire:navigate>Check out</a></li>--}}
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">Payment</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                <li><a href="{{ route('payment-confirmation') }}" class="menu-link-text link text_black-2" wire:navigate>Payment Confirmation</a></li>--}}
                                                    {{--                                                <li><a href="{{ route('payment-failure') }}" class="menu-link-text link text_black-2" wire:navigate>Payment Failure</a></li>--}}
                                                </ul>
                                            </div>
                                        </li>
                                        <li class="menu-item-2">
                                            <a href="#" class="menu-link-text link text_black-2">My account</a>
                                            <div class="sub-menu submenu-default">
                                                <ul class="menu-list">
                                                    {{--                                                <li><a href="{{ route('my-account') }}" class="menu-link-text link text_black-2" wire:navigate>My account</a></li>--}}
                                                    {{--                                                <li><a href="{{ route('my-account-orders') }}" class="menu-link-text link text_black-2" wire:navigate>My order</a></li>--}}
                                                    {{--                                                <li><a href="{{ route('my-account-orders-details') }}" class="menu-link-text link text_black-2" wire:navigate>My order details</a></li>--}}
                                                    {{--                                                <li><a href="{{ route('my-account-address') }}" class="menu-link-text link text_black-2" wire:navigate>My address</a></li>--}}
                                                    {{--                                                <li><a href="{{ route('my-account-edit') }}" class="menu-link-text link text_black-2" wire:navigate>My account details</a></li>--}}
                                                    {{--                                                <li><a href="{{ route('my-account-wishlist') }}" class="menu-link-text link text_black-2" wire:navigate>My wishlist</a></li>--}}
                                                </ul>
                                            </div>
                                        </li>
                                        {{--                                    <li><a href="{{ route('invoice') }}" class="menu-link-text link text_black-2 position-relative" wire:navigate>Invoice</a></li>--}}
                                        {{--                                    <li><a href="{{ route('404') }}" class="menu-link-text link text_black-2 position-relative" wire:navigate>404</a></li>--}}
                                    </ul>
                                </div>
                            </li>
                            <li class="menu-item position-relative">
                                {{--                            <a href="{{ route('blog') }}" class="item-link" wire:navigate>Blog</a>--}}
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main style="padding-bottom: 2rem;">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer id="footer" class="footer background-gray" style="padding-bottom: 70px;">
        <div class="footer-wrap">
            <div class="footer-body">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="footer-infor">
                                <div class="footer-logo">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ asset('images/logo/SWC 2.png') }}"
                                             alt="{{ config('app.name', 'ShopWithCarl') }} logo">
                                    </a>
                                </div>
                                <ul>
                                    <li><p>{{ config('contact.address') }}</p></li>
                                    <li><p>Email: <a
                                                href="mailto:{{ config('contact.email') }}">{{ config('contact.email') }}</a>
                                        </p></li>
                                    <li><p>Phone: <a
                                                href="tel:{{ config('contact.phone') }}">{{ config('contact.phone') }}</a>
                                        </p></li>
                                </ul>
                                <a href="{{ route('pages.contact') }}" class="tf-btn btn-line">Get direction<i
                                        class="icon icon-arrow1-top-left"></i></a>
                                <ul class="tf-social-icon d-flex" style="gap: 10px;">
                                    @if(config('contact.socials'))
                                        @foreach (config('contact.socials') as $platform => $url)
                                            <li>
                                                <a href="{{ $url }}"
                                                   class="box-icon w_34 round social-{{ $platform }} social-line"
                                                   aria-label="Visit our {{ ucfirst($platform) }} page">
                                                    <i class="icon fs-14 icon-{{ $platform }}"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-12 footer-col-block">
                            <div class="footer-heading footer-heading-mobile">
                                <h6 class="font-young-serif" data-bs-toggle="collapse" data-bs-target="#helpCollapse"
                                    aria-expanded="false" aria-controls="helpCollapse">Help</h6>
                            </div>
                            <ul class="footer-menu-list" id="helpCollapse">
                                <li><a href="{{ route('user.wishlist') }}" class="footer-menu_item">My Wishlist</a></li>
                            </ul>
                        </div>
                        <div class="col-xl-3 col-md-6 col-12 footer-col-block">
                            <div class="footer-heading footer-heading-mobile">
                                <h6 class="font-young-serif" data-bs-toggle="collapse"
                                    data-bs-target="#usefulLinksCollapse" aria-expanded="false"
                                    aria-controls="usefulLinksCollapse">Useful Links</h6>
                            </div>
                            <ul class="footer-menu-list" id="usefulLinksCollapse">
                                <li><a href="{{ route('pages.about-us') }}" class="footer-menu_item">Our Story</a></li>
                                <li><a href="{{ route('pages.our-store') }}" class="footer-menu_item">Visit Our
                                        Store</a></li>
                                <li><a href="{{ route('pages.contact') }}" class="footer-menu_item">Contact Us</a></li>
                                <li><a href="{{ route('login') }}" class="footer-menu_item">Account</a></li>
                            </ul>
                        </div>
                        <div class="col-xl-3 col-md-6 col-12">
                            <div class="footer-newsletter footer-col-block">
                                <div class="footer-heading footer-heading-mobile">
                                    <h6 class="font-young-serif" data-bs-toggle="collapse"
                                        data-bs-target="#newsletterCollapse" aria-expanded="false"
                                        aria-controls="newsletterCollapse">Sign Up for Email</h6>
                                </div>
                                <div class="tf-collapse-content" id="newsletterCollapse">
                                    <div class="footer-menu_item">Sign up to get first dibs on new arrivals, sales,
                                        exclusive content, events and more!
                                    </div>
                                    <form class="form-newsletter" id="subscribe-form"
                                          action="{{ route('newsletter.subscribe') }}" method="POST"
                                          accept-charset="utf-8">
                                        @csrf
                                        <div id="subscribe-content">
                                            <fieldset class="email">
                                                <input type="email" name="email" id="subscribe-email"
                                                       placeholder="Enter your email...." required aria-required="true">
                                                @error('email')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </fieldset>
                                            <div class="button-submit">
                                                <button id="subscribe-button"
                                                        class="tf-btn btn-sm radius-60 btn-fill btn-icon animate-hover-btn"
                                                        type="submit">
                                                    Subscribe<i class="icon icon-arrow1-top-left"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @if (session('status'))
                                            <div id="subscribe-msg" style="color: green;">{{ session('status') }}</div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="footer-bottom-wrap d-flex flex-wrap justify-content-between align-items-center"
                                 style="gap: 20px;">
                                <div class="footer-menu_item">© {{ date('Y') }} {{ config('app.name', 'ShopWithCarl') }}
                                    . All Rights Reserved
                                </div>
                                <div class="tf-payment">
                                    @if(config('payment.methods'))
                                        @foreach (config('payment.methods') as $method)
                                            <img src="{{ asset($method['image']) }}" alt="{{ $method['name'] }}">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="menu-list">
                <li><a href="{{ route('home') }}" class="menu-link-text">Home</a></li>
                <li><a href="{{ route('shop.index') }}" class="menu-link-text">Shop</a></li>
                <li><a href="{{ route('products.index') }}" class="menu-link-text">Products</a></li>
                <li><a href="{{ route('pages.about-us') }}" class="menu-link-text">About us</a></li>
                <li><a href="{{ route('blog.index') }}" class="menu-link-text">Blog</a></li>
                @auth
                    <li><a href="{{ route('user.dashboard') }}" class="menu-link-text">Dashboard</a></li>
                    <li><a href="{{ route('logout') }}" class="menu-link-text"
                           onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">Log
                            Out</a></li>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <li><a href="{{ route('login') }}" class="menu-link-text">Login</a></li>
                    <li><a href="{{ route('register') }}" class="menu-link-text">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</div>

@fluxScripts
</body>
</html>
