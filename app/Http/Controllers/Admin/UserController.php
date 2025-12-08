<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filter by role (using Spatie)
        if ($request->has('role') && $request->role !== 'all') {
            $query->role($request->role);
        }

        // Filter by subscription tier
        if ($request->has('subscription') && $request->subscription !== 'all') {
            if ($request->subscription === 'premium') {
                $query->premium();
            } elseif ($request->subscription === 'expiring') {
                // Users with subscriptions expiring in next 7 days
                $query->whereHas('subscriptions', function ($sub) {
                    $sub->where('status', 'active')
                        ->whereBetween('expires_at', [now(), now()->addDays(7)]);
                });
            } elseif ($request->subscription === 'expired') {
                // Users with recently expired subscriptions
                $query->whereHas('subscriptions', function ($sub) {
                    $sub->where('status', 'expired')
                        ->orWhere(function ($q) {
                            $q->where('status', 'active')
                              ->where('expires_at', '<', now());
                        });
                });
            } else {
                // Free users: not premium (exclude those with active subscriptions)
                $query->where(function ($q) {
                    $q->where(function ($inner) {
                        $inner->where('subscription_tier', '!=', 'premium')
                              ->orWhereNull('subscription_tier');
                    })
                    ->whereDoesntHave('subscriptions', function ($sub) {
                        $sub->where('status', 'active')
                            ->where('expires_at', '>', now());
                    });
                });
            }
        }

        // Filter by course enrollment
        if ($request->filled('course')) {
            $query->whereHas('enrollment', function ($q) use ($request) {
                $q->where('course_id', $request->course);
            });
        }

        // Filter by verification status
        if ($request->has('verified') && $request->verified !== 'all') {
            if ($request->verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        // Filter by blocked status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'blocked') {
                $query->where('is_blocked', true);
            } elseif ($request->status === 'active') {
                $query->where('is_blocked', false);
            }
        }

        // Filter by registration date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['name', 'email', 'created_at', 'role'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $users = $query->paginate(15)->withQueryString();

        // Get courses for filter dropdown
        $courses = \App\Models\Course::where('is_published', true)->orderBy('title')->get();

        // Get roles for filter dropdown
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'courses', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'subscription_tier' => 'required|in:free,premium',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        // Remove roles from validated data before creating user
        $roles = $validated['roles'];
        unset($validated['roles']);

        $user = User::create($validated);

        // Assign roles using Spatie
        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['enrollment.course', 'activityLogs' => function ($query) {
            $query->latest()->limit(2);
        }]);

        // Paginate subscriptions separately
        $subscriptions = $user->subscriptions()
            ->with('package')
            ->latest()
            ->paginate(5);

        return view('admin.users.show', compact('user', 'subscriptions'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
            'subscription_tier' => 'required|in:free,premium',
            'subscription_expires_at' => 'nullable|date',
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'required|string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        // Remove roles from validated data before updating user
        $roles = $validated['roles'];
        unset($validated['roles']);

        $user->update($validated);

        // Sync roles (only if not editing own account's roles)
        if ($user->id !== Auth::id()) {
            $user->syncRoles($roles);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->getKey() === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Handle bulk actions on users.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:assign_role,delete,block,unblock',
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:users,id',
            'role' => 'required_if:action,assign_role|exists:roles,name',
        ]);

        // Remove current user from selection (can't modify own account)
        $ids = array_filter($validated['ids'], fn($id) => (int)$id !== Auth::id());

        if (empty($ids)) {
            return back()->with('error', 'No valid users selected (you cannot modify your own account).');
        }

        $users = User::whereIn('id', $ids)->get();
        $count = $users->count();

        switch ($validated['action']) {
            case 'assign_role':
                $roleName = $validated['role'];
                foreach ($users as $user) {
                    $user->syncRoles([$roleName]);
                }
                $roleDisplayName = ucwords(str_replace('-', ' ', $roleName));
                $message = "{$count} user(s) assigned to {$roleDisplayName} role!";
                break;

            case 'block':
                $blockedCount = 0;
                foreach ($users as $user) {
                    // Skip super-admins
                    if (!$user->hasRole('super-admin')) {
                        $user->block('Bulk blocked by admin', Auth::id());
                        $blockedCount++;
                    }
                }
                $message = "{$blockedCount} user(s) blocked successfully!";
                break;

            case 'unblock':
                foreach ($users as $user) {
                    $user->unblock();
                }
                $message = "{$count} user(s) unblocked successfully!";
                break;

            case 'delete':
                User::whereIn('id', $ids)->delete();
                $message = "{$count} user(s) deleted successfully!";
                break;

            default:
                return back()->with('error', 'Invalid action.');
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    /**
     * Impersonate a user (login as them).
     */
    public function impersonate(User $user)
    {
        // Can't impersonate yourself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        // Can't impersonate other admins/staff
        if ($user->hasAnyRole(['super-admin', 'admin', 'content-manager', 'question-editor'])) {
            return back()->with('error', 'You cannot impersonate staff members.');
        }

        // Store the original admin's ID in the session
        session(['impersonating_from' => Auth::id()]);

        // Log the impersonation
        \Illuminate\Support\Facades\Log::info('Admin impersonation started', [
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name,
            'impersonated_user_id' => $user->id,
            'impersonated_user_name' => $user->name,
        ]);

        // Login as the user
        Auth::login($user);

        return redirect()->route('learn.index')
            ->with('info', "You are now viewing the site as {$user->name}. Use the banner at the top to return to your admin account.");
    }

    /**
     * Block a user.
     */
    public function block(Request $request, User $user)
    {
        // Can't block yourself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot block your own account.');
        }

        // Can't block super-admins
        if ($user->hasRole('super-admin')) {
            return back()->with('error', 'Super admins cannot be blocked.');
        }

        $user->block($request->reason, Auth::id());

        return redirect()->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" has been blocked successfully.");
    }

    /**
     * Unblock a user.
     */
    public function unblock(User $user)
    {
        $user->unblock();

        return redirect()->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" has been unblocked successfully.");
    }

    /**
     * Stop impersonating and return to admin account.
     */
    public function stopImpersonating()
    {
        $originalAdminId = session('impersonating_from');

        if (!$originalAdminId) {
            return redirect()->route('learn.index')
                ->with('error', 'You are not currently impersonating anyone.');
        }

        $originalAdmin = User::find($originalAdminId);

        if (!$originalAdmin || !$originalAdmin->hasAnyRole(['super-admin', 'admin', 'content-manager', 'question-editor'])) {
            session()->forget('impersonating_from');
            return redirect()->route('learn.index')
                ->with('error', 'Unable to return to original account.');
        }

        $impersonatedUserName = Auth::user()->name;

        // Log the end of impersonation
        \Illuminate\Support\Facades\Log::info('Admin impersonation ended', [
            'admin_id' => $originalAdminId,
            'admin_name' => $originalAdmin->name,
            'impersonated_user_name' => $impersonatedUserName,
        ]);

        // Clear the session and login as original admin
        session()->forget('impersonating_from');
        Auth::login($originalAdmin);

        return redirect()->route('admin.users.index')
            ->with('success', "You have stopped impersonating {$impersonatedUserName} and returned to your admin account.");
    }
}
