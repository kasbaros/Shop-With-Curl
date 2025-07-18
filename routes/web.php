<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\CompareController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/left-sidebar', [ShopController::class, 'leftSidebar'])->name('left-sidebar');
    Route::get('/right-sidebar', [ShopController::class, 'rightSidebar'])->name('right-sidebar');
    Route::get('/fullwidth', [ShopController::class, 'fullwidth'])->name('fullwidth');
    Route::get('/sub-collection', [ShopController::class, 'subCollection'])->name('sub-collection');
    Route::get('/collections-list', [ShopController::class, 'collectionsList'])->name('collections-list');
    Route::get('/pagination-links', [ShopController::class, 'paginationLinks'])->name('pagination-links');
    Route::get('/pagination-loadmore', [ShopController::class, 'paginationLoadmore'])->name('pagination-loadmore');
    Route::get('/pagination-infinite-scrolling', [ShopController::class, 'infiniteScrolling'])->name('infinite-scrolling');
    Route::get('/filter-sidebar', [ShopController::class, 'filterSidebar'])->name('filter-sidebar');
    Route::get('/filter-hidden', [ShopController::class, 'filterHidden'])->name('filter-hidden');
});

// Products
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
    // Additional product layouts
    Route::get('/grid-1', [ProductController::class, 'gridOne'])->name('grid-1');
    Route::get('/grid-2', [ProductController::class, 'gridTwo'])->name('grid-2');
    Route::get('/stacked', [ProductController::class, 'stacked'])->name('stacked');
    Route::get('/right-thumbnails', [ProductController::class, 'rightThumbnails'])->name('right-thumbnails');
    Route::get('/bottom-thumbnails', [ProductController::class, 'bottomThumbnails'])->name('bottom-thumbnails');
    Route::get('/drawer-sidebar', [ProductController::class, 'drawerSidebar'])->name('drawer-sidebar');
    Route::get('/description-accordion', [ProductController::class, 'descriptionAccordion'])->name('description-accordion');
    Route::get('/description-list', [ProductController::class, 'descriptionList'])->name('description-list');
    Route::get('/description-vertical', [ProductController::class, 'descriptionVertical'])->name('description-vertical');
    // Product features
    Route::get('/inner-zoom', [ProductController::class, 'innerZoom'])->name('inner-zoom');
    Route::get('/zoom-magnifier', [ProductController::class, 'zoomMagnifier'])->name('zoom-magnifier');
    Route::get('/no-zoom', [ProductController::class, 'noZoom'])->name('no-zoom');
    Route::get('/photoswipe-popup', [ProductController::class, 'photoswipePopup'])->name('photoswipe-popup');
    Route::get('/zoom-popup', [ProductController::class, 'zoomPopup'])->name('zoom-popup');
    Route::get('/video', [ProductController::class, 'video'])->name('video');
    Route::get('/3d', [ProductController::class, 'threeD'])->name('3d');
    Route::get('/options-customizer', [ProductController::class, 'optionsCustomizer'])->name('options-customizer');
    Route::get('/advanced-types', [ProductController::class, 'advancedTypes'])->name('advanced-types');
    Route::get('/giftcard', [ProductController::class, 'giftcard'])->name('giftcard');
    Route::get('/frequently-bought-together', [ProductController::class, 'frequentlyBoughtTogether'])->name('frequently-bought-together');
    Route::get('/frequently-bought-together-2', [ProductController::class, 'frequentlyBoughtTogetherTwo'])->name('frequently-bought-together-2');
    Route::get('/upsell-features', [ProductController::class, 'upsellFeatures'])->name('upsell-features');
    Route::get('/pre-orders', [ProductController::class, 'preOrders'])->name('pre-orders');
    Route::get('/notification', [ProductController::class, 'notification'])->name('notification');
    Route::get('/pickup', [ProductController::class, 'pickup'])->name('pickup');
    Route::get('/images-grouped', [ProductController::class, 'imagesGrouped'])->name('images-grouped');
    Route::get('/complimentary', [ProductController::class, 'complimentary'])->name('complimentary');
    Route::get('/quick-order-list', [ProductController::class, 'quickOrderList'])->name('quick-order-list');
    Route::get('/volume-discount', [ProductController::class, 'volumeDiscount'])->name('volume-discount');
    Route::get('/volume-discount-grid', [ProductController::class, 'volumeDiscountGrid'])->name('volume-discount-grid');
    Route::get('/buyx-gety', [ProductController::class, 'buyXGetY'])->name('buyx-gety');
});

