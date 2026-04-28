<?php

namespace App\Services;

use App\Models\User;

class AuthorizationService
{
    /**
     * Check if user can perform an action on a resource.
     */
    public static function can(string $action, string $resource, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        return $user->hasPermission($action, $resource);
    }

    /**
     * Check if user has a specific role.
     */
    public static function hasRole(string $role, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        return $user->role === $role;
    }

    /**
     * Check if user has any of the specified roles.
     */
    public static function hasAnyRole(array $roles, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        return in_array($user->role, $roles);
    }

    /**
     * Check if user has all of the specified roles (usually just one user).
     */
    public static function hasAllRoles(array $roles, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        return in_array($user->role, $roles);
    }

    /**
     * Get the permission matrix for a role.
     */
    public static function getPermissionMatrix(string $role): array
    {
        return match ($role) {
            'owner' => [
                'users' => ['create', 'read', 'update', 'delete'],
                'products' => ['create', 'read', 'update', 'delete'],
                'inventory' => ['create', 'read', 'update', 'delete'],
                'sales' => ['create', 'read', 'update', 'delete'],
                'returns' => ['create', 'read', 'update', 'delete'],
                'reports' => ['read'],
            ],
            'admin' => [
                'users' => ['create', 'read', 'update', 'delete'],
                'sales' => ['read'],
                'returns' => ['read'],
                'reports' => ['read'],
            ],
            'staff' => [
                'products' => ['create', 'read', 'update', 'delete'],
                'inventory' => ['create', 'read', 'update', 'delete'],
            ],
            'cashier' => [
                'products' => ['read'],
                'inventory' => ['read'],
                'sales' => ['create', 'read', 'update'],
                'returns' => ['create', 'read'],
            ],
            default => [],
        };
    }

    /**
     * Get roles that can perform an action on a resource.
     */
    public static function getRolesForPermission(string $action, string $resource): array
    {
        $matrix = [
            'owner' => self::getPermissionMatrix('owner'),
            'admin' => self::getPermissionMatrix('admin'),
            'staff' => self::getPermissionMatrix('staff'),
            'cashier' => self::getPermissionMatrix('cashier'),
        ];

        $rolesWithPermission = [];

        foreach ($matrix as $role => $permissions) {
            if (isset($permissions[$resource]) && in_array($action, $permissions[$resource])) {
                $rolesWithPermission[] = $role;
            }
        }

        return $rolesWithPermission;
    }

    /**
     * Get all accessible branches for a user.
     */
    public static function getAccessibleBranches(?User $user = null): array
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return [];
        }

        if ($user->isOwner()) {
            // Owners can access all branches - hardcoded for now
            return ['Dagupan', 'San Carlos'];
        }

        // Others can only access their own branch
        return $user->branch ? [$user->branch] : [];
    }

    /**
     * Check if user can access a specific branch.
     */
    public static function canAccessBranch(string $branch, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        return in_array($branch, self::getAccessibleBranches($user));
    }

    /**
     * Get role description (human-readable).
     */
    public static function getRoleDescription(string $role): string
    {
        return match ($role) {
            'owner' => 'Owner - Full system access, manages all branches',
            'admin' => 'Admin - Branch manager, manages staff and operations',
            'staff' => 'Staff - Manages products and inventory',
            'cashier' => 'Cashier - POS operator, processes sales',
            default => 'Unknown Role',
        };
    }

    /**
     * Authorize an action or throw exception.
     */
    public static function authorize(string $action, string $resource, ?User $user = null): void
    {
        if (!self::can($action, $resource, $user)) {
            throw new \Illuminate\Auth\AuthorizationException(
                "User is not authorized to {$action} {$resource}."
            );
        }
    }
}
