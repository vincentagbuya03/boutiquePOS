<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

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
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user || $user->role !== $role) {
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
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user || ! in_array($user->role, $roles)) {
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
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->hasPermission($action, $resource)) {
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
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->canManageBranch($branch)) {
            throw new AuthorizationException('Unauthorized action.');
        }
    }
}
