<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'contact_number', 'address', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * User role constants
     */
    const ROLE_OWNER = 'owner';
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_CASHIER = 'cashier';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all sales processed by this user.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get all returns processed by this user.
     */
    public function processedReturns(): HasMany
    {
        return $this->hasMany(ReturnAndRefund::class, 'processed_by');
    }

    /**
     * Check if user is owner.
     */
    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    /**
     * Check if user is cashier.
     */
    public function isCashier(): bool
    {
        return $this->role === self::ROLE_CASHIER;
    }

    /**
     * Check if user can manage users (Owner or Admin).
     */
    public function canManageUsers(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN]);
    }

    /**
     * Check if user can manage products (Owner or Staff).
     */
    public function canManageProducts(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_STAFF]);
    }

    /**
     * Check if user can manage inventory (Owner or Staff).
     */
    public function canManageInventory(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_STAFF]);
    }

    /**
     * Check if user can access POS (Owner or Cashier).
     */
    public function canAccessPOS(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_CASHIER]);
    }

    /**
     * Check if user can view sales (all roles can view their own).
     */
    public function canViewSales(): bool
    {
        return true; // All roles can view sales
    }

    /**
     * Check if user can manage sales (Owner or Admin).
     */
    public function canManageSales(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN]);
    }

    /**
     * Check if user can manage inventory returns (Owner or Admin).
     */
    public function canManageReturns(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN]);
    }

    /**
     * Check if user has permission for the given action and resource.
     *
     * @param string $action - e.g., 'create', 'read', 'update', 'delete'
     * @param string $resource - e.g., 'users', 'products', 'sales'
     * @return bool
     */
    public function hasPermission(string $action, string $resource): bool
    {
        // Owner can do everything
        if ($this->isOwner()) {
            return true;
        }

        // Admin permissions
        if ($this->isAdmin()) {
            $adminResources = ['users', 'sales', 'returns', 'reports'];
            return in_array($resource, $adminResources);
        }

        // Staff permissions
        if ($this->isStaff()) {
            $staffResources = ['products', 'inventory'];
            return in_array($resource, $staffResources);
        }

        // Cashier permissions
        if ($this->isCashier()) {
            if ($action === 'read' && in_array($resource, ['products', 'inventory', 'sales'])) {
                return true;
            }
            if ($action === 'create' && $resource === 'sales') {
                return true;
            }
            if ($action === 'update' && $resource === 'sales') {
                return true;
            }
            if ($action === 'create' && $resource === 'returns') {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user can manage orders (Owner, Admin, or Cashier).
     */
    public function canManageOrders(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN, self::ROLE_CASHIER]);
    }

    /**
     * Check if user can view reports (Owner or Admin).
     */
    public function canViewReports(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN]);
    }

    /**
     * Get display name for the user's role.
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            self::ROLE_OWNER => 'Owner',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_STAFF => 'Staff',
            self::ROLE_CASHIER => 'Cashier',
            default => 'Unknown',
        };
    }

    /**
     * Get Bootstrap badge color class for the user's role.
     */
    public function getRoleBadgeColor(): string
    {
        return match($this->role) {
            self::ROLE_OWNER => 'danger',
            self::ROLE_ADMIN => 'warning',
            self::ROLE_STAFF => 'info',
            self::ROLE_CASHIER => 'success',
            default => 'secondary',
        };
    }

    /**
     * Get the system owner's name from the database.
     */
    public static function getOwnerName(): string
    {
        return self::where('role', self::ROLE_OWNER)->first()?->name ?? 'Vincent Agbuya';
    }
}
