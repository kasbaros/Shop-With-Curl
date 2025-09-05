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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Brand palette from logo */
            --brand-primary: #5b3a79; /* dark purple */
            --brand-secondary: #d9a6cc; /* light lavender */
            --primary-gradient: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            --accent-gradient: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            --luxury-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            --premium-gold: linear-gradient(135deg, #d4af37 0%, #f7ef8a 100%);
            --glass-primary: rgba(255, 255, 255, 0.15);
            --glass-secondary: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow-soft: 0 8px 32px rgba(31, 38, 135, 0.2);
            --shadow-premium: 0 20px 60px rgba(0, 0, 0, 0.1);
            --shadow-glow: 0 0 40px rgba(91, 58, 121, 0.3);
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --text-luxury: #1a202c;
            --pink-primary: #e91e63;
            --pink-light: #f8bbd9;
            --purple-primary: #9c27b0;
            --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            --bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            overflow-x: hidden;
        }

        /* Ultra Modern Announcement Bar */
        .announcement-bar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white;
            padding: 14px 0;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(240, 147, 251, 0.3);
        }

        .announcement-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        .announcement-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            animation: slideAnnouncement 25s linear infinite;
            font-weight: 500;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        .announcement-item {
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
            background: rgba(255, 255, 255, 0.05);
            padding: 8px 16px;
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .announcement-icon {
            width: 24px;
            height: 24px;
            background: var(--premium-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        @keyframes slideAnnouncement {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        .close-announcement {
            position: absolute;
            right: 24px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 20px;
        }

        .close-announcement:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-50%) rotate(90deg) scale(1.1);
        }

        /* Ultra Modern Header */
        .header {
            background: rgba(255, 255, 255, 0.90);
            backdrop-filter: blur(40px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
            box-shadow: 0 4px 40px rgba(31, 38, 135, 0.1);
        }

        .header.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(50px);
            box-shadow: 0 8px 60px rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(240, 147, 251, 0.2);
        }

        .header-top {
            border-bottom: 1px solid rgba(240, 147, 251, 0.1);
            padding: 10px 0;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
        }

        .header-top-content {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .header-top-links {
            display: flex;
            gap: 24px;
        }

        .header-top-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            position: relative;
        }

        .header-top-links a::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-gradient);
            transition: var(--transition);
        }

        .header-top-links a:hover {
            color: var(--pink-primary);
        }

        .header-top-links a:hover::after {
            width: 100%;
        }

        .header-main {
            max-width: 1440px;
            margin: 0 auto;
            padding: 24px 32px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 48px;
        }

        /* Ultra Modern Logo */
        .logo-section {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
        }

        .logo::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 4px;
            background: var(--accent-gradient);
            border-radius: 2px;
            transition: var(--transition);
        }

        .logo:hover::after {
            width: 100%;
        }

        .logo:hover {
            transform: scale(1.05);
            filter: brightness(1.1);
        }

        .logo img {
            height: 45px;
            width: auto;
        }

        /* Ultra Modern Search */
        .search-section {
            position: relative;
            width: 100%;
            max-width: 550px;
        }

        .search-suggestions {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(30px);
            border: 2px solid rgba(240, 147, 251, 0.15);
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 20px 60px rgba(240, 147, 251, 0.2);
            z-index: 1100;
            animation: fadeInUp 0.25s ease;
        }

        .search-suggestion-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 14px;
            text-decoration: none;
            color: var(--text-primary);
            transition: var(--transition);
        }

        .search-suggestion-item:hover {
            background: rgba(240, 147, 251, 0.08);
            color: var(--pink-primary);
            transform: translateX(4px);
        }

        .search-suggestion-empty {
            padding: 12px 14px;
            color: var(--text-secondary);
            font-size: 14px;
        }

        .search-container {
            position: relative;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(30px);
            border: 2px solid rgba(240, 147, 251, 0.2);
            border-radius: 30px;
            padding: 6px;
            transition: var(--transition);
            box-shadow: 0 10px 40px rgba(240, 147, 251, 0.1);
        }

        .search-container:hover,
        .search-container:focus-within {
            background: rgba(255, 255, 255, 0.95);
            border-color: var(--pink-primary);
            box-shadow: 0 15px 50px rgba(240, 147, 251, 0.2);
            transform: translateY(-2px);
        }

        .search-input {
            flex: 1;
            border: none;
            background: none;
            padding: 16px 24px;
            font-size: 16px;
            color: var(--text-primary);
            outline: none;
            font-weight: 500;
        }

        .search-input::placeholder {
            color: var(--text-secondary);
            font-weight: 400;
        }

        .search-btn {
            background: var(--accent-gradient);
            border: none;
            border-radius: 24px;
            padding: 14px 24px;
            color: white;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.3);
        }

        .search-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 35px rgba(240, 147, 251, 0.4);
        }

        /* Ultra Modern Navigation Icons */
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 20px;
            justify-content: flex-end;
        }

        .nav-icon {
            position: relative;
            padding: 16px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(240, 147, 251, 0.15);
            color: var(--text-primary);
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        .nav-icon:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(240, 147, 251, 0.2);
            color: var(--pink-primary);
            border-color: var(--pink-primary);
        }

        .nav-icon svg {
            width: 24px;
            height: 24px;
            transition: var(--transition);
        }

        .nav-icon:hover svg {
            transform: scale(1.1);
        }

        .count-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent-gradient);
            color: white;
            border-radius: 50%;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            border: 3px solid white;
            animation: bounce 0.6s var(--bounce);
            box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 24px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(240, 147, 251, 0.15);
            color: var(--text-primary);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-weight: 500;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        .user-button:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(240, 147, 251, 0.2);
            color: var(--pink-primary);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
        }

        /* Ultra Modern Navigation Bar */
        .nav-bottom {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(40px);
            border-top: 1px solid rgba(240, 147, 251, 0.1);
            padding: 0 32px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1440px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
        }

        .main-nav {
            display: flex;
            align-items: center;
            gap: 80px;
            padding: 28px 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-item .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            min-width: 200px;
            z-index: 1000;
            padding: 0.5rem 0;
        }

        .nav-item:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(91, 58, 121, 0.1);
            color: var(--brand-primary);
        }

        .dropdown-divider {
            height: 1px;
            background-color: rgba(0,0,0,0.1);
            margin: 0.5rem 0;
        }

        .dropdown-toggle::after {
            content: '▼';
            font-size: 0.8rem;
            margin-left: 0.5rem;
            transition: var(--transition);
        }

        .nav-item:hover .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-luxury);
            text-decoration: none;
            padding: 14px 0;
            transition: var(--transition);
            position: relative;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            width: 0;
            height: 4px;
            background: var(--accent-gradient);
            border-radius: 2px;
            transition: var(--transition);
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--pink-primary);
            transform: translateY(-2px);
        }

        /* Ultra Modern Mega Menu */
        .mega-menu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(40px);
            border-radius: 28px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
            border: 2px solid rgba(240, 147, 251, 0.1);
            min-width: 700px;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 1000;
            margin-top: 24px;
            padding: 40px;
        }

        .nav-item:hover .mega-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .mega-menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }

        .mega-menu-section h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--text-luxury);
            margin-bottom: 20px;
            font-size: 18px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .mega-menu-item {
            display: block;
            padding: 14px 0;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .mega-menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 0;
            height: 2px;
            background: var(--accent-gradient);
            transition: var(--transition);
            transform: translateY(-50%);
        }

        .mega-menu-item:hover {
            color: var(--pink-primary);
            padding-left: 16px;
            border-color: var(--pink-primary);
        }

        .mega-menu-item:hover::before {
            width: 12px;
        }

        /* Ultra Modern Footer */
        .footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white;
            position: relative;
            overflow: hidden;
            margin-top: 100px;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--accent-gradient);
        }

        .footer-content {
            max-width: 1440px;
            margin: 0 auto;
            padding: 100px 32px 50px;
            position: relative;
            z-index: 2;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 80px;
            margin-bottom: 80px;
        }

        .footer-section h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 32px;
            background: var(--luxury-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-section p, .footer-section li {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 16px;
            line-height: 1.7;
            font-size: 15px;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: inline-block;
            position: relative;
            padding: 4px 0;
        }

        .footer-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-gradient);
            transition: var(--transition);
        }

        .footer-link:hover {
            color: white;
            transform: translateX(6px);
        }

        .footer-link:hover::after {
            width: 100%;
        }

        /* Ultra Modern Social Links with Flux Icons */
        .social-links {
            display: flex;
            gap: 20px;
            margin-top: 32px;
        }

        .social-link {
            width: 56px;
            height: 56px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .social-link:hover {
            background: var(--glass-primary);
            transform: translateY(-6px) rotate(5deg);
            box-shadow: 0 15px 40px rgba(240, 147, 251, 0.3);
            border-color: var(--pink-primary);
        }

        .social-link svg {
            width: 24px;
            height: 24px;
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .header-main {
                grid-template-columns: auto 1fr auto;
                gap: 24px;
                padding: 20px 24px;
            }

            .search-section {
                order: 3;
                grid-column: 1 / -1;
                margin-top: 20px;
            }

            .main-nav {
                gap: 40px;
            }
        }

        @media (max-width: 768px) {
            .header-top {
                display: none;
            }

            .header-main {
                padding: 16px 20px;
                grid-template-columns: auto 1fr auto;
                gap: 16px;
            }

            .nav-bottom {
                display: none;
            }

            .nav-icons {
                gap: 16px;
            }

            .user-button span {
                display: none;
            }

            .logo {
                font-size: 28px;
            }

            .footer-grid {
                gap: 50px;
            }
        }

        /* Main Content */
        main {
            min-height: 60vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        /* Premium animations and effects */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.6);
            border: 2px solid rgba(240, 147, 251, 0.15);
            cursor: pointer;
            padding: 14px;
            border-radius: 20px;
            transition: var(--transition);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        .mobile-toggle:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            border-color: var(--pink-primary);
        }

        .mobile-toggle svg {
            width: 26px;
            height: 26px;
            color: var(--text-primary);
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-gradient);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-gradient);
        }
    </style>
