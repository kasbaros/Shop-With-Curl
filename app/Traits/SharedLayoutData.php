<?php

    namespace App\Traits;

    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\Auth;

    trait SharedLayoutData
    {
        public function getLayoutData(): array
        {
            return [
                'cartCount' => Auth::check() ? Session::get('cart_count', 0) : 0,
                'wishlistCount' => Auth::check() ? Auth::user()->wishlist()->count() : 0,
                'languages' => Session::get('languages', config('app.languages', ['English'])),
                'selectedLanguage' => Session::get('language', config('app.default_language', 'English')),
                'selectedCurrency' => Session::get('currency', config('currencies.default_currency', 'UGX')),
            ];
        }
    }
