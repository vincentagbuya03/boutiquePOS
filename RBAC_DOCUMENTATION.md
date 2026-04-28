# Role-Based Access Control (RBAC) System

## Overview

This document outlines the role-based access control system implemented for the Boutique POS application. The system supports 4 main roles with specific permissions tied to actions and resources.

## Role Hierarchy & Permissions

### 1. **Owner** (role: `owner`)

- **Branch Access**: All branches
- **Permissions**:
    - ✅ Manage Admins (create, update, delete)
    - ✅ Manage Staff (create, update, delete)
    - ✅ Manage Cashiers (create, update, delete)
    - ✅ View and manage all Products (all branches)
    - ✅ View and manage all Inventory (all branches)
    - ✅ View all Sales (all branches)
    - ✅ View all Returns & Refunds (all branches)
    - ✅ Access Reports (all branches)
    - ✅ Create, view, update, delete in all resources

**Use Case**: Company owner or business manager overseeing multiple branches.

---

### 2. **Admin** (role: `admin`) - Branch Specific

- **Branch Access**: Their assigned branch only
- **Permissions**:
    - ✅ Manage Users (Staff, Cashiers) in their branch
    - ✅ View and manage Sales in their branch
    - ✅ View and manage Returns & Refunds in their branch
    - ✅ View Reports (their branch)
    - ❌ Cannot manage Products or Inventory directly
    - ❌ Cannot access other branches

**Use Case**: Branch manager responsible for staff management and branch operations oversight.

---

### 3. **Staff** (role: `staff`) - Branch Specific

- **Branch Access**: Their assigned branch only
- **Permissions**:
    - ✅ Create, Read, Update, Delete Products (their branch)
    - ✅ Manage Inventory (their branch)
    - ✅ View Products Inventory (quantity, stock levels)
    - ❌ Cannot manage Users
    - ❌ Cannot access POS/Sales
    - ❌ Cannot view Sales data
    - ❌ Cannot access other branches

**Use Case**: Warehouse or inventory staff managing products and stock levels.

---

### 4. **Cashier** (role: `cashier`) - Branch Specific

- **Branch Access**: Their assigned branch only
- **Permissions**:
    - ✅ Create Sales (POS transactions)
    - ✅ View Products and Quantities
    - ✅ View Sales (their own created sales)
    - ✅ Create Returns (initiate returns)
    - ❌ Cannot manage Products or Inventory
    - ❌ Cannot create Users
    - ❌ Cannot access other branches

**Use Case**: Point of Sale operator handling customer transactions.

---

## Implementation Guide

### In Controllers (Using Traits)

```php
<?php

namespace App\Http\Controllers;

use App\Traits\Authorizable;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use Authorizable;

    public function store(Request $request)
    {
        // Check if user can manage products
        $this->authorizePermission('create', 'products');

        // Create product...
    }

    public function update(Request $request, $id)
    {
        // Check if user can update products
        $this->authorizePermission('update', 'products');

        // Update product...
    }
}
```

### In Routes (Using Middleware)

```php
Route::middleware(['auth', 'role:owner,admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('sales', SalesController::class);
});

Route::middleware(['auth', 'role:owner,staff'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('inventory', InventoryController::class);
});

Route::middleware(['auth', 'role:owner,cashier'])->group(function () {
    Route::post('pos/checkout', [SalesController::class, 'checkout']);
});

// Branch-scoped routes
Route::middleware(['auth', 'branch:branch'])->group(function () {
    Route::get('/branch/{branch}/dashboard', [DashboardController::class, 'index']);
});
```

### In Blade Templates

```blade
@if(auth()->user()->isOwner())
    <li><a href="/admin/global-reports">Global Reports</a></li>
@endif

@if(auth()->user()->canManageUsers())
    <li><a href="/admin/users">Manage Users</a></li>
@endif

@if(auth()->user()->canManageProducts())
    <li><a href="/products">Manage Products</a></li>
@endif

@if(auth()->user()->canAccessPOS())
    <li><a href="/pos">POS System</a></li>
@endif

@if(auth()->user()->canManageInventory())
    <li><a href="/inventory">Manage Inventory</a></li>
@endif
```

### Direct Permission Checks

```php
// Check single role
if (auth()->user()->isOwner()) {
    // Owner-only logic
}

// Check multiple roles
if (auth()->user()->canManageUsers()) { // Owner or Admin
    // Can manage users
}

// Check specific permission
if (auth()->user()->hasPermission('create', 'products')) {
    // Can create products
}

// Check branch access
if (auth()->user()->canManageBranch('Dagupan')) {
    // Can manage Dagupan branch
}
```

---

## User Creation / Seeding