</head>

<body style="font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased;">
<div style="min-height: 100vh;">
    <!-- Ultra Modern Announcement Bar -->
    @if (!($isAuthPage ?? false) && config('announcements.messages') && count(config('announcements.messages')) > 0)
        <div class="announcement-bar" id="announcementBar">
            <div class="announcement-content">
                @foreach (config('announcements.messages') as $message)
                    <div class="announcement-item">
                        <div class="announcement-icon">✨</div>
                        <span>{{ $message }}</span>
                    </div>
                @endforeach
            </div>
            <button class="close-announcement" onclick="closeAnnouncement()">×</button>
        </div>
    @endif

    <!-- Ultra Modern Header -->
    <header class="header" id="header">
        <!-- Header Top -->
        <div class="header-top">
            <div class="header-top-content">
                <div class="header-top-links">
                    <a href="#">Free Shipping Over $75</a>
                    <a href="#">Size Guide</a>
                    <a href="#">Customer Care</a>
                    <a href="#">Track Order</a>
                </div>
                <div class="header-top-links">
                    <a href="#">Returns & Exchanges</a>
                    <a href="#">Gift Cards</a>
                </div>
            </div>
        </div>

        <!-- Header Main -->
        <div class="header-main">
            <!-- Mobile Toggle -->
            <button class="mobile-toggle" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Logo Section -->
            <div class="logo-section">
                <a href="{{ route('home') }}" class="logo" wire:navigate>
                    @if(file_exists(public_path('images/logo/SWC 2.png')))
                        <img src="{{ asset('images/logo/SWC 2.png') }}" alt="{{ config('app.name', 'ShopWithCarl') }}">
                    @else
                        ShopWithCarl
                    @endif
                </a>
            </div>

            <!-- Ultra Modern Search -->
            <div class="search-section" role="search">
                <form class="search-container" action="{{ route('shop.search') }}" method="GET"
                      onsubmit="return submitHeaderSearch(event)">
                    <input type="text" class="search-input" name="q" id="headerSearchInput"
                           placeholder="Discover luxury lingerie, shapewear & activewear..."
                           autocomplete="off"
                           aria-label="Search products"
                           onkeyup="showSearchSuggestions(this.value)">
                    <button type="submit" class="search-btn" aria-label="Search">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </button>
                </form>
                <div id="headerSearchSuggestions" class="search-suggestions" role="listbox"
                     aria-label="Search suggestions" style="display:none;"></div>
            </div>

            <!-- Ultra Modern Navigation Icons -->
            <div class="nav-icons">
                <!-- User Account -->
                <div class="user-dropdown">
                    @auth
                        <div class="dropdown">
                            <button class="user-button dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <span>{{ auth()->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(auth()->user()->isAdmin() || auth()->user()->isDeveloper())
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                           href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2" style="width: 16px;"></i>
                                            Admin Dashboard
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                       href="{{ route('account.dashboard') }}" wire:navigate>
                                        <i class="bi bi-house" style="width: 16px;"></i>
                                        My Account
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                       href="{{ route('orders.index') }}" wire:navigate>
                                        <i class="bi bi-bag" style="width: 16px;"></i>
                                        My Orders
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2"
                                       href="{{ route('account.page', ['section' => 'details']) }}" wire:navigate>
                                        <i class="bi bi-person" style="width: 16px;"></i>
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit"
                                                class="dropdown-item d-flex align-items-center gap-2 text-danger border-0 bg-transparent w-100 text-start">
                                            <i class="bi bi-box-arrow-right" style="width: 16px;"></i>
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="user-button" wire:navigate>
                            <div class="user-avatar">AC</div>
                            <span>Account</span>
                        </a>
                    @endguest
                </div>

                @if (!isset($isAuthPage) || !$isAuthPage)
                    <!-- Wishlist -->
                    <a href="{{ route('account.page', ['section' => 'wishlist']) }}"
                       class="nav-icon {{ (($wishlistCount ?? (auth()->check() ? auth()->user()->wishlist()->count() : 0)) > 0) ? 'active' : '' }}"
                       title="Wishlist" wire:navigate>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="count-badge">
                            {{ $wishlistCount ?? (auth()->check() ? auth()->user()->wishlist()->count() : 0) }}
                        </span>
                    </a>

                    @livewire('components.cart-icon')

                @endif
            </div>
        </div>

        <!-- Ultra Modern Navigation -->
        <div class="nav-bottom">
            <div class="nav-container">
                <nav class="main-nav">
                    <div class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link" wire:navigate>Home</a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('shop.index') }}" class="nav-link" wire:navigate>Shop</a>
                    </div>
{{--                    <div class="nav-item">--}}
{{--                        <a href="#" class="nav-link">Categories</a>--}}
{{--                        @include('components.nav.mega-menu')--}}
{{--                    </div>--}}

                    @php
                        use App\Models\Category;
                        $mainCategories = Category::whereNull('parent_id')->active()->orderBy('sort_order')->get();
                    @endphp

                    @foreach($mainCategories as $category)
                        @php
                            $subcategories = $category->children()->active()->orderBy('sort_order')->get();
                        @endphp

                        <div class="nav-item">
                            @if($subcategories->count() > 0)
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">{{ $category->name }}</a>
                                <div class="dropdown-menu">
                                    @foreach($subcategories as $subcategory)
                                        <a href="{{ route('products.category', $subcategory->slug) }}"
                                           class="dropdown-item"
                                           wire:navigate>
                                            {{ $subcategory->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <a href="{{ route('products.category', $category->slug) }}"
                                   class="nav-link"
                                   wire:navigate>{{ $category->name }}</a>
                            @endif
                        </div>
                    @endforeach

                    <div class="nav-item">
                        <a href="{{ route('pages.contact') }}" class="nav-link" wire:navigate>Contact Us</a>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    @if (!isset($isAuthPage) || !$isAuthPage)
        <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="menu-list" style="list-style: none; padding: 0;">
                    <li>
                        <a href="{{ route('home') }}" class="menu-link-text"
                           style="display: block; padding: 16px 0; color: var(--text-primary); text-decoration: none; font-weight: 500; border-bottom: 1px solid rgba(0, 0, 0, 0.05); transition: var(--transition);"
                           wire:navigate>Home</a>
                    </li>
                    <li>
                        <a href="{{ route('shop.index') }}" class="menu-link-text"
                           style="display: block; padding: 16px 0; color: var(--text-primary); text-decoration: none; font-weight: 500; border-bottom: 1px solid rgba(0, 0, 0, 0.05); transition: var(--transition);"
                           wire:navigate>Shop</a>
                    </li>
                    <li>
                        <a href="{{ route('pages.contact') }}" class="menu-link-text"
                           style="display: block; padding: 16px 0; color: var(--text-primary); text-decoration: none; font-weight: 500; border-bottom: 1px solid rgba(0, 0, 0, 0.05); transition: var(--transition);"
                           wire:navigate>Contact Us</a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('home') }}" class="menu-link-text"
                               style="display: block; padding: 16px 0; color: var(--text-primary); text-decoration: none; font-weight: 500; border-bottom: 1px solid rgba(0, 0, 0, 0.05); transition: var(--transition);"
                               wire:navigate>Dashboard</a>
                        </li>
                        <li>
                            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <a href="{{ route('logout') }}" class="menu-link-text"
                                   style="display: block; padding: 16px 0; color: var(--text-primary); text-decoration: none; font-weight: 500; border-bottom: 1px solid rgba(0, 0, 0, 0.05); transition: var(--transition);"
                                   wire:navigate
                                   onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
                            </form>
                        </li>
                    @endauth
                    @guest
                        <li>
                            <a href="{{ route('login') }}" class="menu-link-text"
                               style="display: block; padding: 16px 0; color: var(--text-primary); text-decoration: none; font-weight: 500; border-bottom: 1px solid rgba(0, 0, 0, 0.05); transition: var(--transition);"
                               wire:navigate>Login</a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="menu-link-text"
                               style="display: block; padding: 16px 0; color: var(--text-primary); text-decoration: none; font-weight: 500; border-bottom: 1px solid rgba(0, 0, 0, 0.05); transition: var(--transition);"
                               wire:navigate>Register</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
             style="padding: 16px 20px; border-radius: 12px; margin: 16px 24px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(16, 185, 129, 0.1); color: #065f46; border-color: rgba(16, 185, 129, 0.2);">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
             style="padding: 16px 20px; border-radius: 12px; margin: 16px 24px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); background: rgba(239, 68, 68, 0.1); color: #7f1d1d; border-color: rgba(239, 68, 68, 0.2);">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        {!! $slot !!}
    </main>

    <!-- Global Cart Drawer -->
    <livewire:client.cart.cart-drawer/>

    <!-- Ultra Modern Footer with Working Flux Icons -->
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

                        <!-- Ultra Modern Social Links using Project Icon Font (Icomoon) -->
                        <div class="social-links">
                            @if(config('contact.socials'))
                                {{-- Inside the social links foreach --}}
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
                                <!-- Default social links with icon font -->
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
                            <li><a href="{{ route('blog.index') }}" class="footer-link" wire:navigate>Blog</a></li>
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
                                   style="flex: 1; padding: 16px 20px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); color: white; font-size: 14px; outline: none; transition: var(--transition);">
                            <button type="submit" class="newsletter-button"
                                    style="padding: 16px 24px; background: var(--accent-gradient); border: none; border-radius: 16px; color: white; font-weight: 600; cursor: pointer; transition: var(--transition);">
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
                        © {{ date('Y') }} {{ config('app.name', 'ShopWithCarl') }}. All rights reserved. Designed with
                        💖 for confident women everywhere.
                    </p>

                    <!-- Payment Methods -->
                    <div class="payment-methods"
                         style="display: flex; justify-content: center; gap: 16px; margin-top: 24px; flex-wrap: wrap;">
                        <div class="payment-method" title="MTN Mobile Money"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition); font-size: 10px; font-weight: bold; color: #FFD700;">
                            MTN
                        </div>
                        <div class="payment-method" title="Airtel Money"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition); font-size: 10px; font-weight: bold; color: #FF0000;">
                            AIRTEL
                        </div>
                        <div class="payment-method" title="Flutterwave"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition); position: relative;">
                            <!-- Custom Flutterwave Icon -->
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z"
                                      fill="#FF6B35"/>
                                <circle cx="12" cy="12" r="3" fill="#1B365C"/>
                            </svg>
                        </div>
                        <div class="payment-method" title="Pesapal"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition); position: relative;">
                            <!-- Custom Pesapal Icon -->
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <rect x="3" y="6" width="18" height="12" rx="2" fill="#0056B3"/>
                                <path d="M7 10h10v4H7z" fill="#FF0000"/>
                                <circle cx="9" cy="12" r="1" fill="white"/>
                                <circle cx="15" cy="12" r="1" fill="white"/>
                            </svg>
                        </div>
                        <div class="payment-method" title="Bank Transfer"
                             style="width: 48px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); transition: var(--transition);">
                            <!-- Better Bank Transfer Icon -->
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2">
                                <path d="M3 21h18"/>
                                <path d="M5 21V7l8-4v18"/>
                                <path d="M19 21V11l-6-4"/>
                                <path d="M9 9v.01"/>
                                <path d="M9 12v.01"/>
                                <path d="M9 15v.01"/>
                                <path d="M9 18v.01"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    @endif
