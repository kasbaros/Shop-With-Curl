<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Validation\Rule;

    class UserController extends AdminController
    {
        public function index(Request $request)
        {
            $query = User::withCount('orders');

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'ILIKE', "%{$search}%")
                        ->orWhere('email', 'ILIKE', "%{$search}%")
                        ->orWhere('phone', 'ILIKE', "%{$search}%");
                });
            }

            // Role filter
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            // Status filter
            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            }

            // Date range filter
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Sort
            $sortField = $request->get('sort', 'created_at');
            $sortDirection = $request->get('direction', 'desc');

            // Handle special sort cases
            if ($sortField === 'orders_count') {
                $query->orderBy('orders_count', $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }

            $users = $query->paginate(20)->withQueryString();

            // Get statistics
            $stats = $this->getUserStats();

            return view('admin.user.index', array_merge(
                $this->getAdminViewData(),
                compact('users', 'stats')
            ));
        }

        public function create()
        {
            return view('admin.user.create', $this->getAdminViewData());
        }

        public function store(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:customer,admin,developer',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other',
                'email_verified' => 'boolean',
                'send_welcome_email' => 'boolean',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            // Set email_verified_at if checked
            if ($request->email_verified) {
                $validated['email_verified_at'] = now();
            }

            // Remove validation-only fields
            unset($validated['email_verified'], $validated['send_welcome_email']);

            $user = User::create($validated);

            // Send welcome email if requested
            if ($request->send_welcome_email) {
                // Dispatch welcome email job here
                // Mail::to($user)->send(new WelcomeEmail($user));
            }

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', 'User created successfully!');
        }

        public function show(User $user)
        {
            $user->load([
                'orders' => function($query) {
                    $query->latest()->limit(10);
                },
                'addresses',
                'reviews' => function($query) {
                    $query->with('product')->latest()->limit(5);
                }
            ]);

            // Get user statistics
            $userStats = [
                'total_orders' => $user->orders()->count(),
                'total_spent' => $user->orders()->whereNotIn('status', ['cancelled', 'refunded'])->sum('total_amount'),
                'average_order' => $user->orders()->whereNotIn('status', ['cancelled', 'refunded'])->avg('total_amount') ?? 0,
                'cancelled_orders' => $user->orders()->whereIn('status', ['cancelled', 'refunded'])->count(),
                'total_reviews' => $user->reviews()->count(),
                'approved_reviews' => $user->reviews()->where('is_approved', true)->count(),
            ];

            return view('admin.users.show', array_merge(
                $this->getAdminViewData(),
                compact('user', 'userStats')
            ));
        }

        public function edit(User $user)
        {
            return view('admin.users.edit', array_merge(
                $this->getAdminViewData(),
                compact('user')
            ));
        }

        public function update(Request $request, User $user)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user)],
                'phone' => 'nullable|string|max:20',
                'role' => 'required|in:customer,admin,developer',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other',
                'email_verified' => 'boolean',
                'is_active' => 'boolean',
            ]);

            // Handle email verification
            if ($request->email_verified && !$user->email_verified_at) {
                $validated['email_verified_at'] = now();
            } elseif (!$request->email_verified && $user->email_verified_at) {
                $validated['email_verified_at'] = null;
            }

            // Remove validation-only fields
            unset($validated['email_verified'], $validated['is_active']);

            $user->update($validated);

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', 'User updated successfully!');
        }

        public function destroy(User $user)
        {
            // Prevent deletion of users with orders
            if ($user->orders()->count() > 0) {
                return redirect()
                    ->route('admin.users.index')
                    ->with('error', 'Cannot delete user with existing orders.');
            }

            // Prevent deletion of admin users (safety check)
            if ($user->isAdmin() && User::where('role', '!=', 'customer')->count() <= 1) {
                return redirect()
                    ->route('admin.users.index')
                    ->with('error', 'Cannot delete the last admin user.');
            }

            $user->delete();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User deleted successfully!');
        }

        public function toggleStatus(User $user)
        {
            $isActive = $user->email_verified_at !== null;

            $user->update([
                'email_verified_at' => $isActive ? null : now()
            ]);

            $status = !$isActive ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "User {$status} successfully!",
                'is_active' => !$isActive
            ]);
        }

        /**
         * Reset user password
         */
        public function resetPassword(Request $request, User $user)
        {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
                'send_email' => 'boolean',
            ]);

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Send password reset email if requested
            if ($request->send_email) {
                // Mail::to($user)->send(new PasswordResetEmail($user));
            }

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully!'
            ]);
        }

        /**
         * Bulk actions for users
         */
        public function bulkAction(Request $request)
        {
            $request->validate([
                'action' => 'required|in:activate,deactivate,delete',
                'users' => 'required|array',
                'users.*' => 'exists:users,id'
            ]);

            $users = User::whereIn('id', $request->users);
            $count = $users->count();

            // Prevent bulk deletion of users with orders
            if ($request->action === 'delete') {
                $usersWithOrders = User::whereIn('id', $request->users)
                    ->withCount('orders')
                    ->having('orders_count', '>', 0)
                    ->count();

                if ($usersWithOrders > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot delete {$usersWithOrders} user(s) with existing orders."
                    ]);
                }
            }

            switch ($request->action) {
                case 'activate':
                    $users->update(['email_verified_at' => now()]);
                    $message = "{$count} users activated successfully!";
                    break;
                case 'deactivate':
                    $users->update(['email_verified_at' => null]);
                    $message = "{$count} users deactivated successfully!";
                    break;
                case 'delete':
                    $users->delete();
                    $message = "{$count} users deleted successfully!";
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        /**
         * Impersonate user (for developers only)
         */
        public function impersonate(User $user)
        {
            $this->requireDeveloper();

            // Store original user in session
            session(['impersonate_original_user' => auth()->id()]);

            // Login as the target user
            auth()->login($user);

            return redirect()
                ->route('home')
                ->with('info', "You are now impersonating {$user->name}. Click here to return to admin.");
        }

        /**
         * Stop impersonating and return to original user
         */
        public function stopImpersonating()
        {
            if (session()->has('impersonate_original_user')) {
                $originalUserId = session('impersonate_original_user');
                session()->forget('impersonate_original_user');

                auth()->loginUsingId($originalUserId);
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Returned to your admin account.');
        }

        private function getUserStats(): array
        {
            return [
                'total_users' => User::count(),
                'customers' => User::where('role', 'customer')->count(),
                'admins' => User::where('role', '!=', 'customer')->count(),
                'verified_users' => User::whereNotNull('email_verified_at')->count(),
                'unverified_users' => User::whereNull('email_verified_at')->count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
                'new_users_week' => User::where('created_at', '>=', now()->subDays(7))->count(),
            ];
        }

    }
