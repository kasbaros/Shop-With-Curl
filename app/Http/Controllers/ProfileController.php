<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone', 'date_of_birth', 'gender']));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function addresses()
    {
        $addresses = Auth::user()->addresses()->get();
        return view('user.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'type' => 'required|in:shipping,billing,both',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        $user = Auth::user();

        // If this is set as default, unset other defaults of the same type
        if ($request->is_default) {
            $user->addresses()
                ->where('type', $request->type)
                ->orWhere('type', 'both')
                ->update(['is_default' => false]);
        }

        $user->addresses()->create($request->all());

        return back()->with('success', 'Address added successfully!');
    }

    public function updateAddress(Request $request, UserAddress $address)
    {
        // Ensure user can only update their own addresses
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:shipping,billing,both',
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        // If this is set as default, unset other defaults of the same type
        if ($request->is_default) {
            Auth::user()->addresses()
                ->where('type', $request->type)
                ->orWhere('type', 'both')
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($request->all());

        return back()->with('success', 'Address updated successfully!');
    }

    public function deleteAddress(UserAddress $address)
    {
        // Ensure user can only delete their own addresses
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Address deleted successfully!');
    }

    public function orders()
    {
        return view('user.orders');
    }

    public function wishlist()
    {
        return view('user.wishlist');
    }
}
