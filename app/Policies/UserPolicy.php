<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view a list of users.
     */
    public function viewAny(User $user): bool
    {
        return $user->canManageUsers();
    }

    /**
     * Determine whether the user can view the user.
     */
    public function view(User $user, User $model): bool
    {
        // Owner can view any user
        if ($user->isOwner()) {
            return true;
        }

        // Admin can view users from their branch
        if ($user->isAdmin()) {
            return $model->branch === $user->branch;
        }

        return false;
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->canManageUsers();
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $user, User $model): bool
    {
        // Owner can update any user
        if ($user->isOwner()) {
            return true;
        }

        // Admin can update users from their branch (but not change their own role)
        if ($user->isAdmin()) {
            if ($model->branch !== $user->branch) {
                return false;
            }
            
            // Admin cannot change their own role
            if ($model->id === $user->id && $model->role !== User::ROLE_ADMIN) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the user.
     */
    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        // Owner can delete any user
        if ($user->isOwner()) {
            return true;
        }

        // Admin can delete users from their branch
        if ($user->isAdmin()) {
            return $model->branch === $user->branch;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the user.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only Owner can permanently delete
        return $user->isOwner();
    }

    /**
     * Determine whether the user can restore the user.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isOwner();
    }
}
