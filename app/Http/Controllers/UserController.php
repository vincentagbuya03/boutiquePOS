<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        if ($user->isOwner() || $user->isAdmin()) {
            $users = User::withTrashed()->orderBy('name')->get();
        } else {
            abort(403, 'Unauthorized access');
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Only Owner and Admin can create users
        if (! $currentUser->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('users.create', [
            'roles' => $this->getAvailableRoles(),
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Only Owner and Admin can create users
        if (! $currentUser->canManageUsers()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'contact' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => [
                'required',
                Rule::in($this->getAvailableRoles()),
            ],
        ]);

        // Owner can create any role, Admin can only create staff and cashier
        if ($currentUser->isAdmin()) {
            if (!in_array($validated['role'], [User::ROLE_STAFF, User::ROLE_CASHIER])) {
                return back()->withErrors(['role' => 'You can only create Staff and Cashier users.']);
            }
        }

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Owner and Admin can view users
        if (! $currentUser->isOwner() && ! $currentUser->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Owner and Admin can edit users
        if (! $currentUser->isOwner() && ! $currentUser->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => $this->getAvailableRoles(),
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Owner and Admin can update users
        if (! $currentUser->isOwner() && ! $currentUser->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'contact' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'role' => [
                'required',
                Rule::in($this->getAvailableRoles()),
            ],
        ]);

        // Owner can update any user role, Admin can only update staff/cashier
        if ($currentUser->isAdmin()) {
            if (!in_array($validated['role'], [User::ROLE_STAFF, User::ROLE_CASHIER])) {
                return back()->withErrors(['role' => 'You can only assign Staff and Cashier roles.']);
            }
            if ($user->id === $currentUser->id && $validated['role'] !== User::ROLE_ADMIN) {
                return back()->withErrors(['role' => 'You cannot change your own role.']);
            }
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Archive the specified user from storage.
     */
    public function destroy(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Cannot archive yourself
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot archive your own account.']);
        }

        // Owner and Admin can archive users
        if (! $currentUser->isOwner() && ! $currentUser->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User archived successfully.');
    }

    /**
     * Permanently delete a user (hard delete) - Owner only.
     */
    public function forceDelete(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Only Owner can permanently delete
        if (! $currentUser->isOwner()) {
            abort(403, 'Only the Owner can permanently delete users.');
        }

        $user->forceDelete();

        return redirect()->route('users.index')->with('success', 'User permanently deleted.');
    }

    /**
     * Get available roles based on user's own role.
     */
    protected function getAvailableRoles()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isOwner()) {
            return [
                User::ROLE_OWNER => 'Owner',
                User::ROLE_ADMIN => 'Admin',
                User::ROLE_STAFF => 'Staff',
                User::ROLE_CASHIER => 'Cashier',
            ];
        } elseif ($user->isAdmin()) {
            return [
                User::ROLE_STAFF => 'Staff',
                User::ROLE_CASHIER => 'Cashier',
            ];
        }

        return [];
    }
}