### Using Seeder

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RoleBasedUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Owner
        User::create([
            'name' => 'John Owner',
            'email' => 'owner@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09123456789',
            'role' => 'owner', // No branch for owner
            'branch' => null,
        ]);

        // Create Admin for Dagupan Branch
        User::create([
            'name' => 'Maria Admin',
            'email' => 'admin.dagupan@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09187654321',
            'role' => 'admin',
            'branch' => 'Dagupan',
        ]);

        // Create Staff for Dagupan Branch
        User::create([
            'name' => 'Pedro Staff',
            'email' => 'staff.dagupan@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09198765432',
            'role' => 'staff',
            'branch' => 'Dagupan',
        ]);

        // Create Cashier for Dagupan Branch
        User::create([
            'name' => 'Ana Cashier',
            'email' => 'cashier.dagupan@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09187654322',
            'role' => 'cashier',
            'branch' => 'Dagupan',
        ]);

        // Create Admin for San Carlos Branch
        User::create([
            'name' => 'Juan Admin SC',
            'email' => 'admin.sancarlos@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09154321098',
            'role' => 'admin',
            'branch' => 'San Carlos',
        ]);

        // Create Staff for San Carlos Branch
        User::create([
            'name' => 'Rosa Staff SC',
            'email' => 'staff.sancarlos@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09165432109',
            'role' => 'staff',
            'branch' => 'San Carlos',
        ]);

        // Create Cashier for San Carlos Branch
        User::create([
            'name' => 'Carlos Cashier SC',
            'email' => 'cashier.sancarlos@boutique.com',
            'password' => bcrypt('password'),
            'contact_number' => '09176543210',
            'role' => 'cashier',
            'branch' => 'San Carlos',
        ]);
    }
}
```

---

## Database Query Optimization

### Using BranchScoped Trait

Add the trait to models that are branch-specific:

```php
<?php

namespace App\Models;

use App\Traits\BranchScoped;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use BranchScoped;

    protected $fillable = ['name', 'branch', ...];
}
```

Then use it in queries:

```php
// Get products for current user's branch
$products = Product::forUserBranch()->get();

// Or with specific user
$products = Product::forUserBranch($user)->get();

// Get accessible resources
$inventory = Inventory::accessibleTo()->get();
```

---

## API Response Examples

### Successful Authorization

```json
{
    "status": "success",
    "message": "Resource retrieved",
    "data": { ... }
}
```

### Unauthorized (401)

```json
{
    "message": "Unauthorized"
}
```

### Forbidden (403)

```json
{
    "message": "Forbidden: Insufficient permissions"
}
```

---

## Testing Authorization

Example test:

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    public function test_owner_can_manage_all_users()
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner);

        $response = $this->get('/users');
        $response->assertStatus(200);
    }

    public function test_cashier_cannot_manage_products()
    {
        $cashier = User::factory()->create(['role' => 'cashier']);
        $this->actingAs($cashier);

        $response = $this->post('/products', ['name' => 'Test']);
        $response->assertStatus(403);
    }

    public function test_staff_cannot_access_other_branch()
    {
        $staff = User::factory()->create(['role' => 'staff', 'branch' => 'Dagupan']);
        $this->actingAs($staff);

        $response = $this->get('/branch/San Carlos/dashboard');
        $response->assertStatus(403);
    }
}
```

---

## Migration & Deployment

1. **Run the migration**:

    ```bash
    php artisan migrate
    ```

2. **Seed the database** (optional):

    ```bash
    php artisan db:seed --class=RoleBasedUserSeeder
    ```

3. **Update existing users** (if needed):

    ```php
    // Update admin users
    User::where('role', 'admin')->update(['role' => 'admin']);

    // Convert old structure if needed
    ```

---

## Summary Table

| Resource              | Owner | Admin | Staff | Cashier |
| --------------------- | ----- | ----- | ----- | ------- |
| **Users Management**  | ✅    | ✅    | ❌    | ❌      |
| **Products**          | ✅    | ❌    | ✅    | 🔍      |
| **Inventory**         | ✅    | ❌    | ✅    | 🔍      |
| **Sales (Create)**    | ✅    | ❌    | ❌    | ✅      |
| **Sales (View All)**  | ✅    | ✅    | ❌    | ✅\*    |
| **POS Access**        | ✅    | ❌    | ❌    | ✅      |
| **Returns & Refunds** | ✅    | ✅    | ❌    | ✅\*    |
| **Reports**           | ✅    | ✅    | ❌    | ❌      |
| **Branch Access**     | All   | Own   | Own   | Own     |

**Legend**: ✅ = Full Access, 🔍 = View Only, ❌ = No Access, \* = Limited
