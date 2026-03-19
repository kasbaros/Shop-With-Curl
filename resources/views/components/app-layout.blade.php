<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'ShopWithCarl') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|poppins:300,400,500,600,700|playfair-display:400,500,600,700&display=swap"
        rel="stylesheet"/>

    <script>
        // Fix: LiteSpeed/cPanel injects HTML before Livewire JSON responses.
        // Patch fetch() early so Livewire (auto-injected) uses the clean version.
        (function() {
            var _fetch = window.fetch;
            window.fetch = function(url, opts) {
                return _fetch.apply(this, arguments).then(function(res) {
                    if (typeof url === 'string' && url.indexOf('/livewire/update') !== -1) {
                        return res.text().then(function(t) {
                            var i = t.indexOf('{');
                            if (i > 0) t = t.substring(i);
                            return new Response(t, { status: res.status, statusText: res.statusText, headers: res.headers });
                        });
                    }
                    return res;
                });
            };
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @stack('styles')

    <style>
        :root {
            --brand-primary: #5b3a79;
            --brand-secondary: #d9a6cc;
            --accent-gradient: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            --luxury-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        /* Fix dark background from home_page.css slider styles */
        html { overflow: auto !important; height: auto !important; }
        body {
            background: #fff !important;
            height: auto !important;
            overflow-y: auto !important;
        }

        /* Fix dropdown submenu alignment */
        .header-bottom .box-nav-ul .menu-item { position: relative; }
        .header-bottom .box-nav-ul .sub-menu.submenu-default { left: 0; }
        /* Dropdown link hover highlight */
        .box-nav-ul .sub-menu .menu-list li a.menu-link-text {
            display: block; padding: 6px 0; transition: color 0.2s, padding-left 0.2s;
        }
        .box-nav-ul .sub-menu .menu-list li a.menu-link-text:hover {
            color: var(--brand-primary); padding-left: 6px;
        }

        /* Header structure & contrast — match Ecomus activewear style */
        #header { border-bottom: 1px solid #e5e5e5; }
        #header .main-header { border-bottom: 1px solid #ebebeb; }
        #header .header-bottom {
            border-top: 1px solid #ebebeb;
        }
        #header .header-bottom .box-nav-ul .menu-item { padding: 14px 0; }

        /* Brand color overrides for Ecomus header */
        #header .box-nav-ul .item-link:hover { color: var(--brand-primary); }
        #header .box-nav-ul li:hover .item-link::before { background: var(--brand-primary); }
        #header .nav-icon .nav-icon-item:hover { color: var(--brand-primary); }
        #header .nav-icon .count-box { background-color: var(--brand-primary); }
        .announcement-bar { background-color: var(--brand-primary); }
        .tf-toolbar-bottom .toolbar-item a .toolbar-icon .toolbar-count { background-color: var(--brand-primary); }

        /* User dropdown styling */
        .header-user-dropdown { position: relative; display: inline-flex; }
        .header-user-dropdown .dropdown-menu {
            min-width: 200px; border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.08);
            padding: 0.5rem 0; margin-top: 8px;
        }
        .header-user-dropdown .dropdown-item { padding: 0.5rem 1rem; font-size: 14px; }
        .header-user-dropdown .dropdown-item:hover { background-color: rgba(91, 58, 121, 0.08); color: var(--brand-primary); }
        .header-user-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--accent-gradient); display: inline-flex;
            align-items: center; justify-content: center;
            color: #fff; font-weight: 600; font-size: 11px;
        }

        /* Search suggestions (used by header search JS) */
        .search-suggestions {
            position: absolute; top: calc(100% + 8px); left: 0; right: 0;
            background: #fff; border: 1px solid rgba(0,0,0,0.1); border-radius: 12px;
            padding: 8px; box-shadow: 0 12px 40px rgba(0,0,0,0.12); z-index: 1100;
        }
        .search-suggestion-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px; text-decoration: none; color: #333;
        }
        .search-suggestion-item:hover { background: rgba(91, 58, 121, 0.06); color: var(--brand-primary); }
        .search-suggestion-empty { padding: 10px 12px; color: #888; font-size: 14px; }

        /* Logo sizing */
        .logo-header img { max-height: 45px; width: auto; }

        /* Footer overrides */
        .footer .footer-heading h6 {
            font-size: 18px; font-weight: 600; line-height: 18px;
            font-family: "Albert Sans", sans-serif;
        }
        .footer .subscribe-content {
            display: flex; align-items: center;
            border: 1px solid #ccc; border-radius: 60px;
            overflow: hidden; margin-top: 16px;
        }
        .footer .subscribe-content .email {
            flex-grow: 1; border: none; margin: 0;
        }
        .footer .subscribe-content input {
            padding: 12px 20px; border: none; width: 100%;
            background: transparent; outline: none; font-size: 14px;
        }
        .footer .subscribe-content .tf-btn {
            flex-shrink: 0; border-radius: 60px; margin: 4px;
        }
        /* Auth canvas tabs */
        .canvas-auth-tab.active {
            border-bottom: 2px solid #000 !important;
            color: #000 !important;
        }
        #canvasAccount .canvas-body { overflow-y: auto; }
        #canvasAccount .tf-field { margin-bottom: 0; }
    </style>
</head>

<body>
<div id="wrapper" style="display: flex; flex-direction: column; min-height: 100vh;">

    {{-- ==================== ANNOUNCEMENT BAR ==================== --}}
    @if (!($isAuthPage ?? false) && config('announcements.messages') && count(config('announcements.messages')) > 0)
        <div class="announcement-bar" id="announcementBar">
            <div class="wrap-announcement-bar">
                <div class="box-sw-announcement-bar">
                    @foreach (config('announcements.messages') as $message)
                        <div class="announcement-bar-item">
                            <p>{{ $message }}</p>
                        </div>
                    @endforeach
                    {{-- Duplicate for seamless scrolling --}}
                    @foreach (config('announcements.messages') as $message)
                        <div class="announcement-bar-item">
                            <p>{{ $message }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <span class="icon-close close-announcement-bar" onclick="this.closest('.announcement-bar').style.display='none'"></span>
        </div>
    @endif

    {{-- ==================== HEADER ==================== --}}
    @if (!($isAuthPage ?? false))
    <header id="header" class="header-default header-style-2">
        {{-- Main Header Row --}}
        <div class="main-header">
            <div class="container-full px_15 lg-px_40">
                <div class="row wrapper-header align-items-center">

                    {{-- Left column: empty on desktop, hamburger on mobile --}}
                    <div class="col-xl-5 tf-md-hidden"></div>
                    <div class="col-md-4 col-3 tf-lg-hidden">
                        <a href="#mobileMenu" data-bs-toggle="offcanvas" aria-controls="mobileMenu">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="16" viewBox="0 0 24 16"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <line x1="0" y1="1" x2="14" y2="1"/>
                                <line x1="0" y1="8" x2="24" y2="8"/>
                                <line x1="0" y1="15" x2="18" y2="15"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Centered Logo --}}
                    <div class="col-xl-2 col-md-4 col-6 text-center">
                        <a href="{{ route('home') }}" class="logo-header" wire:navigate>
                            @if(file_exists(public_path('images/logo/SWC 2.png')))
                                <img src="{{ asset('images/logo/SWC 2.png') }}" alt="{{ config('app.name', 'ShopWithCarl') }}">
                            @else
                                {{ config('app.name', 'ShopWithCarl') }}
                            @endif
                        </a>
                    </div>

                    {{-- Nav Icons (right side) --}}
                    <div class="col-xl-5 col-md-4 col-3">
                        <ul class="nav-icon d-flex justify-content-end align-items-center gap-20">

                            {{-- Search icon → opens canvasSearch --}}
                            <li class="nav-search">
                                <a href="#canvasSearch" data-bs-toggle="offcanvas" aria-controls="canvasSearch" class="nav-icon-item">
                                    <i class="icon icon-search"></i>
                                </a>
                            </li>

                            {{-- Account dropdown --}}
                            <li>
                                <div class="header-user-dropdown dropdown">
                                    @auth
                                        <a href="#" class="nav-icon-item dropdown-toggle" data-bs-toggle="dropdown"
                                           aria-expanded="false" role="button">
                                            <i class="icon icon-account"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if(auth()->user()->isAdmin() || auth()->user()->isDeveloper())
                                                <li>
                                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                                       href="{{ route('admin.dashboard') }}">
                                                        <i class="bi bi-speedometer2" style="width:16px;"></i>
                                                        Admin Dashboard
                                                    </a>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2"
                                                   href="{{ route('account.dashboard') }}">
                                                    <i class="bi bi-house" style="width:16px;"></i>
                                                    My Account
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2"
                                                   href="{{ route('orders.index') }}">
                                                    <i class="bi bi-bag" style="width:16px;"></i>
                                                    My Orders
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2"
                                                   href="{{ route('account.page', ['section' => 'details']) }}">
                                                    <i class="bi bi-person" style="width:16px;"></i>
                                                    Profile
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                                    @csrf
                                                    <button type="submit"
                                                            class="dropdown-item d-flex align-items-center gap-2 text-danger border-0 bg-transparent w-100 text-start">
                                                        <i class="bi bi-box-arrow-right" style="width:16px;"></i>
                                                        Logout
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    @else
                                        <a href="#canvasAccount" data-bs-toggle="offcanvas" aria-controls="canvasAccount" class="nav-icon-item">
                                            <i class="icon icon-account"></i>
                                        </a>
                                    @endauth
                                </div>
                            </li>

                            {{-- Wishlist --}}
                            <li>
                                <a href="{{ route('account.page', ['section' => 'wishlist']) }}"
                                   class="nav-icon-item" title="Wishlist">
                                    <i class="icon icon-heart"></i>
                                    @php
                                        $wCount = $wishlistCount ?? (auth()->check() ? auth()->user()->wishlist()->count() : 0);
                                    @endphp
                                    @if($wCount > 0)
                                        <span class="count-box">{{ $wCount }}</span>
                                    @endif
                                </a>
                            </li>

                            {{-- Cart --}}
                            <li>
                                @livewire('components.cart-icon')
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        {{-- Navigation Bar (hidden on mobile, visible >= md) --}}
        <div class="header-bottom bg_grey-11 tf-md-hidden">
            <div class="container-full px_15 lg-px_40">
                <div class="wrapper-header d-flex justify-content-center">
                    <nav class="box-navigation text-center">
                        <ul class="box-nav-ul d-flex align-items-center justify-content-center gap-30">
                            <li class="menu-item">
                                <a href="{{ route('home') }}" class="item-link" wire:navigate>Home</a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('shop.index') }}" class="item-link" wire:navigate>Shop</a>
                            </li>

                            @php
                                $mainCategories = \App\Models\Category::whereNull('parent_id')->active()->orderBy('sort_order')->get();
                            @endphp

                            @foreach($mainCategories as $category)
                                @php
                                    $subcategories = $category->children()->active()->orderBy('sort_order')->get();
                                @endphp

                                <li class="menu-item">
                                    @if($subcategories->count() > 0)
                                        <a href="javascript:void(0);" class="item-link">
                                            {{ $category->name }}
                                            <i class="icon icon-arrow-down"></i>
                                        </a>
                                        <div class="sub-menu submenu-default">
                                            <ul class="menu-list">
                                                @foreach($subcategories as $subcategory)
                                                    <li>
                                                        <a href="{{ route('categories.show', $subcategory->slug) }}"
                                                           class="menu-link-text" wire:navigate>
                                                            {{ $subcategory->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ route('categories.show', $category->slug) }}"
                                           class="item-link" wire:navigate>{{ $category->name }}</a>
                                    @endif
                                </li>
                            @endforeach

                            <li class="menu-item">
                                <a href="{{ route('pages.contact') }}" class="item-link" wire:navigate>Contact Us</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    @endif

    {{-- ==================== FLASH MESSAGES ==================== --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main style="flex: 1 0 auto;">
        {!! $slot !!}
    </main>

    {{-- ==================== CART DRAWER ==================== --}}
    <livewire:client.cart.cart-drawer/>

    {{-- ==================== FOOTER ==================== --}}
    @if (!isset($isAuthPage) || !$isAuthPage)
        <footer id="footer" class="footer background-gray md-pb-70">
            <div class="footer-wrap">
                <div class="footer-body">
                    <div class="container">
                        <div class="row">
                            {{-- Col 1: Company Info --}}
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="footer-infor">
                                    <div class="footer-logo">
                                        <a href="{{ route('home') }}" wire:navigate>
                                            @if(file_exists(public_path('images/logo/SWC 2.png')))
                                                <img src="{{ asset('images/logo/SWC 2.png') }}" alt="{{ config('app.name', 'ShopWithCarl') }}" style="max-height: 40px;">
                                            @else
                                                {{ config('app.name', 'ShopWithCarl') }}
                                            @endif
                                        </a>
                                    </div>
                                    <ul>
                                        <li>
                                            <p>Address: {{ config('contact.address', '123 Fashion Avenue, Style District') }}</p>
                                        </li>
                                        <li>
                                            <p>Email: <a href="mailto:{{ config('contact.email', 'hello@shopwithcarl.com') }}">{{ config('contact.email', 'hello@shopwithcarl.com') }}</a></p>
                                        </li>
                                        <li>
                                            <p>Phone: <a href="tel:{{ config('contact.phone', '+1234567890') }}">{{ config('contact.phone', '+1 (234) 567-890') }}</a></p>
                                        </li>
                                    </ul>
                                    <a href="{{ route('pages.contact') }}" class="tf-btn btn-line" wire:navigate>
                                        Get direction<i class="icon icon-arrow1-top-left"></i>
                                    </a>
                                    <ul class="tf-social-icon d-flex gap-10 mt_20">
                                        @if(config('contact.socials'))
                                            @foreach (config('contact.socials') as $platform => $url)
                                                @php $p = strtolower($platform); @endphp
                                                <li>
                                                    <a href="{{ $url }}" class="box-icon w_34 round social-line" title="{{ ucfirst($platform) }}">
                                                        @if($p === 'facebook')
                                                            <i class="icon fs-14 icon-facebook"></i>
                                                        @elseif($p === 'instagram')
                                                            <i class="icon fs-12 icon-instagram"></i>
                                                        @elseif($p === 'twitter' || $p === 'x' || $p === 'x-twitter')
                                                            <i class="icon fs-12 icon-Icon-x"></i>
                                                        @elseif($p === 'tiktok')
                                                            <i class="icon fs-12 icon-tiktok"></i>
                                                        @elseif($p === 'pinterest')
                                                            <i class="icon fs-12 icon-pinterest"></i>
                                                        @elseif($p === 'youtube')
                                                            <i class="icon fs-12 icon-youtube"></i>
                                                        @else
                                                            <i class="icon fs-12 icon-share"></i>
                                                        @endif
                                                    </a>
                                                </li>
                                            @endforeach
                                        @else
                                            <li><a href="#" class="box-icon w_34 round social-line"><i class="icon fs-14 icon-facebook"></i></a></li>
                                            <li><a href="#" class="box-icon w_34 round social-line"><i class="icon fs-12 icon-instagram"></i></a></li>
                                            <li><a href="#" class="box-icon w_34 round social-line"><i class="icon fs-12 icon-Icon-x"></i></a></li>
                                            <li><a href="#" class="box-icon w_34 round social-line"><i class="icon fs-12 icon-tiktok"></i></a></li>
                                            <li><a href="#" class="box-icon w_34 round social-line"><i class="icon fs-12 icon-pinterest"></i></a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            {{-- Col 2: Help --}}
                            <div class="col-xl-3 col-md-6 col-12 footer-col-block">
                                <div class="footer-heading footer-heading-desktop">
                                    <h6>Help</h6>
                                </div>
                                <div class="footer-heading footer-heading-moblie">
                                    <h6>Help</h6>
                                </div>
                                <ul class="footer-menu-list tf-collapse-content">
                                    <li><a href="#" class="footer-menu_item">Privacy Policy</a></li>
                                    <li><a href="#" class="footer-menu_item">Returns + Exchanges</a></li>
                                    <li><a href="#" class="footer-menu_item">Shipping</a></li>
                                    <li><a href="#" class="footer-menu_item">Terms &amp; Conditions</a></li>
                                    <li><a href="#" class="footer-menu_item">FAQ's</a></li>
                                    <li><a href="{{ route('compare.index') }}" class="footer-menu_item" wire:navigate>Compare</a></li>
                                    <li><a href="{{ route('account.page', ['section' => 'wishlist']) }}" class="footer-menu_item">My Wishlist</a></li>
                                </ul>
                            </div>

                            {{-- Col 3: Useful Links --}}
                            <div class="col-xl-3 col-md-6 col-12 footer-col-block">
                                <div class="footer-heading footer-heading-desktop">
                                    <h6>Useful Links</h6>
                                </div>
                                <div class="footer-heading footer-heading-moblie">
                                    <h6>Useful Links</h6>
                                </div>
                                <ul class="footer-menu-list tf-collapse-content">
                                    <li><a href="{{ route('pages.about') }}" class="footer-menu_item" wire:navigate>About Us</a></li>
                                    <li><a href="{{ route('shop.index') }}" class="footer-menu_item" wire:navigate>Shop</a></li>
                                    <li><a href="{{ route('pages.contact') }}" class="footer-menu_item" wire:navigate>Contact Us</a></li>
                                    <li><a href="{{ route('account.dashboard') }}" class="footer-menu_item">My Account</a></li>
                                </ul>
                            </div>

                            {{-- Col 4: Newsletter --}}
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="footer-newsletter footer-col-block">
                                    <div class="footer-heading footer-heading-desktop">
                                        <h6>Sign Up for Email</h6>
                                    </div>
                                    <div class="footer-heading footer-heading-moblie">
                                        <h6>Sign Up for Email</h6>
                                    </div>
                                    <div class="tf-collapse-content">
                                        <div class="footer-menu_item">Sign up to get first dibs on new arrivals, sales, exclusive content, events and more!</div>
                                        <form class="form-newsletter" action="{{ route('newsletter.subscribe') }}" method="POST" onsubmit="handleNewsletter(event)">
                                            @csrf
                                            <div class="subscribe-content">
                                                <fieldset class="email border-none">
                                                    <input type="email" name="email" placeholder="Enter your email...." required>
                                                </fieldset>
                                                <button type="submit" class="tf-btn btn-sm radius-60 btn-fill btn-icon animate-hover-btn">
                                                    Subscribe<i class="icon icon-arrow1-top-left"></i>
                                                </button>
                                            </div>
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
                                <div class="footer-bottom-wrap d-flex align-items-center justify-content-between flex-wrap gap-10">
                                    <div class="footer-body-text">
                                        <span>&copy; {{ date('Y') }} {{ config('app.name', 'ShopWithCarl') }}. All Rights Reserved.</span>
                                    </div>
                                    <div class="tf-payment d-flex align-items-center gap-10">
                                        <span class="fw-6" style="font-size: 12px;">MTN</span>
                                        <span class="fw-6" style="font-size: 12px;">Airtel</span>
                                        <span class="fw-6" style="font-size: 12px;">Visa</span>
                                        <span class="fw-6" style="font-size: 12px;">PayPal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    @endif

</div>
{{-- end #wrapper --}}

{{-- ==================== MOBILE MENU (Ecomus offcanvas) ==================== --}}
@if (!($isAuthPage ?? false))
    <div class="offcanvas offcanvas-start canvas-mb" id="mobileMenu">
        <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
        <div class="mb-canvas-content">
            <div class="mb-body">
                <ul class="nav-ul-mb" id="wrapper-menu-navigation">
                    <li class="nav-mb-item">
                        <a href="{{ route('home') }}" class="mb-menu-link"><span>Home</span></a>
                    </li>
                    <li class="nav-mb-item">
                        <a href="{{ route('shop.index') }}" class="mb-menu-link"><span>Shop</span></a>
                    </li>

                    @php
                        if (!isset($mainCategories)) {
                            $mainCategories = \App\Models\Category::whereNull('parent_id')->active()->orderBy('sort_order')->get();
                        }
                    @endphp

                    @foreach($mainCategories as $cat)
                        <li class="nav-mb-item">
                            <a href="{{ route('categories.show', $cat->slug) }}" class="mb-menu-link">
                                <span>{{ $cat->name }}</span>
                            </a>
                        </li>
                    @endforeach

                    <li class="nav-mb-item">
                        <a href="{{ route('pages.contact') }}" class="mb-menu-link"><span>Contact Us</span></a>
                    </li>
                </ul>

                <div class="mb-other-content">
                    <div class="d-flex group-icon">
                        <a href="{{ route('account.page', ['section' => 'wishlist']) }}" class="site-nav-icon">
                            <i class="icon icon-heart"></i>Wishlist
                        </a>
                        <a href="#" class="site-nav-icon" data-bs-toggle="offcanvas" data-bs-target="#canvasSearch">
                            <i class="icon icon-search"></i>Search
                        </a>
                    </div>
                    <div class="mb-notice">
                        <a href="{{ route('pages.contact') }}" class="text-need">Need help ?</a>
                    </div>
                </div>
            </div>
            <div class="mb-bottom">
                @guest
                    <a href="#canvasAccount" data-bs-toggle="offcanvas" aria-controls="canvasAccount" class="site-nav-icon"><i class="icon icon-account"></i>Login</a>
                @else
                    <a href="{{ route('account.dashboard') }}" class="site-nav-icon"><i class="icon icon-account"></i>My Account</a>
                @endguest
            </div>
        </div>
    </div>

    {{-- ==================== SEARCH CANVAS ==================== --}}
    <div class="offcanvas offcanvas-end canvas-search" id="canvasSearch">
        <div class="canvas-wrapper">
            <header class="tf-search-head">
                <div class="title fw-5">
                    Search our site
                    <div class="close">
                        <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close"></span>
                    </div>
                </div>
                <div class="tf-search-sticky">
                    <form class="tf-mini-search-frm" action="{{ route('shop.search') }}" method="GET">
                        <fieldset class="text">
                            <input type="text" placeholder="Search" name="q" tabindex="0" value=""
                                   aria-required="true" required>
                        </fieldset>
                        <button type="submit"><i class="icon-search"></i></button>
                    </form>
                </div>
            </header>
            <div class="canvas-body p-0">
                <div class="tf-search-content">
                    <div class="tf-cart-hide-has-results">
                        <div class="tf-col-quicklink">
                            <div class="tf-search-content-title fw-5">Quick link</div>
                            <ul class="tf-quicklink-list">
                                @foreach($mainCategories->take(4) as $cat)
                                    <li class="tf-quicklink-item">
                                        <a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== LOGIN / REGISTER CANVAS ==================== --}}
    @guest
    <div class="offcanvas offcanvas-end" id="canvasAccount" tabindex="-1">
        <div class="canvas-wrapper">
            <header class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                <h5 class="fw-bold mb-0" id="canvasAccountTitle">Login</h5>
                <span class="icon-close icon-close-popup" data-bs-dismiss="offcanvas" aria-label="Close" style="cursor:pointer;"></span>
            </header>
            <div class="canvas-body px-4 py-4">
                {{-- Tab buttons --}}
                <div class="d-flex mb-4 border-bottom">
                    <button type="button" class="canvas-auth-tab active fw-5 pb-2 me-4 border-0 bg-transparent" data-target="canvasLoginForm">Login</button>
                    <button type="button" class="canvas-auth-tab fw-5 pb-2 border-0 bg-transparent text-secondary" data-target="canvasRegisterForm">Register</button>
                </div>

                {{-- Login Form --}}
                <div id="canvasLoginForm" class="canvas-auth-pane">
                    <div id="canvasLoginAlerts"></div>
                    <form action="{{ route('login') }}" method="POST" id="canvasLoginFormEl">
                        @csrf
                        <div class="mb-3">
                            <label for="canvas-email" class="mb-2 fw-5">Email Address</label>
                            <div class="tf-field">
                                <input type="email" class="tf-input" id="canvas-email" name="email"
                                       required autocomplete="email" placeholder="email@example.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="canvas-password" class="fw-5">Password</label>
                                <button type="button" class="text-secondary fs-12 border-0 bg-transparent p-0 canvas-auth-switch" data-target="canvasForgotForm">Forgot password?</button>
                            </div>
                            <div class="tf-field">
                                <input type="password" class="tf-input" id="canvas-password" name="password"
                                       required autocomplete="current-password" placeholder="Password">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <input type="checkbox" id="canvas-remember" name="remember">
                                <label for="canvas-remember">Remember me</label>
                            </div>
                        </div>

                        <button type="submit" class="tf-btn btn-fill radius-60 animate-hover-btn w-100 justify-content-center">
                            Log in
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-secondary">Don't have an account?</span>
                        <button type="button" class="fw-5 text-decoration-underline border-0 bg-transparent canvas-auth-switch" data-target="canvasRegisterForm">Sign up</button>
                    </div>
                </div>

                {{-- Register Form --}}
                <div id="canvasRegisterForm" class="canvas-auth-pane" style="display:none;">
                    <div id="canvasRegisterAlerts"></div>
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="canvas-reg-name" class="mb-2 fw-5">Name</label>
                            <div class="tf-field">
                                <input type="text" class="tf-input" id="canvas-reg-name" name="name"
                                       required autocomplete="name" placeholder="Full name">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="canvas-reg-email" class="mb-2 fw-5">Email Address</label>
                            <div class="tf-field">
                                <input type="email" class="tf-input" id="canvas-reg-email" name="email"
                                       required autocomplete="email" placeholder="email@example.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="canvas-reg-password" class="mb-2 fw-5">Password</label>
                            <div class="tf-field">
                                <input type="password" class="tf-input" id="canvas-reg-password" name="password"
                                       required autocomplete="new-password" placeholder="Password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="canvas-reg-password-confirm" class="mb-2 fw-5">Confirm Password</label>
                            <div class="tf-field">
                                <input type="password" class="tf-input" id="canvas-reg-password-confirm" name="password_confirmation"
                                       required autocomplete="new-password" placeholder="Confirm password">
                            </div>
                        </div>

                        <button type="submit" class="tf-btn btn-fill radius-60 animate-hover-btn w-100 justify-content-center">
                            Create Account
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-secondary">Already have an account?</span>
                        <button type="button" class="fw-5 text-decoration-underline border-0 bg-transparent canvas-auth-switch" data-target="canvasLoginForm">Log in</button>
                    </div>
                </div>

                {{-- Forgot Password Form --}}
                <div id="canvasForgotForm" class="canvas-auth-pane" style="display:none;">
                    <div class="text-center mb-4">
                        <p class="text-secondary">Enter your email to receive a password reset link</p>
                    </div>
                    <div id="canvasForgotAlerts"></div>
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="canvas-forgot-email" class="mb-2 fw-5">Email Address</label>
                            <div class="tf-field">
                                <input type="email" class="tf-input" id="canvas-forgot-email" name="email"
                                       required autocomplete="email" placeholder="email@example.com">
                            </div>
                        </div>

                        <button type="submit" class="tf-btn btn-fill radius-60 animate-hover-btn w-100 justify-content-center">
                            Email Password Reset Link
                        </button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-secondary">Remember your password?</span>
                        <button type="button" class="fw-5 text-decoration-underline border-0 bg-transparent canvas-auth-switch" data-target="canvasLoginForm">Log in</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endguest

    {{-- ==================== MOBILE TOOLBAR BOTTOM ==================== --}}
    <div class="tf-toolbar-bottom type-1150">
        <div class="toolbar-item">
            <a href="{{ route('shop.index') }}">
                <div class="toolbar-icon">
                    <i class="icon-shop"></i>
                </div>
                <div class="toolbar-label">Shop</div>
            </a>
        </div>
        <div class="toolbar-item">
            <a href="#canvasSearch" data-bs-toggle="offcanvas" aria-controls="canvasSearch">
                <div class="toolbar-icon">
                    <i class="icon-search"></i>
                </div>
                <div class="toolbar-label">Search</div>
            </a>
        </div>
        <div class="toolbar-item">
            @guest
                <a href="#canvasAccount" data-bs-toggle="offcanvas" aria-controls="canvasAccount">
                    <div class="toolbar-icon">
                        <i class="icon-account"></i>
                    </div>
                    <div class="toolbar-label">Account</div>
                </a>
            @else
                <a href="{{ route('account.dashboard') }}">
                    <div class="toolbar-icon">
                        <i class="icon-account"></i>
                    </div>
                    <div class="toolbar-label">Account</div>
                </a>
            @endguest
        </div>
        <div class="toolbar-item">
            <a href="{{ route('account.page', ['section' => 'wishlist']) }}">
                <div class="toolbar-icon">
                    <i class="icon-heart"></i>
                    @php
                        $wCountToolbar = $wishlistCount ?? (auth()->check() ? auth()->user()->wishlist()->count() : 0);
                    @endphp
                    @if($wCountToolbar > 0)
                        <div class="toolbar-count">{{ $wCountToolbar }}</div>
                    @endif
                </div>
                <div class="toolbar-label">Wishlist</div>
            </a>
        </div>
        <div class="toolbar-item">
            <a href="{{ route('account.page', ['section' => 'cart']) }}">
                <div class="toolbar-icon">
                    <i class="icon-bag"></i>
                    @php
                        $cCount = $cartCount ?? 0;
                    @endphp
                    @if($cCount > 0)
                        <div class="toolbar-count">{{ $cCount }}</div>
                    @endif
                </div>
                <div class="toolbar-label">Cart</div>
            </a>
        </div>
    </div>
@endif

<!-- Core Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

@stack('scripts')

<script>
    // Handle newsletter form submission
    function handleNewsletter(event) {
        event.preventDefault();
        const form = event.target;
        const email = form.querySelector('input[name="email"]').value;
        alert('Thank you for subscribing!');
        form.reset();
    }
</script>

<!-- Global Notification Toast -->
<livewire:components.notification-toast/>

<!-- Shopping Cart Component -->
<livewire:components.shopping-cart/>

<!-- Quick View Modal (Global) -->
<livewire:components.product-quick-view/>

<!-- Compare Modal (Bottom bar) -->
<livewire:components.compare-modal/>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('open-bs-modal', ({id}) => {
            $(id).modal('show');
        });

        Livewire.on('close-bs-modal', ({id}) => {
            $(id).modal('hide');
        });

        $('#shoppingCart').on('hidden.bs.modal', function () {
            if (Livewire.getByName('client.cart.cart-drawer').length > 0) {
                Livewire.dispatch('cart-drawer-was-closed');
            }
        });

        // Auth canvas tab switching
        document.querySelectorAll('.canvas-auth-tab, .canvas-auth-switch').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var target = this.dataset.target;
                document.querySelectorAll('.canvas-auth-pane').forEach(function(p) { p.style.display = 'none'; });
                document.getElementById(target).style.display = 'block';
                document.querySelectorAll('.canvas-auth-tab').forEach(function(t) { t.classList.remove('active'); t.classList.add('text-secondary'); });
                var matchTab = document.querySelector('.canvas-auth-tab[data-target="' + target + '"]');
                if (matchTab) { matchTab.classList.add('active'); matchTab.classList.remove('text-secondary'); }
                var title = document.getElementById('canvasAccountTitle');
                var titles = { canvasLoginForm: 'Login', canvasRegisterForm: 'Register', canvasForgotForm: 'Forgot Password' };
                if (title) title.textContent = titles[target] || 'Account';
            });
        });
    });
</script>

</body>
</html>