</div>

@livewireScripts
@fluxScripts
@stack('scripts')
<script>
    // Close announcement bar
    function closeAnnouncement() {
        const announcementBar = document.getElementById('announcementBar');
        if (announcementBar) {
            announcementBar.style.display = 'none';
        }
    }

    // Handle header scroll effect
    window.addEventListener('scroll', function () {
        const header = document.getElementById('header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Header search logic
    const shopSearchUrl = "{{ route('shop.search') }}";
    const productsIndexUrl = "{{ route('products.index') }}";

    function submitHeaderSearch(event) {
        // allow default submit unless query too short
        const input = document.getElementById('headerSearchInput');
        if (!input) return true;
        const q = (input.value || '').trim();
        if (q.length < 2) {
            // provide gentle hint and prevent empty/too short queries
            renderSearchSuggestions(q, true);
            event.preventDefault();
            return false;
        }
        hideSearchSuggestions();
        return true; // allow form submission
    }

    function showSearchSuggestions(value) {
        const q = (value || '').trim();
        if (q.length === 0) {
            hideSearchSuggestions();
            return;
        }
        renderSearchSuggestions(q, false);
    }

    function renderSearchSuggestions(q, tooShort) {
        const box = document.getElementById('headerSearchSuggestions');
        if (!box) return;

        if (tooShort) {
            box.innerHTML = `<div class="search-suggestion-empty">Type at least 2 characters to search</div>`;
            box.style.display = 'block';
            return;
        }

        const encoded = encodeURIComponent(q);
        const resultsLink = `${shopSearchUrl}?q=${encoded}`;
        const productsLink = `${productsIndexUrl}?q=${encoded}`;
        box.innerHTML = `
            <a class="search-suggestion-item" role="option" href="${resultsLink}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Search for "${q}" in shop</span>
            </a>
            <a class="search-suggestion-item" role="option" href="${productsLink}">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h3.28a1 1 0 01.948.684l.894 2.683A1 1 0 0010.08 7H19a1 1 0 01.98 1.197l-1.5 7.5A2 2 0 0116.52 17H8.28a2 2 0 01-1.897-1.316L4.34 8.947A1 1 0 003.38 8H3V4z"/>
                </svg>
                <span>View products matching "${q}"</span>
            </a>
        `;
        box.style.display = 'block';
    }

    function hideSearchSuggestions() {
        const box = document.getElementById('headerSearchSuggestions');
        if (box) box.style.display = 'none';
    }

    document.addEventListener('click', function (e) {
        const suggestions = document.getElementById('headerSearchSuggestions');
        const input = document.getElementById('headerSearchInput');
        if (!suggestions || !input) return;
        if (!suggestions.contains(e.target) && e.target !== input) {
            hideSearchSuggestions();
        }
    });

    // Handle newsletter form submission
    function handleNewsletter(event) {
        event.preventDefault();
        const form = event.target;
        const email = form.querySelector('input[name="email"]').value;
        // Add your newsletter subscription logic here
        alert('Thank you for subscribing!');
        form.reset();
    }

    // Add hover effects for payment methods
    document.addEventListener('DOMContentLoaded', function () {
        const paymentMethods = document.querySelectorAll('.payment-method');
        paymentMethods.forEach(method => {
            method.addEventListener('mouseenter', function () {
                this.style.background = 'rgba(255, 255, 255, 0.2)';
                this.style.transform = 'translateY(-2px)';
            });
            method.addEventListener('mouseleave', function () {
                this.style.background = 'rgba(255, 255, 255, 0.1)';
                this.style.transform = 'translateY(0)';
            });
        });
    });
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
        // Listener for opening a Bootstrap modal
        Livewire.on('open-bs-modal', ({ id }) => {
            $(id).modal('show');
        });

        // Listener for closing a Bootstrap modal
        Livewire.on('close-bs-modal', ({ id }) => {
            $(id).modal('hide');
        });

        // When the cart modal is hidden by any means (ESC, backdrop click, etc.),
        // notify the Livewire component so it can update its state.
        $('#shoppingCart').on('hidden.bs.modal', function () {
            if (Livewire.getByName('client.cart.cart-drawer').length > 0) {
                Livewire.dispatch('cart-drawer-was-closed');
            }
        });
    });
</script>

</body>
</html>
