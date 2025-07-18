<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait SharedLayoutData
{
    public function getLayoutData(): array
    {
        return [
            'cartCount' => Session::get('cart', []) ? count(Session::get('cart')) : 0,
            'wishlistCount' => Auth::check() ? Auth::user()->wishlist()->count() : 0,
            'languages' => ['English', 'Arabic', 'Chinese', 'Urdu'], // Example languages array
            'selectedLanguage' => Session::get('selected_language', 'English'), // Example fallback
        ];
    }
}
