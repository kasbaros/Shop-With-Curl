<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class AccountRedirectController extends Controller
{
    public function dashboard(): RedirectResponse
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isDeveloper()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('account.dashboard');
    }

    public function accountDashboard(): RedirectResponse
    {
        return redirect()->route('account.page', ['section' => 'dashboard']);
    }

    public function profileEdit(): RedirectResponse
    {
        return redirect()->route('account.page', ['section' => 'details']);
    }
}
