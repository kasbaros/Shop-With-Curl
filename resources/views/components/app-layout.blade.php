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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
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
            background-color: #f5f5f5;
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

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white; position: relative; overflow: hidden; margin-top: 100px;
        }
        .footer::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 2px; background: var(--accent-gradient);
        }
        .footer-content {
            max-width: 1440px; margin: 0 auto; padding: 100px 32px 50px;
            position: relative; z-index: 2;
        }
        .footer-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 80px; margin-bottom: 80px;
        }
        .footer-section h3 {
            font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 600;
            margin-bottom: 32px; background: var(--luxury-gradient);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .footer-section p, .footer-section li {
            color: rgba(255,255,255,0.8); margin-bottom: 16px; line-height: 1.7; font-size: 15px;
        }
        .footer-link {
            color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s;
            display: inline-block; padding: 4px 0;
        }
        .footer-link:hover { color: white; transform: translateX(6px); }
        .social-links { display: flex; gap: 20px; margin-top: 32px; }
        .social-link {
            width: 56px; height: 56px; border-radius: 20px;
            background: rgba(255,255,255,0.1); border: 2px solid rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            color: white; text-decoration: none; transition: all 0.3s;
        }
        .social-link:hover {
            background: rgba(255,255,255,0.15); transform: translateY(-6px);
            border-color: var(--brand-secondary);
        }
        .social-link .icon { font-size: 22px; }
    </style>
</head>

<body>
<div id="wrapper">

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
    <header id="header" class="header-default">
        {{-- Main Header Row --}}
        <div class="main-header">
            <div class="container-full px_15 lg-px_40">
                <div class="row wrapper-header align-items-center">

                    {{-- Mobile hamburger (visible < lg) --}}
                    <div class="col-xl-3 tf-lg-hidden">
                        <a href="#mobileMenu" data-bs-toggle="offcanvas" aria-controls="mobileMenu">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="3" y1="6" x2="21" y2="6"/>
                                <line x1="3" y1="12" x2="21" y2="12"/>
                                <line x1="3" y1="18" x2="21" y2="18"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Logo --}}
                    <div class="col-xl-3 tf-lg-hidden">
                        <a href="{{ route('home') }}" class="logo-header" wire:navigate>
                            @if(file_exists(public_path('images/logo/SWC 2.png')))
                                <img src="{{ asset('images/logo/SWC 2.png') }}" alt="{{ config('app.name', 'ShopWithCarl') }}">
                            @else
                                {{ config('app.name', 'ShopWithCarl') }}
                            @endif
                        </a>
                    </div>

                    {{-- Desktop logo (visible >= lg) --}}
                    <div class="col-xl-3 tf-md-hidden">
                        <a href="{{ route('home') }}" class="logo-header" wire:navigate>
                            @if(file_exists(public_path('images/logo/SWC 2.png')))
                                <img src="{{ asset('images/logo/SWC 2.png') }}" alt="{{ config('app.name', 'ShopWithCarl') }}">
                            @else
                                {{ config('app.name', 'ShopWithCarl') }}
                            @endif
                        </a>
                    </div>

                    {{-- Nav Icons (right side) --}}
                    <div class="col-xl-9">
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
                                                   href="{{ route('account.dashboard') }}" wire:navigate>
                                                    <i class="bi bi-house" style="width:16px;"></i>
                                                    My Account
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2"
                                                   href="{{ route('orders.index') }}" wire:navigate>
                                                    <i class="bi bi-bag" style="width:16px;"></i>
                                                    My Orders
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2"
                                                   href="{{ route('account.page', ['section' => 'details']) }}" wire:navigate>
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
                                        <a href="{{ route('login') }}" class="nav-icon-item" wire:navigate>
                                            <i class="icon icon-account"></i>
                                        </a>
                                    @endauth
                                </div>
                            </li>

                            {{-- Wishlist --}}
                            <li>
                                <a href="{{ route('account.page', ['section' => 'wishlist']) }}"
                                   class="nav-icon-item" title="Wishlist" wire:navigate>
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
        <div class="header-bottom tf-md-hidden">
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
                                        <a href="{{ route('products.category', $category->slug) }}" class="item-link">
                                            {{ $category->name }}
                                            <i class="icon icon-arrow-down"></i>
                                        </a>
                                        <div class="sub-menu submenu-default">
                                            <ul class="menu-list">
                                                @foreach($subcategories as $subcategory)
                                                    <li>
                                                        <a href="{{ route('products.category', $subcategory->slug) }}"
                                                           class="menu-link-text" wire:navigate>
                                                            {{ $subcategory->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ route('products.category', $category->slug) }}"
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
    <main>
        {!! $slot !!}
    </main>

    {{-- ==================== CART DRAWER ==================== --}}
    <livewire:client.cart.cart-drawer/>

    {{-- ==================== FOOTER ==================== --}}
    @if (!isset($isAuthPage) || !$isAuthPage)
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-grid">
                    <!-- Company Info -->
                    <div class="footer-section">
                        <h3>ShopWithCarl</h3>
                        <p>Your premier destination for luxury women's undergarments, activewear, and shapewear. We
                            believe every woman deserves to feel confident and beautiful.</p>
                        <p>
                            <strong>Address:</strong> {{ config('contact.address', '123 Fashion Avenue, Style District') }}
                        </p>
                        <p><strong>Email:</strong> <a
                                href="mailto:{{ config('contact.email', 'hello@shopwithcarl.com') }}"
                                class="footer-link">{{ config('contact.email', 'hello@shopwithcarl.com') }}</a></p>
                        <p><strong>Phone:</strong> <a href="tel:{{ config('contact.phone', '+1234567890') }}"
                                                      class="footer-link">{{ config('contact.phone', '+1 (234) 567-890') }}</a>
                        </p>
                        <a href="{{ route('pages.contact') }}" class="footer-link"
                           style="margin-top: 16px; display: inline-block;" wire:navigate>Get Directions →</a>

                        <div class="social-links">
                            @if(config('contact.socials'))
                                @foreach (config('contact.socials') as $platform => $url)
                                    @php $p = strtolower($platform); @endphp
                                    <a href="{{ $url }}" class="social-link" title="{{ ucfirst($platform) }}">
                                        @if($p === 'instagram')
                                            <span class="icon icon-instagram"></span>
                                        @elseif($p === 'pinterest')
                                            <span class="icon icon-pinterest"></span>
                                        @elseif($p === 'youtube')
                                            <span class="icon icon-youtube"></span>
                                        @elseif($p === 'tiktok')
                                            <span class="icon icon-tiktok"></span>
                                        @elseif($p === 'twitter')
                                            <span class="icon icon-twitter"></span>
                                        @elseif($p === 'x' || $p === 'x-twitter')
                                            <span class="icon icon-Icon-x"></span>
                                        @elseif($p === 'facebook')
                                            <span class="icon icon-share"></span>
                                        @else
                                            <span class="icon icon-share"></span>
                                        @endif
                                    </a>
                                @endforeach
                            @else
                                <a href="#" class="social-link" title="Instagram">
                                    <span class="icon icon-instagram"></span>
                                </a>
                                <a href="#" class="social-link" title="Twitter/X">
                                    <span class="icon icon-Icon-x"></span>
                                </a>
                                <a href="#" class="social-link" title="Pinterest">
                                    <span class="icon icon-pinterest"></span>
                                </a>
                                <a href="#" class="social-link" title="YouTube">
                                    <span class="icon icon-youtube"></span>
                                </a>
                                <a href="#" class="social-link" title="TikTok">
                                    <span class="icon icon-tiktok"></span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <ul style="list-style: none; padding: 0;">
                            <li><a href="{{ route('home') }}" class="footer-link" wire:navigate>Home</a></li>
                            <li><a href="{{ route('shop.index') }}" class="footer-link" wire:navigate>Shop All</a></li>
                            <li><a href="{{ route('products.index') }}" class="footer-link" wire:navigate>New
                                    Arrivals</a></li>
                            <li><a href="#" class="footer-link">Best Sellers</a></li>
                            <li><a href="#" class="footer-link">Sale Items</a></li>
                            <li><a href="{{ route('pages.about') }}" class="footer-link" wire:navigate>About Us</a></li>
                            <li><a href="{{ route('pages.contact') }}" class="footer-link" wire:navigate>Contact</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div class="footer-section">
                        <h3>Shop Categories</h3>
                        <ul style="list-style: none; padding: 0;">
                            @php
                                $footerCategories = \App\Models\Category::active()->parent()->orderBy('sort_order')->take(8)->get();
                            @endphp
                            @foreach($footerCategories as $category)
                                <li><a href="{{ route('categories.show', $category->slug) }}" class="footer-link"
                                       wire:navigate>{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Customer Service -->
                    <div class="footer-section">
                        <h3>Customer Care</h3>
                        <ul style="list-style: none; padding: 0;">
                            <li><a href="#" class="footer-link">Help Center</a></li>
                            <li><a href="#" class="footer-link">Track Your Order</a></li>
                            <li><a href="#" class="footer-link">Shipping & Returns</a></li>
                            <li><a href="#" class="footer-link">Size Exchange</a></li>
                            <li><a href="#" class="footer-link">Gift Cards</a></li>
                            <li><a href="#" class="footer-link">Privacy Policy</a></li>
                            <li><a href="#" class="footer-link">Terms of Service</a></li>
                            <li><a href="#" class="footer-link">Accessibility</a></li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div class="footer-section">
                        <h3>Stay Connected</h3>
                        <p>Join our exclusive community for early access to new collections, styling tips, and special
                            offers just for you.</p>
                        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST"
                              onsubmit="handleNewsletter(event)" style="display: flex; gap: 12px; margin-top: 24px;">
                            @csrf
                            <input type="email" name="email" class="newsletter-input"
                                   placeholder="Enter your email address" required
                                   style="flex: 1; padding: 16px 20px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); color: white; font-size: 14px; outline: none;">
                            <button type="submit" class="newsletter-button"
                                    style="padding: 16px 24px; background: var(--accent-gradient); border: none; border-radius: 16px; color: white; font-weight: 600; cursor: pointer;">
                                Subscribe
                            </button>
                        </form>
                        <p style="font-size: 12px; color: rgba(255, 255, 255, 0.6); margin-top: 12px;">
                            By subscribing, you agree to receive marketing emails. Unsubscribe anytime.
                        </p>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="footer-bottom"
                     style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 40px; text-align: center;">
                    <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 16px;">
                        &copy; {{ date('Y') }} {{ config('app.name', 'ShopWithCarl') }}. All rights reserved.
                    </p>

                    <!-- Payment Methods -->
                    <div class="payment-methods"
                         style="display: flex; justify-content: center; gap: 16px; margin-top: 24px; flex-wrap: wrap;">
                        <div class="payment-method" title="MTN Mobile Money"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.2); font-size: 10px; font-weight: bold; color: #FFD700;">
                            MTN
                        </div>
                        <div class="payment-method" title="Airtel Money"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.2); font-size: 10px; font-weight: bold; color: #FF0000;">
                            AIRTEL
                        </div>
                        <div class="payment-method" title="Flutterwave"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.2);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" fill="#FF6B35"/>
                                <circle cx="12" cy="12" r="3" fill="#1B365C"/>
                            </svg>
                        </div>
                        <div class="payment-method" title="Pesapal"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.2);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="6" width="18" height="12" rx="2" fill="#0056B3"/>
                                <path d="M7 10h10v4H7z" fill="#FF0000"/>
                                <circle cx="9" cy="12" r="1" fill="white"/>
                                <circle cx="15" cy="12" r="1" fill="white"/>
                            </svg>
                        </div>
                        <div class="payment-method" title="Bank Transfer"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.2);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>
                                <path d="M9 9v.01"/><path d="M9 12v.01"/><path d="M9 15v.01"/><path d="M9 18v.01"/>
                            </svg>
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
                            <a href="{{ route('products.category', $cat->slug) }}" class="mb-menu-link">
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
                    <a href="{{ route('login') }}" class="site-nav-icon"><i class="icon icon-account"></i>Login</a>
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
                                        <a href="{{ route('products.category', $cat->slug) }}">{{ $cat->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                <a href="{{ route('login') }}">
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

@livewireScripts
@fluxScripts
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

<!-- Product Compare Component -->
<livewire:components.product-compare/>

<!-- Quick View Modal (Global) -->
<livewire:components.product-quick-view/>

<!-- Compare Modal (Quick feedback) -->
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
    });
</script>

</body>
</html>
