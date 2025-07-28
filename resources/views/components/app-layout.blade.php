{{--<!DOCTYPE html>--}}
{{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
{{--<head>--}}
{{--    <meta charset="utf-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1">--}}
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
{{--    <title>{{ $title ?? config('app.name', 'ShopWithCarl') }}</title>--}}
{{--    <link rel="preconnect" href="https://fonts.bunny.net">--}}
{{--    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|poppins:300,400,500,600,700&display=swap"--}}
{{--          rel="stylesheet"/>--}}
{{--    @vite(['resources/css/app.css', 'resources/js/app.js'])--}}
{{--    @livewireStyles--}}
{{--    @fluxAppearance--}}
{{--    <style>--}}
{{--        * {--}}
{{--            margin: 0;--}}
{{--            padding: 0;--}}
{{--            box-sizing: border-box;--}}
{{--        }--}}

{{--        :root {--}}
{{--            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);--}}
{{--            --accent-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);--}}
{{--            --neutral-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);--}}
{{--            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);--}}
{{--            --glass-bg: rgba(255, 255, 255, 0.25);--}}
{{--            --glass-border: rgba(255, 255, 255, 0.18);--}}
{{--            --shadow-subtle: 0 8px 32px rgba(31, 38, 135, 0.37);--}}
{{--            --shadow-strong: 0 20px 60px rgba(0, 0, 0, 0.1);--}}
{{--            --text-primary: #2d3748;--}}
{{--            --text-secondary: #718096;--}}
{{--            --pink-primary: #e91e63;--}}
{{--            --pink-light: #f8bbd9;--}}
{{--            --purple-primary: #9c27b0;--}}
{{--            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);--}}
{{--        }--}}

{{--        body {--}}
{{--            font-family: 'Inter', sans-serif;--}}
{{--            line-height: 1.6;--}}
{{--            color: var(--text-primary);--}}
{{--            background: #fafafa;--}}
{{--        }--}}

{{--        /* Floating Particles Animation */--}}
{{--        .floating-particles {--}}
{{--            position: absolute;--}}
{{--            width: 100%;--}}
{{--            height: 100%;--}}
{{--            overflow: hidden;--}}
{{--            pointer-events: none;--}}
{{--        }--}}

{{--        .particle {--}}
{{--            position: absolute;--}}
{{--            background: var(--accent-gradient);--}}
{{--            border-radius: 50%;--}}
{{--            opacity: 0.1;--}}
{{--            animation: float 6s ease-in-out infinite;--}}
{{--        }--}}

{{--        .particle:nth-child(1) {--}}
{{--            width: 4px;--}}
{{--            height: 4px;--}}
{{--            left: 10%;--}}
{{--            animation-delay: 0s;--}}
{{--        }--}}

{{--        .particle:nth-child(2) {--}}
{{--            width: 6px;--}}
{{--            height: 6px;--}}
{{--            left: 20%;--}}
{{--            animation-delay: 1s;--}}
{{--        }--}}

{{--        .particle:nth-child(3) {--}}
{{--            width: 3px;--}}
{{--            height: 3px;--}}
{{--            left: 30%;--}}
{{--            animation-delay: 2s;--}}
{{--        }--}}

{{--        .particle:nth-child(4) {--}}
{{--            width: 5px;--}}
{{--            height: 5px;--}}
{{--            left: 60%;--}}
{{--            animation-delay: 1.5s;--}}
{{--        }--}}

{{--        .particle:nth-child(5) {--}}
{{--            width: 4px;--}}
{{--            height: 4px;--}}
{{--            left: 80%;--}}
{{--            animation-delay: 0.5s;--}}
{{--        }--}}

{{--        @keyframes float {--}}
{{--            0%, 100% {--}}
{{--                transform: translateY(0px) rotate(0deg);--}}
{{--            }--}}
{{--            50% {--}}
{{--                transform: translateY(-20px) rotate(180deg);--}}
{{--            }--}}
{{--        }--}}

{{--        /* Announcement Bar */--}}
{{--        .announcement-bar {--}}
{{--            background: var(--primary-gradient);--}}
{{--            color: white;--}}
{{--            padding: 12px 0;--}}
{{--            position: relative;--}}
{{--            overflow: hidden;--}}
{{--            backdrop-filter: blur(10px);--}}
{{--        }--}}

{{--        .announcement-content {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            gap: 20px;--}}
{{--            animation: slideAnnouncement 20s linear infinite;--}}
{{--        }--}}

{{--        .announcement-item {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            gap: 8px;--}}
{{--            white-space: nowrap;--}}
{{--            font-weight: 500;--}}
{{--            font-size: 14px;--}}
{{--        }--}}

{{--        .announcement-icon {--}}
{{--            width: 16px;--}}
{{--            height: 16px;--}}
{{--            background: rgba(255, 255, 255, 0.2);--}}
{{--            border-radius: 50%;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--        }--}}

{{--        .close-announcement {--}}
{{--            position: absolute;--}}
{{--            right: 20px;--}}
{{--            top: 50%;--}}
{{--            transform: translateY(-50%);--}}
{{--            background: rgba(255, 255, 255, 0.2);--}}
{{--            border: none;--}}
{{--            color: white;--}}
{{--            width: 32px;--}}
{{--            height: 32px;--}}
{{--            border-radius: 50%;--}}
{{--            cursor: pointer;--}}
{{--            transition: var(--transition);--}}
{{--            backdrop-filter: blur(10px);--}}
{{--        }--}}

{{--        .close-announcement:hover {--}}
{{--            background: rgba(255, 255, 255, 0.3);--}}
{{--            transform: translateY(-50%) rotate(90deg);--}}
{{--        }--}}

{{--        @keyframes slideAnnouncement {--}}
{{--            0% {--}}
{{--                transform: translateX(100%);--}}
{{--            }--}}
{{--            100% {--}}
{{--                transform: translateX(-100%);--}}
{{--            }--}}
{{--        }--}}

{{--        /* Header */--}}
{{--        .header {--}}
{{--            background: rgba(255, 255, 255, 0.95);--}}
{{--            backdrop-filter: blur(20px);--}}
{{--            border-bottom: 1px solid rgba(255, 255, 255, 0.2);--}}
{{--            position: sticky;--}}
{{--            top: 0;--}}
{{--            z-index: 1000;--}}
{{--            transition: var(--transition);--}}
{{--        }--}}

{{--        .header.scrolled {--}}
{{--            background: rgba(255, 255, 255, 0.98);--}}
{{--            box-shadow: var(--shadow-subtle);--}}
{{--        }--}}

{{--        .header-main {--}}
{{--            max-width: 1400px;--}}
{{--            margin: 0 auto;--}}
{{--            padding: 20px 24px;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: space-between;--}}
{{--        }--}}

{{--        .mobile-toggle {--}}
{{--            display: none;--}}
{{--            background: none;--}}
{{--            border: none;--}}
{{--            cursor: pointer;--}}
{{--            padding: 8px;--}}
{{--            border-radius: 12px;--}}
{{--            transition: var(--transition);--}}
{{--        }--}}

{{--        .mobile-toggle:hover {--}}
{{--            background: rgba(0, 0, 0, 0.05);--}}
{{--        }--}}

{{--        .logo {--}}
{{--            font-family: 'Poppins', sans-serif;--}}
{{--            font-size: 28px;--}}
{{--            font-weight: 700;--}}
{{--            background: var(--accent-gradient);--}}
{{--            -webkit-background-clip: text;--}}
{{--            -webkit-text-fill-color: transparent;--}}
{{--            background-clip: text;--}}
{{--            text-decoration: none;--}}
{{--            transition: var(--transition);--}}
{{--        }--}}

{{--        .logo:hover {--}}
{{--            transform: scale(1.05);--}}
{{--        }--}}

{{--        .logo img {--}}
{{--            height: 40px;--}}
{{--            width: auto;--}}
{{--        }--}}

{{--        .nav-icons {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            gap: 24px;--}}
{{--        }--}}

{{--        .nav-icon {--}}
{{--            position: relative;--}}
{{--            padding: 12px;--}}
{{--            border-radius: 16px;--}}
{{--            background: rgba(255, 255, 255, 0.8);--}}
{{--            backdrop-filter: blur(10px);--}}
{{--            border: 1px solid rgba(255, 255, 255, 0.2);--}}
{{--            color: var(--text-primary);--}}
{{--            transition: var(--transition);--}}
{{--            cursor: pointer;--}}
{{--            text-decoration: none;--}}
{{--        }--}}

{{--        .nav-icon:hover {--}}
{{--            background: var(--glass-bg);--}}
{{--            transform: translateY(-2px);--}}
{{--            box-shadow: var(--shadow-subtle);--}}
{{--            color: var(--pink-primary);--}}
{{--        }--}}

{{--        .nav-icon svg {--}}
{{--            width: 20px;--}}
{{--            height: 20px;--}}
{{--        }--}}

{{--        .count-badge {--}}
{{--            position: absolute;--}}
{{--            top: -6px;--}}
{{--            right: -6px;--}}
{{--            background: var(--accent-gradient);--}}
{{--            color: white;--}}
{{--            border-radius: 50%;--}}
{{--            width: 20px;--}}
{{--            height: 20px;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            font-size: 11px;--}}
{{--            font-weight: 600;--}}
{{--            border: 2px solid white;--}}
{{--        }--}}

{{--        .user-dropdown {--}}
{{--            position: relative;--}}
{{--        }--}}

{{--        .user-button {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            gap: 8px;--}}
{{--            padding: 12px 16px;--}}
{{--            border-radius: 16px;--}}
{{--            background: var(--glass-bg);--}}
{{--            backdrop-filter: blur(10px);--}}
{{--            border: 1px solid var(--glass-border);--}}
{{--            color: var(--text-primary);--}}
{{--            cursor: pointer;--}}
{{--            transition: var(--transition);--}}
{{--            text-decoration: none;--}}
{{--        }--}}

{{--        .user-button:hover {--}}
{{--            background: rgba(255, 255, 255, 0.4);--}}
{{--            transform: translateY(-2px);--}}
{{--        }--}}

{{--        /* Navigation */--}}
{{--        .nav-bottom {--}}
{{--            background: rgba(255, 255, 255, 0.9);--}}
{{--            backdrop-filter: blur(20px);--}}
{{--            border-top: 1px solid rgba(0, 0, 0, 0.05);--}}
{{--            padding: 0 24px;--}}
{{--        }--}}

{{--        .nav-container {--}}
{{--            max-width: 1400px;--}}
{{--            margin: 0 auto;--}}
{{--            display: flex;--}}
{{--            justify-content: center;--}}
{{--        }--}}

{{--        .main-nav {--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            gap: 48px;--}}
{{--            padding: 20px 0;--}}
{{--        }--}}

{{--        .nav-item {--}}
{{--            position: relative;--}}
{{--        }--}}

{{--        .nav-link {--}}
{{--            font-weight: 500;--}}
{{--            color: var(--text-primary);--}}
{{--            text-decoration: none;--}}
{{--            padding: 12px 0;--}}
{{--            transition: var(--transition);--}}
{{--            position: relative;--}}
{{--        }--}}

{{--        .nav-link::after {--}}
{{--            content: '';--}}
{{--            position: absolute;--}}
{{--            bottom: -4px;--}}
{{--            left: 50%;--}}
{{--            width: 0;--}}
{{--            height: 3px;--}}
{{--            background: var(--accent-gradient);--}}
{{--            border-radius: 2px;--}}
{{--            transition: var(--transition);--}}
{{--            transform: translateX(-50%);--}}
{{--        }--}}

{{--        .nav-link:hover::after {--}}
{{--            width: 100%;--}}
{{--        }--}}

{{--        .nav-link:hover {--}}
{{--            color: var(--pink-primary);--}}
{{--            transform: translateY(-2px);--}}
{{--        }--}}

{{--        .dropdown-menu {--}}
{{--            position: absolute;--}}
{{--            top: 100%;--}}
{{--            left: 50%;--}}
{{--            transform: translateX(-50%);--}}
{{--            background: rgba(255, 255, 255, 0.95);--}}
{{--            backdrop-filter: blur(20px);--}}
{{--            border-radius: 20px;--}}
{{--            box-shadow: var(--shadow-strong);--}}
{{--            border: 1px solid rgba(255, 255, 255, 0.2);--}}
{{--            min-width: 240px;--}}
{{--            opacity: 0;--}}
{{--            visibility: hidden;--}}
{{--            transition: var(--transition);--}}
{{--            z-index: 1000;--}}
{{--            margin-top: 16px;--}}
{{--        }--}}

{{--        .nav-item:hover .dropdown-menu {--}}
{{--            opacity: 1;--}}
{{--            visibility: visible;--}}
{{--            transform: translateX(-50%) translateY(0);--}}
{{--        }--}}

{{--        .dropdown-item {--}}
{{--            display: block;--}}
{{--            padding: 16px 24px;--}}
{{--            color: var(--text-primary);--}}
{{--            text-decoration: none;--}}
{{--            transition: var(--transition);--}}
{{--            border-radius: 16px;--}}
{{--            margin: 8px;--}}
{{--        }--}}

{{--        .dropdown-item:hover {--}}
{{--            background: var(--glass-bg);--}}
{{--            color: var(--pink-primary);--}}
{{--            transform: translateX(8px);--}}
{{--        }--}}

{{--        /* Footer */--}}
{{--        .footer {--}}
{{--            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);--}}
{{--            color: white;--}}
{{--            position: relative;--}}
{{--            overflow: hidden;--}}
{{--            margin-top: 80px;--}}
{{--        }--}}

{{--        .footer::before {--}}
{{--            content: '';--}}
{{--            position: absolute;--}}
{{--            top: 0;--}}
{{--            left: 0;--}}
{{--            right: 0;--}}
{{--            height: 1px;--}}
{{--            background: var(--accent-gradient);--}}
{{--        }--}}

{{--        .footer-content {--}}
{{--            max-width: 1400px;--}}
{{--            margin: 0 auto;--}}
{{--            padding: 80px 24px 40px;--}}
{{--            position: relative;--}}
{{--            z-index: 2;--}}
{{--        }--}}

{{--        .footer-grid {--}}
{{--            display: grid;--}}
{{--            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));--}}
{{--            gap: 60px;--}}
{{--            margin-bottom: 60px;--}}
{{--        }--}}

{{--        .footer-section h3 {--}}
{{--            font-family: 'Poppins', sans-serif;--}}
{{--            font-size: 24px;--}}
{{--            font-weight: 600;--}}
{{--            margin-bottom: 24px;--}}
{{--            background: var(--neutral-gradient);--}}
{{--            -webkit-background-clip: text;--}}
{{--            -webkit-text-fill-color: transparent;--}}
{{--            background-clip: text;--}}
{{--        }--}}

{{--        .footer-section p, .footer-section li {--}}
{{--            color: rgba(255, 255, 255, 0.8);--}}
{{--            margin-bottom: 12px;--}}
{{--            line-height: 1.7;--}}
{{--        }--}}

{{--        .footer-link {--}}
{{--            color: rgba(255, 255, 255, 0.8);--}}
{{--            text-decoration: none;--}}
{{--            transition: var(--transition);--}}
{{--            display: inline-block;--}}
{{--            position: relative;--}}
{{--        }--}}

{{--        .footer-link::after {--}}
{{--            content: '';--}}
{{--            position: absolute;--}}
{{--            bottom: -2px;--}}
{{--            left: 0;--}}
{{--            width: 0;--}}
{{--            height: 2px;--}}
{{--            background: var(--accent-gradient);--}}
{{--            transition: var(--transition);--}}
{{--        }--}}

{{--        .footer-link:hover {--}}
{{--            color: white;--}}
{{--            transform: translateX(4px);--}}
{{--        }--}}

{{--        .footer-link:hover::after {--}}
{{--            width: 100%;--}}
{{--        }--}}

{{--        .social-links {--}}
{{--            display: flex;--}}
{{--            gap: 16px;--}}
{{--            margin-top: 24px;--}}
{{--        }--}}

{{--        .social-link {--}}
{{--            width: 48px;--}}
{{--            height: 48px;--}}
{{--            border-radius: 16px;--}}
{{--            background: rgba(255, 255, 255, 0.1);--}}
{{--            backdrop-filter: blur(10px);--}}
{{--            border: 1px solid rgba(255, 255, 255, 0.2);--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            color: white;--}}
{{--            text-decoration: none;--}}
{{--            transition: var(--transition);--}}
{{--        }--}}

{{--        .social-link:hover {--}}
{{--            background: var(--glass-bg);--}}
{{--            transform: translateY(-4px) rotate(5deg);--}}
{{--            box-shadow: var(--shadow-subtle);--}}
{{--        }--}}

{{--        .newsletter-form {--}}
{{--            display: flex;--}}
{{--            gap: 12px;--}}
{{--            margin-top: 24px;--}}
{{--        }--}}

{{--        .newsletter-input {--}}
{{--            flex: 1;--}}
{{--            padding: 16px 20px;--}}
{{--            border: 1px solid rgba(255, 255, 255, 0.2);--}}
{{--            border-radius: 16px;--}}
{{--            background: rgba(255, 255, 255, 0.1);--}}
{{--            backdrop-filter: blur(10px);--}}
{{--            color: white;--}}
{{--            font-size: 14px;--}}
{{--            outline: none;--}}
{{--            transition: var(--transition);--}}
{{--        }--}}

{{--        .newsletter-input::placeholder {--}}
{{--            color: rgba(255, 255, 255, 0.6);--}}
{{--        }--}}

{{--        .newsletter-input:focus {--}}
{{--            border-color: var(--pink-primary);--}}
{{--            background: rgba(255, 255, 255, 0.15);--}}
{{--        }--}}

{{--        .newsletter-button {--}}
{{--            padding: 16px 24px;--}}
{{--            background: var(--accent-gradient);--}}
{{--            border: none;--}}
{{--            border-radius: 16px;--}}
{{--            color: white;--}}
{{--            font-weight: 600;--}}
{{--            cursor: pointer;--}}
{{--            transition: var(--transition);--}}
{{--        }--}}

{{--        .newsletter-button:hover {--}}
{{--            transform: translateY(-2px);--}}
{{--            box-shadow: var(--shadow-subtle);--}}
{{--        }--}}

{{--        .footer-bottom {--}}
{{--            border-top: 1px solid rgba(255, 255, 255, 0.1);--}}
{{--            padding-top: 40px;--}}
{{--            text-align: center;--}}
{{--        }--}}

{{--        .payment-methods {--}}
{{--            display: flex;--}}
{{--            justify-content: center;--}}
{{--            gap: 16px;--}}
{{--            margin-top: 24px;--}}
{{--            flex-wrap: wrap;--}}
{{--        }--}}

{{--        .payment-method {--}}
{{--            width: 48px;--}}
{{--            height: 32px;--}}
{{--            background: rgba(255, 255, 255, 0.1);--}}
{{--            border-radius: 8px;--}}
{{--            display: flex;--}}
{{--            align-items: center;--}}
{{--            justify-content: center;--}}
{{--            backdrop-filter: blur(10px);--}}
{{--            border: 1px solid rgba(255, 255, 255, 0.2);--}}
{{--            transition: var(--transition);--}}
{{--        }--}}

{{--        .payment-method:hover {--}}
{{--            background: rgba(255, 255, 255, 0.2);--}}
{{--            transform: translateY(-2px);--}}
{{--        }--}}

{{--        .payment-method img {--}}
{{--            height: 20px;--}}
{{--            width: auto;--}}
{{--        }--}}

{{--        /* Mobile Styles */--}}
{{--        @media (max-width: 768px) {--}}
{{--            .mobile-toggle {--}}
{{--                display: block;--}}
{{--            }--}}

{{--            .nav-bottom {--}}
{{--                display: none;--}}
{{--            }--}}

{{--            .header-main {--}}
{{--                padding: 16px 20px;--}}
{{--            }--}}

{{--            .nav-icons {--}}
{{--                gap: 16px;--}}
{{--            }--}}

{{--            .nav-icon {--}}
{{--                padding: 10px;--}}
{{--            }--}}

{{--            .footer-content {--}}
{{--                padding: 60px 20px 40px;--}}
{{--            }--}}

{{--            .footer-grid {--}}
{{--                gap: 40px;--}}
{{--            }--}}

{{--            .newsletter-form {--}}
{{--                flex-direction: column;--}}
{{--            }--}}

{{--            .announcement-content {--}}
{{--                justify-content: flex-start;--}}
{{--                padding-left: 20px;--}}
{{--            }--}}
{{--        }--}}

{{--        /* Smooth scrolling */--}}
{{--        html {--}}
{{--            scroll-behavior: smooth;--}}
{{--        }--}}

{{--        /* Custom scrollbar */--}}
{{--        ::-webkit-scrollbar {--}}
{{--            width: 8px;--}}
{{--        }--}}

{{--        ::-webkit-scrollbar-track {--}}
{{--            background: #f1f1f1;--}}
{{--        }--}}

{{--        ::-webkit-scrollbar-thumb {--}}
{{--            background: var(--accent-gradient);--}}
{{--            border-radius: 4px;--}}
{{--        }--}}

{{--        ::-webkit-scrollbar-thumb:hover {--}}
{{--            background: var(--primary-gradient);--}}
{{--        }--}}

{{--        /* Flash Messages */--}}
{{--        .alert {--}}
{{--            padding: 16px 20px;--}}
{{--            border-radius: 12px;--}}
{{--            margin: 16px 24px;--}}
{{--            backdrop-filter: blur(10px);--}}
{{--            border: 1px solid rgba(255, 255, 255, 0.2);--}}
{{--        }--}}

{{--        .alert-success {--}}
{{--            background: rgba(16, 185, 129, 0.1);--}}
{{--            color: #065f46;--}}
{{--            border-color: rgba(16, 185, 129, 0.2);--}}
{{--        }--}}

{{--        .alert-danger {--}}
{{--            background: rgba(239, 68, 68, 0.1);--}}
{{--            color: #7f1d1d;--}}
{{--            border-color: rgba(239, 68, 68, 0.2);--}}
{{--        }--}}

{{--        /* Offcanvas Styles */--}}
{{--        .offcanvas {--}}
{{--            background: rgba(255, 255, 255, 0.95);--}}
{{--            backdrop-filter: blur(20px);--}}
{{--            border: none;--}}
{{--        }--}}

{{--        .offcanvas-header {--}}
{{--            border-bottom: 1px solid rgba(0, 0, 0, 0.1);--}}
{{--            padding: 24px;--}}
{{--        }--}}

{{--        .offcanvas-title {--}}
{{--            font-family: 'Poppins', sans-serif;--}}
{{--            font-weight: 600;--}}
{{--            background: var(--accent-gradient);--}}
{{--            -webkit-background-clip: text;--}}
{{--            -webkit-text-fill-color: transparent;--}}
{{--            background-clip: text;--}}
{{--        }--}}

{{--        .offcanvas-body {--}}
{{--            padding: 24px;--}}
{{--        }--}}

{{--        .offcanvas .menu-list {--}}
{{--            list-style: none;--}}
{{--            padding: 0;--}}
{{--        }--}}

{{--        .offcanvas .menu-link-text {--}}
{{--            display: block;--}}
{{--            padding: 16px 0;--}}
{{--            color: var(--text-primary);--}}
{{--            text-decoration: none;--}}
{{--            font-weight: 500;--}}
{{--            border-bottom: 1px solid rgba(0, 0, 0, 0.05);--}}
{{--            transition: var(--transition);--}}
{{--        }--}}

{{--        .offcanvas .menu-link-text:hover {--}}
{{--            color: var(--pink-primary);--}}
{{--            transform: translateX(8px);--}}
{{--        }--}}

{{--        /* Make main content area more elegant */--}}
{{--        main {--}}
{{--            min-height: 60vh;--}}
{{--            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body style="font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased;">--}}
{{--<div style="min-height: 100vh;">--}}
{{--    <!-- Announcement Bar -->--}}
{{--    @if (!($isAuthPage ?? false) && config('announcements.messages') && count(config('announcements.messages')) > 0)--}}
{{--        <div class="announcement-bar" id="announcementBar">--}}
{{--            <div class="floating-particles">--}}
{{--                <div class="particle"></div>--}}
{{--                <div class="particle"></div>--}}
{{--                <div class="particle"></div>--}}
{{--                <div class="particle"></div>--}}
{{--                <div class="particle"></div>--}}
{{--            </div>--}}
{{--            <div class="announcement-content">--}}
{{--                @foreach (config('announcements.messages') as $message)--}}
{{--                    <div class="announcement-item">--}}
{{--                        <div class="announcement-icon">✨</div>--}}
{{--                        <span>{{ $message }}</span>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--            <button class="close-announcement" onclick="closeAnnouncement()">×</button>--}}
{{--        </div>--}}
{{--    @endif--}}

{{--    <!-- Header -->--}}
{{--    <header class="header" id="header">--}}
{{--        <div class="header-main">--}}
{{--            <!-- Mobile Menu Toggle -->--}}
{{--            <button class="mobile-toggle" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">--}}
{{--                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">--}}
{{--                    <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>--}}
{{--                </svg>--}}
{{--            </button>--}}

{{--            <!-- Logo -->--}}
{{--            <a href="{{ route('home') }}" class="logo" wire:navigate>--}}
{{--                @if(file_exists(public_path('images/logo/SWC 2.png')))--}}
{{--                    <img src="{{ asset('images/logo/SWC 2.png') }}" alt="{{ config('app.name', 'ShopWithCarl') }}">--}}
{{--                @else--}}
{{--                    ShopWithCarl--}}
{{--                @endif--}}
{{--            </a>--}}

{{--            <!-- Navigation Icons -->--}}
{{--            <div class="nav-icons">--}}
{{--                <a href="#searchModal" data-bs-toggle="modal" class="nav-icon" title="Search">--}}
{{--                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>--}}
{{--                    </svg>--}}
{{--                </a>--}}

{{--                <div class="user-dropdown">--}}
{{--                    @auth--}}
{{--                        <flux:dropdown>--}}
{{--                            <flux:button variant="ghost" class="user-button">--}}
{{--                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>--}}
{{--                                </svg>--}}
{{--                                <span>{{ auth()->user()->name }}</span>--}}
{{--                            </flux:button>--}}
{{--                            <flux:menu>--}}
{{--                                <flux:menu.item>--}}
{{--                                    <flux:link href="{{ route('user.dashboard') }}" wire:navigate>--}}
{{--                                        Dashboard--}}
{{--                                    </flux:link>--}}
{{--                                </flux:menu.item>--}}
{{--                                <flux:menu.item>--}}
{{--                                    <flux:link href="{{ route('user.orders') }}" wire:navigate>Orders</flux:link>--}}
{{--                                </flux:menu.item>--}}
{{--                                <flux:menu.item>--}}
{{--                                    <flux:link href="{{ route('user.wishlist') }}" wire:navigate>Wishlist</flux:link>--}}
{{--                                </flux:menu.item>--}}
{{--                                <flux:menu.item>--}}
{{--                                    <flux:link href="{{ route('user.addresses') }}" wire:navigate>Addresses</flux:link>--}}
{{--                                </flux:menu.item>--}}
{{--                                <flux:menu.item>--}}
{{--                                    <flux:link href="{{ route('profile.edit') }}" wire:navigate>Profile</flux:link>--}}
{{--                                </flux:menu.item>--}}
{{--                                <flux:menu.separator/>--}}
{{--                                <flux:menu.item>--}}
{{--                                    <form method="POST" action="{{ route('logout') }}">--}}
{{--                                        @csrf--}}
{{--                                        <flux:button type="submit" variant="ghost" class="w-full text-left">--}}
{{--                                            Logout--}}
{{--                                        </flux:button>--}}
{{--                                    </form>--}}
{{--                                </flux:menu.item>--}}
{{--                            </flux:menu>--}}
{{--                        </flux:dropdown>--}}
{{--                    @else--}}
{{--                        <a href="{{ route('login') }}" class="user-button" wire:navigate>--}}
{{--                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>--}}
{{--                            </svg>--}}
{{--                            <span>Account</span>--}}
{{--                        </a>--}}
{{--                    @endauth--}}
{{--                </div>--}}

{{--                @if (!isset($isAuthPage) || !$isAuthPage)--}}
{{--                    <a href="{{ route('user.wishlist') }}" class="nav-icon" title="Wishlist" wire:navigate>--}}
{{--                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>--}}
{{--                        </svg>--}}
{{--                        <span--}}
{{--                            class="count-badge">{{ $wishlistCount ?? (auth()->check() ? auth()->user()->wishlist()->count() : 0) }}</span>--}}
{{--                    </a>--}}

{{--                    <div class="nav-icon">--}}
{{--                        <livewire:header/>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <!-- Bottom Navigation -->--}}
{{--        <div class="nav-bottom">--}}
{{--            <div class="nav-container">--}}
{{--                <nav class="main-nav">--}}
{{--                    <div class="nav-item">--}}
{{--                        <a href="{{ route('home') }}" class="nav-link" wire:navigate>Home</a>--}}
{{--                    </div>--}}
{{--                    <div class="nav-item">--}}
{{--                        <a href="{{ route('shop.index') }}" class="nav-link" wire:navigate>Shop</a>--}}
{{--                    </div>--}}
{{--                    <div class="nav-item">--}}
{{--                        <a href="{{ route('products.index') }}" class="nav-link" wire:navigate>Products</a>--}}
{{--                    </div>--}}
{{--                    <div class="nav-item">--}}
{{--                        <a href="#" class="nav-link">Categories</a>--}}
{{--                        <div class="dropdown-menu">--}}
{{--                            @php--}}
{{--                                $categories = \App\Models\Category::active()->parent()->orderBy('sort_order')->take(8)->get();--}}
{{--                            @endphp--}}
{{--                            @foreach($categories as $category)--}}
{{--                                <a href="{{ route('category.show', $category->slug) }}" class="dropdown-item"--}}
{{--                                   wire:navigate>{{ $category->name }}</a>--}}
{{--                            @endforeach--}}
{{--                            <a href="{{ route('categories.index') }}" class="dropdown-item" wire:navigate>View All--}}
{{--                                Categories</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="nav-item">--}}
{{--                        <a href="#" class="nav-link">Pages</a>--}}
{{--                        <div class="dropdown-menu">--}}
{{--                            <a href="{{ route('pages.about-us') }}" class="dropdown-item" wire:navigate>About Us</a>--}}
{{--                            <a href="#" class="dropdown-item">Brands</a>--}}
{{--                            <a href="{{ route('pages.contact') }}" class="dropdown-item" wire:navigate>Contact</a>--}}
{{--                            <a href="#" class="dropdown-item">FAQ</a>--}}
{{--                            <a href="#" class="dropdown-item">Store Locator</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="nav-item">--}}
{{--                        <a href="{{ route('blog.index') }}" class="nav-link" wire:navigate>Blog</a>--}}
{{--                    </div>--}}
{{--                </nav>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </header>--}}

{{--    <!-- Mobile Menu -->--}}
{{--    @if (!isset($isAuthPage) || !$isAuthPage)--}}
{{--        <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">--}}
{{--            <div class="offcanvas-header">--}}
{{--                <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>--}}
{{--                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>--}}
{{--            </div>--}}
{{--            <div class="offcanvas-body">--}}
{{--                <ul class="menu-list">--}}
{{--                    <li>--}}
{{--                        <a href="{{ route('home') }}" class="menu-link-text" wire:navigate>Home</a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="{{ route('shop.index') }}" class="menu-link-text" wire:navigate>Shop</a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="{{ route('products.index') }}" class="menu-link-text" wire:navigate>Products</a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="{{ route('pages.about-us') }}" class="menu-link-text" wire:navigate>About us</a>--}}
{{--                    </li>--}}
{{--                    <li>--}}
{{--                        <a href="{{ route('blog.index') }}" class="menu-link-text" wire:navigate>Blog</a>--}}
{{--                    </li>--}}
{{--                    @auth--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('user.dashboard') }}" class="menu-link-text" wire:navigate>Dashboard</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST">--}}
{{--                                @csrf--}}
{{--                                <a href="{{ route('logout') }}" class="menu-link-text" wire:navigate--}}
{{--                                   onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>--}}
{{--                            </form>--}}
{{--                        </li>--}}
{{--                    @else--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('login') }}" class="menu-link-text" wire:navigate>Login</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('register') }}" class="menu-link-text" wire:navigate>Register</a>--}}
{{--                        </li>--}}
{{--                    @endauth--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}

{{--    <!-- Flash Messages -->--}}
{{--    @if (session()->has('success'))--}}
{{--        <div class="alert alert-success alert-dismissible fade show" role="alert">--}}
{{--            {{ session('success') }}--}}
{{--            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--    @if (session()->has('error'))--}}
{{--        <div class="alert alert-danger alert-dismissible fade show" role="alert">--}}
{{--            {{ session('error') }}--}}
{{--            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>--}}
{{--        </div>--}}
{{--    @endif--}}

{{--    <!-- Main Content -->--}}
{{--    <main>--}}
{{--        {{ $slot }}--}}
{{--    </main>--}}

{{--    <!-- Footer -->--}}
{{--    @if (!isset($isAuthPage) || !$isAuthPage)--}}
{{--        <footer class="footer">--}}
{{--            <div class="floating-particles">--}}
{{--                <div class="particle"></div>--}}
{{--                <div class="particle"></div>--}}
{{--                <div class="particle"></div>--}}
{{--                <div class="particle"></div>--}}
{{--                <div class="particle"></div>--}}
{{--            </div>--}}

{{--            <div class="footer-content">--}}
{{--                <div class="footer-grid">--}}
{{--                    <!-- Company Info -->--}}
{{--                    <div class="footer-section">--}}
{{--                        <h3>ShopWithCarl</h3>--}}
{{--                        <p>Your premier destination for luxury women's undergarments, activewear, and shapewear. We--}}
{{--                            believe every woman deserves to feel confident and beautiful.</p>--}}
{{--                        <p>--}}
{{--                            <strong>Address:</strong> {{ config('contact.address', '123 Fashion Avenue, Style District') }}--}}
{{--                        </p>--}}
{{--                        <p><strong>Email:</strong> <a--}}
{{--                                href="mailto:{{ config('contact.email', 'hello@shopwithcarl.com') }}"--}}
{{--                                class="footer-link">{{ config('contact.email', 'hello@shopwithcarl.com') }}</a></p>--}}
{{--                        <p><strong>Phone:</strong> <a href="tel:{{ config('contact.phone', '+1234567890') }}"--}}
{{--                                                      class="footer-link">{{ config('contact.phone', '+1 (234) 567-890') }}</a>--}}
{{--                        </p>--}}
{{--                        <a href="{{ route('pages.contact') }}" class="footer-link"--}}
{{--                           style="margin-top: 16px; display: inline-block;" wire:navigate>Get Directions →</a>--}}

{{--                        <div class="social-links">--}}
{{--                            @if(config('contact.socials'))--}}
{{--                                @foreach (config('contact.socials') as $platform => $url)--}}
{{--                                    <a href="{{ $url }}" class="social-link" title="{{ ucfirst($platform) }}">--}}
{{--                                        @if($platform === 'facebook')--}}
{{--                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                                                <path--}}
{{--                                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>--}}
{{--                                            </svg>--}}
{{--                                        @elseif($platform === 'instagram')--}}
{{--                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                                                <path--}}
{{--                                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.621 5.367 11.988 11.988 11.988s11.987-5.367 11.987-11.988C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.596-3.205-1.535l1.846-1.846c.39.586 1.054.977 1.808.977 1.297 0 2.345-1.048 2.345-2.345 0-.754-.391-1.418-.977-1.808l1.846-1.846c.939.757 1.535 1.908 1.535 3.205 0 2.247-1.831 4.078-4.078 4.078z"/>--}}
{{--                                            </svg>--}}
{{--                                        @elseif($platform === 'twitter')--}}
{{--                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                                                <path--}}
{{--                                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.986 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>--}}
{{--                                            </svg>--}}
{{--                                        @elseif($platform === 'pinterest')--}}
{{--                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                                                <path--}}
{{--                                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.165.120.285-.09.924-.297 1.199-.423 1.199-.314 0-1.022-.105-1.371-.394-.841-.696-1.364-1.893-1.364-3.185 0-3.896 2.85-7.466 8.207-7.466 4.312 0 7.662 3.075 7.662 7.174 0 4.288-2.703 7.737-6.453 7.737-1.259 0-2.447-.656-2.853-1.534 0 0-.622 2.378-.775 2.95-.281 1.05-1.03 2.369-1.532 3.177C9.505 23.788 10.748 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>--}}
{{--                                            </svg>--}}
{{--                                        @else--}}
{{--                                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                                                <circle cx="12" cy="12" r="10"/>--}}
{{--                                            </svg>--}}
{{--                                        @endif--}}
{{--                                    </a>--}}
{{--                                @endforeach--}}
{{--                            @else--}}
{{--                                <a href="#" class="social-link" title="Facebook">--}}
{{--                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                                        <path--}}
{{--                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>--}}
{{--                                    </svg>--}}
{{--                                </a>--}}
{{--                                <a href="#" class="social-link" title="Instagram">--}}
{{--                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                                        <path--}}
{{--                                            d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.621 5.367 11.988 11.988 11.988s11.987-5.367 11.987-11.988C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.596-3.205-1.535l1.846-1.846c.39.586 1.054.977 1.808.977 1.297 0 2.345-1.048 2.345-2.345 0-.754-.391-1.418-.977-1.808l1.846-1.846c.939.757 1.535 1.908 1.535 3.205 0 2.247-1.831 4.078-4.078 4.078z"/>--}}
{{--                                    </svg>--}}
{{--                                </a>--}}
{{--                                <a href="#" class="social-link" title="Twitter">--}}
{{--                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                                        <path--}}
{{--                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.986 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>--}}
{{--                                    </svg>--}}
{{--                                </a>--}}
{{--                                <a href="#" class="social-link" title="Pinterest">--}}
{{--                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">--}}
{{--                                        <path--}}
{{--                                            d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.165.120.285-.09.924-.297 1.199-.423 1.199-.314 0-1.022-.105-1.371-.394-.841-.696-1.364-1.893-1.364-3.185 0-3.896 2.85-7.466 8.207-7.466 4.312 0 7.662 3.075 7.662 7.174 0 4.288-2.703 7.737-6.453 7.737-1.259 0-2.447-.656-2.853-1.534 0 0-.622 2.378-.775 2.95-.281 1.05-1.03 2.369-1.532 3.177C9.505 23.788 10.748 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>--}}
{{--                                    </svg>--}}
{{--                                </a>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Quick Links -->--}}
{{--                    <div class="footer-section">--}}
{{--                        <h3>Quick Links</h3>--}}
{{--                        <ul style="list-style: none; padding: 0;">--}}
{{--                            <li><a href="{{ route('home') }}" class="footer-link" wire:navigate>Home</a></li>--}}
{{--                            <li><a href="{{ route('shop.index') }}" class="footer-link" wire:navigate>Shop All</a></li>--}}
{{--                            <li><a href="{{ route('products.index') }}" class="footer-link" wire:navigate>New--}}
{{--                                    Arrivals</a></li>--}}
{{--                            <li><a href="#" class="footer-link">Best Sellers</a></li>--}}
{{--                            <li><a href="#" class="footer-link">Sale Items</a></li>--}}
{{--                            <li><a href="#" class="footer-link">Size Guide</a></li>--}}
{{--                            <li><a href="{{ route('pages.about-us') }}" class="footer-link" wire:navigate>About Us</a>--}}
{{--                            </li>--}}
{{--                            <li><a href="{{ route('pages.contact') }}" class="footer-link" wire:navigate>Contact</a>--}}
{{--                            </li>--}}
{{--                            <li><a href="{{ route('blog.index') }}" class="footer-link" wire:navigate>Blog</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}

{{--                    <!-- Categories -->--}}
{{--                    <div class="footer-section">--}}
{{--                        <h3>Shop Categories</h3>--}}
{{--                        <ul style="list-style: none; padding: 0;">--}}
{{--                            @php--}}
{{--                                $footerCategories = \App\Models\Category::active()->parent()->orderBy('sort_order')->take(8)->get();--}}
{{--                            @endphp--}}
{{--                            @foreach($footerCategories as $category)--}}
{{--                                <li><a href="{{ route('category.show', $category->slug) }}" class="footer-link"--}}
{{--                                       wire:navigate>{{ $category->name }}</a></li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
{{--                    </div>--}}

{{--                    <!-- Customer Service -->--}}
{{--                    <div class="footer-section">--}}
{{--                        <h3>Customer Care</h3>--}}
{{--                        <ul style="list-style: none; padding: 0;">--}}
{{--                                                        <li><a href="{{ route('pages.help') }}" class="footer-link" wire:navigate>Help Center</a></li>--}}
{{--                                                        <li><a href="#" class="footer-link">Track Your Order</a></li>--}}
{{--                                                        <li><a href="{{ route('pages.shipping') }}" class="footer-link" wire:navigate>Shipping & Returns</a></li>--}}
{{--                                                        <li><a href="#" class="footer-link">Size Exchange</a></li>--}}
{{--                                                        <li><a href="#" class="footer-link">Gift Cards</a></li>--}}
{{--                                                        <li><a href="{{ route('pages.privacy') }}" class="footer-link" wire:navigate>Privacy Policy</a></li>--}}
{{--                                                        <li><a href="{{ route('pages.terms') }}" class="footer-link" wire:navigate>Terms of Service</a></li>--}}
{{--                            <li><a href="#" class="footer-link">Accessibility</a></li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}

{{--                    <!-- Newsletter -->--}}
{{--                    <div class="footer-section">--}}
{{--                        <h3>Stay Connected</h3>--}}
{{--                        <p>Join our exclusive community for early access to new collections, styling tips, and special--}}
{{--                            offers just for you.</p>--}}
{{--                        <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST"--}}
{{--                              onsubmit="handleNewsletter(event)">--}}
{{--                            @csrf--}}
{{--                            <input type="email" name="email" class="newsletter-input"--}}
{{--                                   placeholder="Enter your email address" required>--}}
{{--                            <button type="submit" class="newsletter-button">Subscribe</button>--}}
{{--                        </form>--}}
{{--                        <p style="font-size: 12px; color: rgba(255, 255, 255, 0.6); margin-top: 12px;">--}}
{{--                            By subscribing, you agree to receive marketing emails. Unsubscribe anytime.--}}
{{--                        </p>--}}

{{--                        <div style="margin-top: 32px;">--}}
{{--                            <h4 style="font-size: 16px; margin-bottom: 16px; color: rgba(255, 255, 255, 0.9);">Download--}}
{{--                                Our App</h4>--}}
{{--                            <div style="display: flex; gap: 12px; flex-wrap: wrap;">--}}
{{--                                <a href="#" style="display: inline-block;">--}}
{{--                                    <img--}}
{{--                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='135' height='40' viewBox='0 0 135 40'%3E%3Crect width='135' height='40' rx='5' fill='%23000'/%3E%3Ctext x='67.5' y='25' text-anchor='middle' fill='white' font-family='Arial' font-size='14'%3EApp Store%3C/text%3E%3C/svg%3E"--}}
{{--                                        alt="Download on the App Store" style="height: 40px;">--}}
{{--                                </a>--}}
{{--                                <a href="#" style="display: inline-block;">--}}
{{--                                    <img--}}
{{--                                        src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='135' height='40' viewBox='0 0 135 40'%3E%3Crect width='135' height='40' rx='5' fill='%23000'/%3E%3Ctext x='67.5' y='25' text-anchor='middle' fill='white' font-family='Arial' font-size='14'%3EGoogle Play%3C/text%3E%3C/svg%3E"--}}
{{--                                        alt="Get it on Google Play" style="height: 40px;">--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <!-- Footer Bottom -->--}}
{{--                <div class="footer-bottom">--}}
{{--                    <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 16px;">--}}
{{--                        © {{ date('Y') }} {{ config('app.name', 'ShopWithCarl') }}. All rights reserved. Designed with--}}
{{--                        💖 for confident women everywhere.--}}
{{--                    </p>--}}

{{--                    <div class="payment-methods">--}}
{{--                        @if(config('payment.methods'))--}}
{{--                            @foreach (config('payment.methods') as $method)--}}
{{--                                <div class="payment-method" title="{{ $method['name'] }}">--}}
{{--                                    <img src="{{ asset($method['image']) }}" alt="{{ $method['name'] }}">--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        @else--}}
{{--                            <div class="payment-method" title="Visa">--}}
{{--                                <svg width="24" height="16" viewBox="0 0 24 16" fill="none">--}}
{{--                                    <rect width="24" height="16" rx="2" fill="white"/>--}}
{{--                                    <text x="12" y="10" text-anchor="middle" fill="#1a1f71" font-family="Arial"--}}
{{--                                          font-size="8" font-weight="bold">VISA--}}
{{--                                    </text>--}}
{{--                                </svg>--}}
{{--                            </div>--}}
{{--                            <div class="payment-method" title="Mastercard">--}}
{{--                                <svg width="24" height="16" viewBox="0 0 24 16" fill="none">--}}
{{--                                    <rect width="24" height="16" rx="2" fill="white"/>--}}
{{--                                    <circle cx="8" cy="8" r="4" fill="#eb001b"/>--}}
{{--                                    <circle cx="16" cy="8" r="4" fill="#f79e1b"/>--}}
{{--                                </svg>--}}
{{--                            </div>--}}
{{--                            <div class="payment-method" title="American Express">--}}
{{--                                <svg width="24" height="16" viewBox="0 0 24 16" fill="none">--}}
{{--                                    <rect width="24" height="16" rx="2" fill="#006fcf"/>--}}
{{--                                    <text x="12" y="10" text-anchor="middle" fill="white" font-family="Arial"--}}
{{--                                          font-size="6" font-weight="bold">AMEX--}}
{{--                                    </text>--}}
{{--                                </svg>--}}
{{--                            </div>--}}
{{--                            <div class="payment-method" title="PayPal">--}}
{{--                                <svg width="24" height="16" viewBox="0 0 24 16" fill="none">--}}
{{--                                    <rect width="24" height="16" rx="2" fill="white"/>--}}
{{--                                    <text x="12" y="10" text-anchor="middle" fill="#003087" font-family="Arial"--}}
{{--                                          font-size="6" font-weight="bold">PayPal--}}
{{--                                    </text>--}}
{{--                                </svg>--}}
{{--                            </div>--}}
{{--                            <div class="payment-method" title="Apple Pay">--}}
{{--                                <svg width="24" height="16" viewBox="0 0 24 16" fill="none">--}}
{{--                                    <rect width="24" height="16" rx="2" fill="black"/>--}}
{{--                                    <text x="12" y="10" text-anchor="middle" fill="white" font-family="Arial"--}}
{{--                                          font-size="6" font-weight="bold">Apple Pay--}}
{{--                                    </text>--}}
{{--                                </svg>--}}
{{--                            </div>--}}
{{--                            <div class="payment-method" title="Google Pay">--}}
{{--                                <svg width="24" height="16" viewBox="0 0 24 16" fill="none">--}}
{{--                                    <rect width="24" height="16" rx="2" fill="white"/>--}}
{{--                                    <text x="12" y="10" text-anchor="middle" fill="#4285f4" font-family="Arial"--}}
{{--                                          font-size="6" font-weight="bold">G Pay--}}
{{--                                    </text>--}}
{{--                                </svg>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </footer>--}}
{{--    @endif--}}

{{--    <!-- Cart Drawer -->--}}
{{--    @if (!isset($isAuthPage) || !$isAuthPage)--}}
{{--        && !request()->routeIs('checkout.*')--}}
{{--        <livewire:cart.cart-drawer :count="$cartCount ?? 0"/>--}}
{{--    @endif--}}

{{--    <!-- Notifications -->--}}
{{--    <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>--}}
{{--</div>--}}

{{--@livewireScripts--}}
{{--@fluxScripts--}}

{{--<script>--}}
{{--    // Header scroll effect--}}
{{--    window.addEventListener('scroll', () => {--}}
{{--        const header = document.getElementById('header');--}}
{{--        if (window.scrollY > 100) {--}}
{{--            header.classList.add('scrolled');--}}
{{--        } else {--}}
{{--            header.classList.remove('scrolled');--}}
{{--        }--}}
{{--    });--}}

{{--    // Close announcement bar--}}
{{--    function closeAnnouncement() {--}}
{{--        const announcementBar = document.getElementById('announcementBar');--}}
{{--        announcementBar.style.transform = 'translateY(-100%)';--}}
{{--        setTimeout(() => {--}}
{{--            announcementBar.style.display = 'none';--}}
{{--        }, 300);--}}
{{--    }--}}

{{--    // Newsletter form handler--}}
{{--    function handleNewsletter(event) {--}}
{{--        event.preventDefault();--}}
{{--        const email = event.target.querySelector('input[type="email"]').value;--}}

{{--        // Simulate API call--}}
{{--        const button = event.target.querySelector('button');--}}
{{--        const originalText = button.textContent;--}}
{{--        button.textContent = 'Subscribing...';--}}
{{--        button.disabled = true;--}}

{{--        // Submit the form normally for Laravel processing--}}
{{--        event.target.submit();--}}

{{--        setTimeout(() => {--}}
{{--            button.textContent = 'Subscribed! ✓';--}}
{{--            button.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';--}}
{{--            event.target.querySelector('input').value = '';--}}

{{--            setTimeout(() => {--}}
{{--                button.textContent = originalText;--}}
{{--                button.disabled = false;--}}
{{--                button.style.background = 'var(--accent-gradient)';--}}
{{--            }, 2000);--}}
{{--        }, 1500);--}}
{{--    }--}}

{{--    // Livewire notifications--}}
{{--    document.addEventListener('livewire:initialized', () => {--}}
{{--        Livewire.on('show-notification', (event) => {--}}
{{--            const notification = document.createElement('div');--}}
{{--            notification.className = `px-4 py-2 rounded-lg shadow-lg text-white ${--}}
{{--                event.type === 'success' ? 'bg-green-500' :--}}
{{--                    event.type === 'error' ? 'bg-red-500' : 'bg-blue-500'--}}
{{--            }`;--}}
{{--            notification.textContent = event.message;--}}
{{--            document.getElementById('notifications').appendChild(notification);--}}
{{--            setTimeout(() => notification.remove(), 5000);--}}
{{--        });--}}
{{--    });--}}

{{--    // Smooth scrolling for anchor links--}}
{{--    document.querySelectorAll('a[href^="#"]').forEach(anchor => {--}}
{{--        anchor.addEventListener('click', function (e) {--}}
{{--            e.preventDefault();--}}
{{--            const target = document.querySelector(this.getAttribute('href'));--}}
{{--            if (target) {--}}
{{--                target.scrollIntoView({--}}
{{--                    behavior: 'smooth',--}}
{{--                    block: 'start'--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}
{{--    });--}}

{{--    // Add subtle parallax effect to floating particles--}}
{{--    window.addEventListener('scroll', () => {--}}
{{--        const particles = document.querySelectorAll('.particle');--}}
{{--        const scrolled = window.pageYOffset;--}}
{{--        const rate = scrolled * -0.5;--}}

{{--        particles.forEach((particle, index) => {--}}
{{--            particle.style.transform = `translateY(${rate * (index + 1) * 0.1}px)`;--}}
{{--        });--}}
{{--    });--}}

{{--    // Announcement bar close functionality--}}
{{--    document.querySelector('.close-announcement')?.addEventListener('click', () => {--}}
{{--        document.querySelector('.announcement-bar').style.display = 'none';--}}
{{--    });--}}

{{--    // Add loading animation for images--}}
{{--    document.addEventListener('DOMContentLoaded', () => {--}}
{{--        const images = document.querySelectorAll('img');--}}
{{--        images.forEach(img => {--}}
{{--            img.addEventListener('load', () => {--}}
{{--                img.style.opacity = '1';--}}
{{--            });--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}



    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'ShopWithCarl') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|poppins:300,400,500,600,700|playfair-display:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @fluxAppearance

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --luxury-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            --premium-gold: linear-gradient(135deg, #d4af37 0%, #f7ef8a 100%);
            --glass-primary: rgba(255, 255, 255, 0.15);
            --glass-secondary: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow-soft: 0 8px 32px rgba(31, 38, 135, 0.2);
            --shadow-premium: 0 20px 60px rgba(0, 0, 0, 0.1);
            --shadow-glow: 0 0 40px rgba(240, 147, 251, 0.3);
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

        /* Premium Announcement Bar */
        .announcement-bar {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white;
            padding: 12px 0;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
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
            gap: 30px;
            animation: slideAnnouncement 25s linear infinite;
            font-weight: 500;
            font-size: 14px;
        }

        .announcement-item {
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        .announcement-icon {
            width: 20px;
            height: 20px;
            background: var(--premium-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
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
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .close-announcement:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-50%) rotate(90deg) scale(1.1);
        }

        /* Premium Header */
        .header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(30px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
            box-shadow: var(--shadow-soft);
        }

        .header.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(40px);
            box-shadow: var(--shadow-premium);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .header-top {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 8px 0;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(20px);
        }

        .header-top-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }

        .header-top-links {
            display: flex;
            gap: 20px;
        }

        .header-top-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
        }

        .header-top-links a:hover {
            color: var(--pink-primary);
            transform: translateY(-1px);
        }

        .header-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 24px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 40px;
        }

        /* Logo Section */
        .logo-section {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
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
            bottom: -2px;
            left: 0;
            width: 0;
            height: 3px;
            background: var(--accent-gradient);
            border-radius: 2px;
            transition: var(--transition);
        }

        .logo:hover::after {
            width: 100%;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo img {
            height: 40px;
            width: auto;
        }

        /* Search Section */
        .search-section {
            position: relative;
            width: 100%;
            max-width: 500px;
        }

        .search-container {
            position: relative;
            display: flex;
            align-items: center;
            background: var(--glass-primary);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 4px;
            transition: var(--transition);
        }

        .search-container:hover,
        .search-container:focus-within {
            background: var(--glass-secondary);
            border-color: var(--pink-primary);
            box-shadow: var(--shadow-glow);
            transform: translateY(-2px);
        }

        .search-input {
            flex: 1;
            border: none;
            background: none;
            padding: 14px 20px;
            font-size: 15px;
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
            border-radius: 20px;
            padding: 12px 20px;
            color: white;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-btn:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-premium);
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            border-radius: 20px;
            box-shadow: var(--shadow-premium);
            border: 1px solid var(--glass-border);
            margin-top: 8px;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 1000;
        }

        .search-suggestions.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .suggestion-item {
            padding: 16px 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .suggestion-item:hover {
            background: var(--glass-primary);
            transform: translateX(8px);
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        /* Navigation Icons */
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 16px;
            justify-content: flex-end;
        }

        .nav-icon {
            position: relative;
            padding: 14px;
            border-radius: 20px;
            background: var(--glass-primary);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-icon:hover {
            background: var(--glass-secondary);
            transform: translateY(-3px);
            box-shadow: var(--shadow-premium);
            color: var(--pink-primary);
            border-color: var(--pink-primary);
        }

        .nav-icon svg {
            width: 22px;
            height: 22px;
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
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            border: 3px solid white;
            animation: bounce 0.6s var(--bounce);
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: scale(1);
            }
            40%, 43% {
                transform: scale(1.2);
            }
            70% {
                transform: scale(1.1);
            }
        }

        .user-dropdown {
            position: relative;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            border-radius: 20px;
            background: var(--glass-primary);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-weight: 500;
        }

        .user-button:hover {
            background: var(--glass-secondary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-premium);
            color: var(--pink-primary);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        /* Premium Navigation */
        .nav-bottom {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(30px);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 0 24px;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
        }

        .main-nav {
            display: flex;
            align-items: center;
            gap: 60px;
            padding: 24px 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-luxury);
            text-decoration: none;
            padding: 12px 0;
            transition: var(--transition);
            position: relative;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -6px;
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

        /* Mega Menu */
        .mega-menu {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            border-radius: 24px;
            box-shadow: var(--shadow-premium);
            border: 1px solid var(--glass-border);
            min-width: 600px;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 1000;
            margin-top: 20px;
            padding: 32px;
        }

        .nav-item:hover .mega-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .mega-menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
        }

        .mega-menu-section h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--text-luxury);
            margin-bottom: 16px;
            font-size: 16px;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .mega-menu-item {
            display: block;
            padding: 12px 0;
            color: var(--text-secondary);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .mega-menu-item:hover {
            color: var(--pink-primary);
            transform: translateX(8px);
            border-color: var(--pink-primary);
        }

        .mega-menu-featured {
            grid-column: span 3;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .featured-products {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .featured-product {
            text-align: center;
            padding: 16px;
            border-radius: 16px;
            background: var(--glass-primary);
            transition: var(--transition);
        }

        .featured-product:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-premium);
        }

        .featured-product img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 12px;
        }

        .featured-product h5 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-luxury);
            margin-bottom: 4px;
        }

        .featured-product .price {
            color: var(--pink-primary);
            font-weight: 700;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            background: var(--glass-primary);
            border: 1px solid var(--glass-border);
            cursor: pointer;
            padding: 12px;
            border-radius: 16px;
            transition: var(--transition);
            backdrop-filter: blur(20px);
        }

        .mobile-toggle:hover {
            background: var(--glass-secondary);
            transform: translateY(-2px);
        }

        .mobile-toggle svg {
            width: 24px;
            height: 24px;
            color: var(--text-primary);
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            pointer-events: none;
            opacity: 0.6;
        }

        .floating-element:nth-child(1) {
            width: 6px;
            height: 6px;
            background: var(--accent-gradient);
            border-radius: 50%;
            top: 20%;
            left: 10%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(2) {
            width: 4px;
            height: 4px;
            background: var(--premium-gold);
            border-radius: 50%;
            top: 60%;
            right: 15%;
            animation: float 8s ease-in-out infinite reverse;
        }

        .floating-element:nth-child(3) {
            width: 8px;
            height: 8px;
            background: var(--primary-gradient);
            border-radius: 50%;
            top: 40%;
            left: 80%;
            animation: float 7s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white;
            position: relative;
            overflow: hidden;
            margin-top: 80px;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--accent-gradient);
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 80px 24px 40px;
            position: relative;
            z-index: 2;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 60px;
            margin-bottom: 60px;
        }

        .footer-section h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 24px;
            background: var(--luxury-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .footer-section p, .footer-section li {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 12px;
            line-height: 1.7;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            display: inline-block;
            position: relative;
        }

        .footer-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-gradient);
            transition: var(--transition);
        }

        .footer-link:hover {
            color: white;
            transform: translateX(4px);
        }

        .footer-link:hover::after {
            width: 100%;
        }

        .social-links {
            display: flex;
            gap: 16px;
            margin-top: 24px;
        }

        .social-link {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .social-link:hover {
            background: var(--glass-primary);
            transform: translateY(-4px) rotate(5deg);
            box-shadow: var(--shadow-premium);
        }

        .newsletter-form {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .newsletter-input {
            flex: 1;
            padding: 16px 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            font-size: 14px;
            outline: none;
            transition: var(--transition);
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .newsletter-input:focus {
            border-color: var(--pink-primary);
            background: rgba(255, 255, 255, 0.15);
        }

        .newsletter-button {
            padding: 16px 24px;
            background: var(--accent-gradient);
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .newsletter-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-premium);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 40px;
            text-align: center;
        }

        .payment-methods {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .payment-method {
            width: 48px;
            height: 32px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
        }

        .payment-method:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .payment-method img {
            height: 20px;
            width: auto;
        }

        /* Mobile Menu */
        .offcanvas {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
        }

        .offcanvas-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 24px;
        }

        .offcanvas-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .offcanvas-body {
            padding: 24px;
        }

        .offcanvas .menu-list {
            list-style: none;
            padding: 0;
        }

        .offcanvas .menu-link-text {
            display: block;
            padding: 16px 0;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }

        .offcanvas .menu-link-text:hover {
            color: var(--pink-primary);
            transform: translateX(8px);
        }

        /* Flash Messages */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin: 16px 24px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border-color: rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #7f1d1d;
            border-color: rgba(239, 68, 68, 0.2);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .header-main {
                grid-template-columns: auto 1fr auto;
                gap: 20px;
            }

            .search-section {
                order: 3;
                grid-column: 1 / -1;
                margin-top: 16px;
            }

            .mega-menu {
                min-width: 90vw;
            }

            .mega-menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 24px;
            }
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .nav-bottom {
                display: none;
            }

            .header-top {
                display: none;
            }

            .header-main {
                padding: 16px 20px;
                grid-template-columns: auto 1fr auto;
                gap: 16px;
            }

            .search-section {
                order: initial;
                margin-top: 0;
            }

            .nav-icons {
                gap: 12px;
            }

            .nav-icon {
                padding: 12px;
            }

            .user-button span {
                display: none;
            }

            .logo {
                font-size: 24px;
            }

            .footer-grid {
                gap: 40px;
            }

            .newsletter-form {
                flex-direction: column;
            }
        }

        /* Premium Animations */
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

        /* Cart Preview Hover */
        .cart-preview {
            position: absolute;
            top: 100%;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            border-radius: 20px;
            box-shadow: var(--shadow-premium);
            border: 1px solid var(--glass-border);
            min-width: 320px;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 1000;
            margin-top: 12px;
            padding: 24px;
        }

        .nav-icon:hover .cart-preview {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .cart-preview-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .cart-preview-item:last-child {
            border-bottom: none;
        }

        .cart-preview-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .cart-preview-details h6 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-luxury);
            margin-bottom: 4px;
        }

        .cart-preview-details .price {
            color: var(--pink-primary);
            font-weight: 700;
            font-size: 14px;
        }

        /* Luxury Search Animation */
        .search-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 25px;
            padding: 1px;
            background: var(--accent-gradient);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            opacity: 0;
            transition: var(--transition);
        }

        .search-container:focus-within::before {
            opacity: 1;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-gradient);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-gradient);
        }

        /* Main Content */
        main {
            min-height: 60vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
    </style>
</head>

<body style="font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased;">
    <div style="min-height: 100vh;">
        <!-- Floating Elements -->
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>

        <!-- Announcement Bar -->
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

        <!-- Header -->
        <header class="header" id="header">
            <!-- Header Top -->
            <div class="header-top">
                <div class="header-top-content">
                    <div class="header-top-links">
{{--                        <a href="{{ route('pages.store-locator') }}" wire:navigate>Store Locator</a>--}}
{{--                        <a href="{{ route('pages.size-guide') }}" wire:navigate>Size Guide</a>--}}
{{--                        <a href="{{ route('pages.help') }}" wire:navigate>Customer Care</a>--}}
{{--                        <a href="{{ route('pages.track-order') }}" wire:navigate>Track Order</a>--}}
                    </div>
                    <div class="header-top-links">
{{--                        <a href="{{ route('pages.shipping') }}" wire:navigate>Free Shipping Over $75</a>--}}
{{--                        <a href="{{ route('pages.shipping') }}" wire:navigate>Returns & Exchanges</a>--}}
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

                <!-- Search Section -->
                <div class="search-section">
                    <div class="search-container">
                        <input type="text" class="search-input" placeholder="Search luxury lingerie, shapewear, activewear..." onkeyup="showSearchSuggestions(this.value)">
                        <button class="search-btn" data-bs-toggle="modal" data-bs-target="#searchModal">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                    </div>
                    <div class="search-suggestions" id="searchSuggestions">
                        <div class="suggestion-item">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Wireless bras
                        </div>
                        <div class="suggestion-item">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Seamless shapewear
                        </div>
                        <div class="suggestion-item">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Sports bras
                        </div>
                    </div>
                </div>

                <!-- Navigation Icons -->
                <div class="nav-icons">
                    <!-- User Account -->
                    <div class="user-dropdown">
                        @auth
                            <flux:dropdown>
                                <flux:button variant="ghost" class="user-button">
                                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                                    <span>{{ auth()->user()->name }}</span>
                                </flux:button>
                                <flux:menu>
                                    <flux:menu.item>
                                        <flux:link href="{{ route('home') }}" wire:navigate>Dashboard</flux:link>
                                    </flux:menu.item>
                                    <flux:menu.item>
                                        <flux:link href="{{ route('user.orders') }}" wire:navigate>Orders</flux:link>
                                    </flux:menu.item>
                                    <flux:menu.item>
                                        <flux:link href="{{ route('user.wishlist') }}" wire:navigate>Wishlist</flux:link>
                                    </flux:menu.item>
                                    <flux:menu.item>
                                        <flux:link href="{{ route('user.addresses') }}" wire:navigate>Addresses</flux:link>
                                    </flux:menu.item>
                                    <flux:menu.item>
{{--                                        <flux:link href="{{ route('profile.edit') }}" wire:navigate>Profile</flux:link>--}}
                                    </flux:menu.item>
                                    <flux:menu.separator/>
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
                        @else
                            <a href="{{ route('login') }}" class="user-button" wire:navigate>
                                <div class="user-avatar">AC</div>
                                <span>Account</span>
                            </a>
                        @endauth
                    </div>

                    @if (!isset($isAuthPage) || !$isAuthPage)
                        <!-- Wishlist -->
                        <a href="{{ route('user.wishlist') }}" class="nav-icon" title="Wishlist" wire:navigate>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span class="count-badge">{{ $wishlistCount ?? (auth()->check() ? auth()->user()->wishlist()->count() : 0) }}</span>
                        </a>

                        <!-- Shopping Cart -->
                        <div class="nav-icon" title="Shopping Cart">
                            <livewire:header/>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Navigation -->
            <div class="nav-bottom">
                <div class="nav-container">
                    <nav class="main-nav">
                        <div class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link" wire:navigate>Home</a>
                        </div>
                        <div class="nav-item">
                            <a href="{{ route('shop.index') }}" class="nav-link" wire:navigate>Shop</a>
                        </div>
                        <div class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link" wire:navigate>Products</a>
                        </div>
                        <div class="nav-item">
                            <a href="#" class="nav-link">Categories</a>
                            <div class="mega-menu">
                                <div class="mega-menu-grid">
                                    <div class="mega-menu-section">
                                        <h4>By Style</h4>
                                        @php
                                            $categories = \App\Models\Category::active()->parent()->orderBy('sort_order')->take(8)->get();
                                        @endphp
                                        @foreach($categories as $category)
                                            <a href="{{ route('category.show', $category->slug) }}" class="mega-menu-item" wire:navigate>{{ $category->name }}</a>
                                        @endforeach
                                    </div>
                                    <div class="mega-menu-section">
                                        <h4>By Occasion</h4>
                                        <a href="#" class="mega-menu-item">Everyday Comfort</a>
                                        <a href="#" class="mega-menu-item">Special Occasion</a>
                                        <a href="#" class="mega-menu-item">Sports & Active</a>
                                        <a href="#" class="mega-menu-item">Sleep & Lounge</a>
                                    </div>
                                    <div class="mega-menu-section">
                                        <h4>Collections</h4>
                                        <a href="#" class="mega-menu-item">Luxury Lace</a>
                                        <a href="#" class="mega-menu-item">Seamless Collection</a>
                                        <a href="#" class="mega-menu-item">Designer Series</a>
                                        <a href="{{ route('categories.index') }}" class="mega-menu-item" wire:navigate>View All Categories</a>
                                    </div>
                                </div>
                                <div class="mega-menu-featured">
                                    <h4 style="text-align: center; margin-bottom: 20px;">Featured Products</h4>
                                    <div class="featured-products">
                                        <div class="featured-product">
                                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='150' height='120' viewBox='0 0 150 120'%3E%3Crect width='150' height='120' fill='%23fdf2f8'/%3E%3Ctext x='75' y='65' text-anchor='middle' fill='%23e91e63' font-size='14'%3ELuxury Bra%3C/text%3E%3C/svg%3E" alt="Featured Product">
                                            <h5>Silk Touch Wireless</h5>
                                            <div class="price">$75.00</div>
                                        </div>
                                        <div class="featured-product">
                                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='150' height='120' viewBox='0 0 150 120'%3E%3Crect width='150' height='120' fill='%23f0f9ff'/%3E%3Ctext x='75' y='65' text-anchor='middle' fill='%230369a1' font-size='14'%3ELace Bralette%3C/text%3E%3C/svg%3E" alt="Featured Product">
                                            <h5>Delicate Lace</h5>
                                            <div class="price">$55.00</div>
                                        </div>
                                        <div class="featured-product">
                                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='150' height='120' viewBox='0 0 150 120'%3E%3Crect width='150' height='120' fill='%23f7fee7'/%3E%3Ctext x='75' y='65' text-anchor='middle' fill='%236b7280' font-size='12'%3EPush-Up Bra%3C/text%3E%3C/svg%3E" alt="Featured Product">
                                            <h5>Natural Lift</h5>
                                            <div class="price">$65.00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a href="#" class="nav-link">Pages</a>
                            <div class="mega-menu">
                                <div class="mega-menu-grid">
                                    <div class="mega-menu-section">
                                        <h4>Company</h4>
                                        <a href="{{ route('pages.about-us') }}" class="mega-menu-item" wire:navigate>About Us</a>
                                        <a href="#" class="mega-menu-item">Brands</a>
                                        <a href="{{ route('pages.contact') }}" class="mega-menu-item" wire:navigate>Contact</a>
                                        <a href="#" class="mega-menu-item">FAQ</a>
                                        <a href="{{ route('pages.store-locator') }}" class="mega-menu-item" wire:navigate>Store Locator</a>
                                    </div>
                                    <div class="mega-menu-section">
                                        <h4>Support</h4>
{{--                                        <a href="{{ route('pages.help') }}" class="mega-menu-item" wire:navigate>Help Center</a>--}}
{{--                                        <a href="{{ route('pages.shipping') }}" class="mega-menu-item" wire:navigate>Shipping & Returns</a>--}}
{{--                                        <a href="{{ route('pages.terms') }}" class="mega-menu-item" wire:navigate>Terms of Service</a>--}}
{{--                                        <a href="{{ route('pages.privacy') }}" class="mega-menu-item" wire:navigate>Privacy Policy</a>--}}
                                    </div>
                                    <div class="mega-menu-section">
                                        <h4>Account</h4>
{{--                                        <a href="{{ route('user.dashboard') }}" class="mega-menu-item" wire:navigate>Dashboard</a>--}}
{{--                                        <a href="{{ route('user.orders') }}" class="mega-menu-item" wire:navigate>Orders</a>--}}
{{--                                        <a href="{{ route('user.wishlist') }}" class="mega-menu-item" wire:navigate>Wishlist</a>--}}
{{--                                        <a href="{{ route('user.addresses') }}" class="mega-menu-item" wire:navigate>Addresses</a>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="nav-item">
                            <a href="{{ route('blog.index') }}" class="nav-link" wire:navigate>Blog</a>
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
                    <ul class="menu-list">
                        <li>
                            <a href="{{ route('home') }}" class="menu-link-text" wire:navigate>Home</a>
                        </li>
                        <li>
                            <a href="{{ route('shop.index') }}" class="menu-link-text" wire:navigate>Shop</a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" class="menu-link-text" wire:navigate>Products</a>
                        </li>
                        <li>
                            <a href="{{ route('pages.about-us') }}" class="menu-link-text" wire:navigate>About us</a>
                        </li>
                        <li>
                            <a href="{{ route('blog.index') }}" class="menu-link-text" wire:navigate>Blog</a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('home') }}" class="menu-link-text" wire:navigate>Dashboard</a>
                            </li>
                            <li>
                                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="menu-link-text" wire:navigate onclick="event.preventDefault(); this.closest('form').submit();">Log Out</a>
                                </form>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}" class="menu-link-text" wire:navigate>Login</a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" class="menu-link-text" wire:navigate>Register</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        @endif

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Main Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        @if (!isset($isAuthPage) || !$isAuthPage)
            <footer class="footer">
                <div class="floating-element"></div>
                <div class="floating-element"></div>
                <div class="floating-element"></div>

                <div class="footer-content">
                    <div class="footer-grid">
                        <!-- Company Info -->
                        <div class="footer-section">
                            <h3>ShopWithCarl</h3>
                            <p>Your premier destination for luxury women's undergarments, activewear, and shapewear. We believe every woman deserves to feel confident and beautiful.</p>
                            <p>
                                <strong>Address:</strong> {{ config('contact.address', '123 Fashion Avenue, Style District') }}
                            </p>
                            <p><strong>Email:</strong> <a href="mailto:{{ config('contact.email', 'hello@shopwithcarl.com') }}" class="footer-link">{{ config('contact.email', 'hello@shopwithcarl.com') }}</a></p>
                            <p><strong>Phone:</strong> <a href="tel:{{ config('contact.phone', '+1234567890') }}" class="footer-link">{{ config('contact.phone', '+1 (234) 567-890') }}</a></p>
                            <a href="{{ route('pages.contact') }}" class="footer-link" style="margin-top: 16px; display: inline-block;" wire:navigate>Get Directions →</a>

                            <div class="social-links">
                                @if(config('contact.socials'))
                                    @foreach (config('contact.socials') as $platform => $url)
                                        <a href="{{ $url }}" class="social-link" title="{{ ucfirst($platform) }}">
                                            @if($platform === 'facebook')
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                            @elseif($platform === 'instagram')
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.621 5.367 11.988 11.988 11.988s11.987-5.367 11.987-11.988C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.596-3.205-1.535l1.846-1.846c.39.586 1.054.977 1.808.977 1.297 0 2.345-1.048 2.345-2.345 0-.754-.391-1.418-.977-1.808l1.846-1.846c.939.757 1.535 1.908 1.535 3.205 0 2.247-1.831 4.078-4.078 4.078z"/>
                                                </svg>
                                            @elseif($platform === 'twitter')
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.986 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                </svg>
                                            @elseif($platform === 'pinterest')
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.165.120.285-.09.924-.297 1.199-.423 1.199-.314 0-1.022-.105-1.371-.394-.841-.696-1.364-1.893-1.364-3.185 0-3.896 2.85-7.466 8.207-7.466 4.312 0 7.662 3.075 7.662 7.174 0 4.288-2.703 7.737-6.453 7.737-1.259 0-2.447-.656-2.853-1.534 0 0-.622 2.378-.775 2.95-.281 1.05-1.03 2.369-1.532 3.177C9.505 23.788 10.748 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                                                </svg>
                                            @else
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="10"/>
                                                </svg>
                                            @endif
                                        </a>
                                    @endforeach
                                @else
                                    <a href="#" class="social-link" title="Facebook">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="social-link" title="Instagram">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.621 5.367 11.988 11.988 11.988s11.987-5.367 11.987-11.988C24.004 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.596-3.205-1.535l1.846-1.846c.39.586 1.054.977 1.808.977 1.297 0 2.345-1.048 2.345-2.345 0-.754-.391-1.418-.977-1.808l1.846-1.846c.939.757 1.535 1.908 1.535 3.205 0 2.247-1.831 4.078-4.078 4.078z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="social-link" title="Twitter">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.986 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="social-link" title="Pinterest">
                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.165.120.285-.09.924-.297 1.199-.423 1.199-.314 0-1.022-.105-1.371-.394-.841-.696-1.364-1.893-1.364-3.185 0-3.896 2.85-7.466 8.207-7.466 4.312 0 7.662 3.075 7.662 7.174 0 4.288-2.703 7.737-6.453 7.737-1.259 0-2.447-.656-2.853-1.534 0 0-.622 2.378-.775 2.95-.281 1.05-1.03 2.369-1.532 3.177C9.505 23.788 10.748 24 12.017 24c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                                        </svg>
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
                                <li><a href="{{ route('products.index') }}" class="footer-link" wire:navigate>New Arrivals</a></li>
                                <li><a href="#" class="footer-link">Best Sellers</a></li>
                                <li><a href="#" class="footer-link">Sale Items</a></li>
{{--                                <li><a href="{{ route('pages.size-guide') }}" class="footer-link" wire:navigate>Size Guide</a></li>--}}
                                <li><a href="{{ route('pages.about-us') }}" class="footer-link" wire:navigate>About Us</a></li>
                                <li><a href="{{ route('pages.contact') }}" class="footer-link" wire:navigate>Contact</a></li>
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
                                    <li><a href="{{ route('category.show', $category->slug) }}" class="footer-link" wire:navigate>{{ $category->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Customer Service -->
                        <div class="footer-section">
                            <h3>Customer Care</h3>
                            <ul style="list-style: none; padding: 0;">
{{--                                <li><a href="{{ route('pages.help') }}" class="footer-link" wire:navigate>Help Center</a></li>--}}
{{--                                <li><a href="{{ route('pages.track-order') }}" class="footer-link" wire:navigate>Track Your Order</a></li>--}}
{{--                                <li><a href="{{ route('pages.shipping') }}" class="footer-link" wire:navigate>Shipping & Returns</a></li>--}}
                                <li><a href="#" class="footer-link">Size Exchange</a></li>
                                <li><a href="#" class="footer-link">Gift Cards</a></li>
{{--                                <li><a href="{{ route('pages.privacy') }}" class="footer-link" wire:navigate>Privacy Policy</a></li>--}}
{{--                                <li><a href="{{ route('pages.terms') }}" class="footer-link" wire:navigate>Terms of Service</a></li>--}}
                                <li><a href="#" class="footer-link">Accessibility</a></li>
                            </ul>
                        </div>

                        <!-- Newsletter -->
                        <div class="footer-section">
                            <h3>Stay Connected</h3>
                            <p>Join our exclusive community for early access to new collections, styling tips, and special offers just for you.</p>
                            <form class="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" onsubmit="handleNewsletter(event)">
                                @csrf
                                <input type="email" name="email" class="newsletter-input" placeholder="Enter your email address" required>
                                <button type="submit" class="newsletter-button">Subscribe</button>
                            </form>
                            <p style="font-size: 12px; color: rgba(255, 255, 255, 0.6); margin-top: 12px;">
                                By subscribing, you agree to receive marketing emails. Unsubscribe anytime.
                            </p>

                            <div style="margin-top: 32px;">
                                <h4 style="font-size: 16px; margin-bottom: 16px; color: rgba(255, 255, 255, 0.9);">Download Our App</h4>
                                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                    <a href="#" style="display: inline-block;">
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='135' height='40' viewBox='0 0 135 40'%3E%3Crect width='135' height='40' rx='5' fill='%23000'/%3E%3Ctext x='67.5' y='25' text-anchor='middle' fill='white' font-family='Arial' font-size='14'%3EApp Store%3C/text%3E%3C/svg%3E" alt="Download on the App Store" style="height: 40px;">
                                    </a>
                                    <a href="#" style="display: inline-block;">
                                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='135' height='40' viewBox='0 0 135 40'%3E%3Crect width='135' height='40' rx='5' fill='%23000'/%3E%3Ctext x='67.5' y='25' text-anchor='middle' fill='white' font-family='Arial' font-size='14'%3EGoogle Play%3C/text%3E%3C/svg%3E" alt="Get it on Google Play" style="height: 40px;">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Bottom -->
                    <div class="footer-bottom">
                        <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 16px;">
                            © {{ date('Y') }} {{ config('app.name', 'ShopWithCarl') }}. All rights reserved. Designed with 💖 for confident women everywhere.
                        </p>

                        <div class="payment-methods">
                            @if(config('payment.methods'))
                                @foreach (config('payment.methods') as $method)
                                    <div class="payment-method" title="{{ $method['name'] }}">
                                        <img src="{{ asset($method['image']) }}" alt="{{ $method['name'] }}">
                                    </div>
                                @endforeach
                            @else
                                <div class="payment-method" title="Visa">
                                    <svg width="24" height="16" viewBox="0 0 24 16" fill="none">
                                        <rect width="24" height="16" rx="2" fill="white"/>
                                        <text x="12" y="10" text-anchor="middle" fill="#1a1f71" font-family="Arial" font-size="8" font-weight="bold">VISA</text>
                                    </svg>
                                </div>
                                <div class="payment-method" title="Mastercard">
                                    <svg width="24" height="16" viewBox="0 0 24 16" fill="none">
                                        <rect width="24" height="16" rx="2" fill="white"/>
                                        <circle cx="8" cy="8" r="4" fill="#eb001b"/>
                                        <circle cx="16" cy="8" r="4" fill="#f79e1b"/>
                                    </svg>
                                </div>
                                <div class="payment-method" title="American Express">
                                    <svg width="24" height="16" viewBox="0 0 24 16" fill="none">
                                        <rect width="24" height="16" rx="2" fill="#006fcf"/>
                                        <text x="12" y="10" text-anchor="middle" fill="white" font-family="Arial" font-size="6" font-weight="bold">AMEX</text>
                                    </svg>
                                </div>
                                <div class="payment-method" title="PayPal">
                                    <svg width="24" height="16" viewBox="0 0 24 16" fill="none">
                                        <rect width="24" height="16" rx="2" fill="#003087"/>
                                        <text x="12" y="10" text-anchor="middle" fill="white" font-family="Arial" font-size="6" font-weight="bold">PayPal</text>
                                    </svg>
                                </div>
                                <div class="payment-method" title="Apple Pay">
                                    <svg width="24" height="16" viewBox="0 0 24 16" fill="none">
                                        <rect width="24" height="16" rx="2" fill="#000000"/>
                                        <text x="12" y="10" text-anchor="middle" fill="white" font-family="Arial" font-size="6" font-weight="bold">Apple Pay</text>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </footer>
        @endif
    </div>

    @livewireScripts
    @fluxScripts
    <script>
        // Close announcement bar
        function closeAnnouncement() {
            const announcementBar = document.getElementById('announcementBar');
            if (announcementBar) {
                announcementBar.style.display = 'none';
            }
        }

        // Handle header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Placeholder for search suggestions
        function showSearchSuggestions(value) {
            const suggestions = document.getElementById('searchSuggestions');
            if (value.length > 2) {
                suggestions.classList.add('active');
            } else {
                suggestions.classList.remove('active');
            }
        }

        // Handle newsletter form submission
        function handleNewsletter(event) {
            event.preventDefault();
            const form = event.target;
            const email = form.querySelector('input[name="email"]').value;
            // Add your newsletter subscription logic here
            alert('Thank you for subscribing!');
            form.reset();
        }
    </script>
</body>
</html>
