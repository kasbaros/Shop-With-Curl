<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('livewire.auth.login', [
            'isAuthPage' => true,
            'cartCount' => 0,
            'wishlistCount' => 0,
            'languages' => ['English'],
            'selectedLanguage' => 'English',
            'title' => 'Login',
        ]);
    }
}
