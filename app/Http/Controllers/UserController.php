<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $user = auth()->user();
        
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
        // Only Owner and Admin can create users
        if (!auth()->user()->canManageUsers()) {
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
        // Only Owner and Admin can create users
        if (!auth()->user()->canManageUsers()) {
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
        $currentUser = auth()->user();
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
        // Owner and Admin can view users
        if (!auth()->user()->isOwner() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Owner and Admin can edit users
        if (!auth()->user()->isOwner() && !auth()->user()->isAdmin()) {
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
        // Owner and Admin can update users
        if (!auth()->user()->isOwner() && !auth()->user()->isAdmin()) {
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
        $currentUser = auth()->user();
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
        // Cannot archive yourself
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot archive your own account.']);
        }

        // Owner and Admin can archive users
        if (!auth()->user()->isOwner() && !auth()->user()->isAdmin()) {
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
        // Only Owner can permanently delete
        if (!auth()->user()->isOwner()) {
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
        $user = auth()->user();

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
