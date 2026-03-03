# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**ShopWithCarl** — A Laravel 12 e-commerce storefront with an admin panel. PHP 8.2+, PostgreSQL, Livewire 3 + Volt, Tailwind CSS v4, Bootstrap 5.3.

## Common Commands

```bash
# Development (runs server + queue + logs + Vite concurrently)
composer dev

# Tests (clears config cache first)
composer test

# Run a single test
php artisan test --filter=TestClassName
php artisan test --filter=test_method_name

# Code style (Laravel Pint)
./vendor/bin/pint          # Fix
./vendor/bin/pint --test   # Check only

# Frontend
npm run dev    # Vite dev server
npm run build  # Production build

# Custom artisan commands
php artisan settings:clear-cache [--all]
php artisan email:test {email}
php artisan blog:migrate
```

## Architecture

### Three-Tier Route Structure

- **Guest** (`/`, `/shop`, `/products/{slug}`, `/blog`, `/categories`): Public storefront. Uses `block.privileged` middleware to keep admins out.
- **Client** (`/account`, `/cart`, `/checkout`, `/wishlist`, `/compare`): Authenticated customers. Middleware: `auth` + `client`.
- **Admin** (`/admin/*`): Resource controllers for all management. Middleware: `auth` + `admin`. Developer-only sub-routes under `/admin/settings`.

Auth pages use Volt single-file components via `Livewire\Volt\Volt::route()` in `routes/auth.php`.

### User Roles

Three roles via `users.role` column: `customer`, `admin`, `developer`. Primary access control uses custom role methods (`isAdmin()`, `isDeveloper()`, `isCustomer()`) on the User model, not Spatie Permission (which is installed but secondary).

### Key Services & Patterns

- **CartService** (singleton): Session-based cart stored in `session('cart')`. Bound in `AppServiceProvider`.
- **CompareService** (singleton): Product comparison, also bound in `AppServiceProvider`.
- **Payment gateways**: `PaymentGatewayFactory` dispatches to `MtnMomoService`, `AirtelMoneyService`, `PayPalService`, `ManualPaymentService`, or Stripe. All implement `PaymentGatewayInterface`. Payments logged via `LogsPayments` trait to `payment_logs` table.
- **Settings system**: DB-driven key-value `settings` table with 2-hour cache. Use global helpers: `setting('key', $default)`, `settings()`, `set_setting()`. Mail config is loaded from DB at boot in `AppServiceProvider`.
- **Image storage**: Hybrid system — Spatie MediaLibrary + custom `gallery` JSON array on products. `ImageStorageHelper` and `HasStorageImages` trait handle the merging.

### Currency & Money

All prices are in UGX (Ugandan Shilling). Use `money_format_ugx($amount)` — no decimal places. A general `format_currency($amount, $currency)` helper also exists.

### Blade Directives

`@storage($path)`, `@imageUrl($path)`, `@imageOptimized($path)` — registered in `AppServiceProvider`.

### Route Model Binding

Products and categories use `{model:slug}` binding via Spatie Sluggable.

## Infrastructure

- **Database**: PostgreSQL (`shop_with_carl`)
- **Queue**: Database-backed, managed by Laravel Horizon
- **Cache/Session**: Both database-backed
- **Server**: Laravel Octane with RoadRunner
- **Search**: Laravel Scout with database driver (Algolia installed but disabled)
- **Media disk**: `storage/app/public/media` served at `/storage/media`

## Seeding

Always run `AdminUserSeeder` + `SettingsSeeder`. Test data seeders are non-production only.
