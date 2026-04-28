<?php

namespace App\Traits;

use Illuminate\Auth\AuthorizationException;

trait Authorizable
{
    /**
     * Authorize the request based on user role.
     *
     * @param  string  $role
     * @throws AuthorizationException
     */
    public function authorizeRole(string $role): void
    {
        if (!auth()->check() || auth()->user()->role !== $role) {
            throw new AuthorizationException('Unauthorized action.');
        }
    }

    /**
     * Authorize the request based on multiple roles.
     *
     * @param  array  $roles
     * @throws AuthorizationException
     */
    public function authorizeRoles(array $roles): void
    {
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            throw new AuthorizationException('Unauthorized action.');
        }
    }

    /**
     * Authorize the request based on permission.
     *
     * @param  string  $action
     * @param  string  $resource
     * @throws AuthorizationException
     */
    public function authorizePermission(string $action, string $resource): void
    {
        if (!auth()->check() || !auth()->user()->hasPermission($action, $resource)) {
            throw new AuthorizationException('Unauthorized action.');
        }
    }

    /**
     * Authorize that the user can manage the given branch.
     *
     * @param  string  $branch
     * @throws AuthorizationException
     */
    public function authorizeBranch(string $branch): void
    {
        if (!auth()->check() || !auth()->user()->canManageBranch($branch)) {
            throw new AuthorizationException('Unauthorized action.');
        }
    }
}