// Categories
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('show');
});

// For backward compatibility
Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// Pages
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('/about-us', [PagesController::class, 'aboutUs'])->name('about-us');
    Route::get('/brands', [PagesController::class, 'brands'])->name('brands');
    Route::get('/brands-v2', [PagesController::class, 'brandsV2'])->name('brands-v2');
    Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
    Route::get('/contact-2', [PagesController::class, 'contactTwo'])->name('contact-2');
    Route::get('/faq', [PagesController::class, 'faq'])->name('faq');
    Route::get('/faq-2', [PagesController::class, 'faqTwo'])->name('faq-2');
    Route::get('/our-store', [PagesController::class, 'ourStore'])->name('our-store');
    Route::get('/store-locator', [PagesController::class, 'storeLocator'])->name('store-locator');
    Route::get('/timeline', [PagesController::class, 'timeline'])->name('timeline');
    Route::get('/view-cart', [PagesController::class, 'viewCart'])->name('view-cart');
    Route::get('/checkout', [PagesController::class, 'checkout'])->name('checkout'); // Note: This may overlap with checkout.index
    Route::get('/payment-confirmation', [PagesController::class, 'paymentConfirmation'])->name('payment-confirmation');
    Route::get('/payment-failure', [PagesController::class, 'paymentFailure'])->name('payment-failure');
    Route::get('/invoice', [PagesController::class, 'invoice'])->name('invoice');
    Route::get('/404', [PagesController::class, 'notFound'])->name('404');
    Route::get('/privacy-policy', [PagesController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::get('/returns-exchanges', [PagesController::class, 'returnsExchanges'])->name('returns-exchanges');
    Route::get('/shipping', [PagesController::class, 'shipping'])->name('shipping');
    Route::get('/terms-conditions', [PagesController::class, 'termsConditions'])->name('terms-conditions');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/grid', [BlogController::class, 'grid'])->name('grid');
    Route::get('/sidebar-left', [BlogController::class, 'sidebarLeft'])->name('sidebar-left');
    Route::get('/sidebar-right', [BlogController::class, 'sidebarRight'])->name('sidebar-right');
    Route::get('/list', [BlogController::class, 'list'])->name('list');
    Route::get('/{post:slug}', [BlogController::class, 'show'])->name('show');
});

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{key}', [CartController::class, 'update'])->name('update');
    Route::delete('/{key}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
    Route::post('/coupon', [CartController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/coupon', [CartController::class, 'removeCoupon'])->name('coupon.remove');
});

// Checkout (Auth required)
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

// Compare (Auth optional, depending on your implementation)
Route::prefix('compare')->name('compare.')->group(function () {
    Route::get('/', [CompareController::class, 'index'])->name('index');
    Route::post('/add', [CompareController::class, 'add'])->name('add');
    Route::delete('/{product}', [CompareController::class, 'remove'])->name('remove');
});

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// User Dashboard (Auth required)
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/addresses', [ProfileController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::patch('/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [ProfileController::class, 'deleteAddress'])->name('addresses.delete');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('order.show');

    Route::get('/wishlist', [ProfileController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/add', [ProfileController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/wishlist/{product}', [ProfileController::class, 'removeFromWishlist'])->name('wishlist.remove');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
