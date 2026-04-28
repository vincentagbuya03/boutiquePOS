<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthorizationService;
use App\Traits\Authorizable;
use Illuminate\Auth\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Example controller showing how to implement RBAC.
 * Apply these patterns to your actual controllers.
 */
class ExampleRBACController extends Controller
{
    use Authorizable;

    /**
     * Example: List users - only Owner and Admin can see this.
     */
    public function listUsers(Request $request): JsonResponse
    {
        // Method 1: Using Authorizable trait
        $this->authorizeRoles(['owner', 'admin']);

        // Method 2: Using AuthorizationService
        // AuthorizationService::authorize('read', 'users');

        // Method 3: Using User model directly
        // if (!auth()->user()->canManageUsers()) {
        //     throw new AuthorizationException('Unauthorized');
        // }

        $users = User::query();

        // If not owner, filter by their branch
        if (!auth()->user()->isOwner()) {
            $users->where('branch', auth()->user()->branch);
        }

        return response()->json([
            'status' => 'success',
            'data' => $users->get(),
        ]);
    }

    /**
     * Example: Create a user - only Owner and Admin can do this.
     */
    public function createUser(Request $request): JsonResponse
    {
        $this->authorizePermission('create', 'users');

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,staff,cashier',
            'branch' => 'required|in:Dagupan,San Carlos',
        ]);

        // Admins can only create users for their own branch
        if (auth()->user()->isAdmin() && $validated['branch'] !== auth()->user()->branch) {
            throw new AuthorizationException('You can only create users for your own branch');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'branch' => $validated['branch'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    /**
     * Example: Manage products - only Owner and Staff can do this.
     */
    public function manageProducts(Request $request): JsonResponse
    {
        $this->authorizePermission('update', 'products');

        // Additional branch check
        $this->authorizeBranch($request->input('branch'));

        return response()->json([
            'status' => 'success',
            'message' => 'Products updated',
        ]);
    }

    /**
     * Example: Access POS - only Owner and Cashier can do this.
     */
    public function accessPOS(): JsonResponse
    {
        if (!auth()->user()->canAccessPOS()) {
            throw new AuthorizationException('You do not have access to the POS system');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'POS Access Granted',
        ]);
    }

    /**
     * Example: Create a sale - only Owner and Cashier can do this.
     */
    public function createSale(Request $request): JsonResponse
    {
        $this->authorizePermission('create', 'sales');

        $validated = $request->validate([
            'items' => 'required|array',
            'total' => 'required|numeric',
        ]);

        // Cashiers can only create sales for their branch
        if (auth()->user()->isCashier() && $validated['branch'] !== auth()->user()->branch) {
            throw new AuthorizationException('You can only create sales for your own branch');
        }

        // Create sale...

        return response()->json([
            'status' => 'success',
            'message' => 'Sale created successfully',
        ], 201);
    }

    /**
     * Example: Generate reports - only Owner and Admin can do this.
     */
    public function generateReports(Request $request): JsonResponse
    {
        $this->authorizeRoles(['owner', 'admin']);

        // Build reports...

        return response()->json([
            'status' => 'success',
            'message' => 'Reports generated',
        ]);
    }

    /**
     * Example: Using AuthorizationService directly.
     */
    public function checkAuthorization(string $action, string $resource): JsonResponse
    {
        $canAccess = AuthorizationService::can($action, $resource);

        return response()->json([
            'can_access' => $canAccess,
            'message' => $canAccess ? 'Access granted' : 'Access denied',
        ]);
    }

    /**
     * Example: Get permission matrix for debugging.
     */
    public function getPermissionMatrix(string $role): JsonResponse
    {
        $matrix = AuthorizationService::getPermissionMatrix($role);

        return response()->json([
            'role' => $role,
            'description' => AuthorizationService::getRoleDescription($role),
            'permissions' => $matrix,
        ]);
    }
}
