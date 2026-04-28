# Button & Navigation Visibility Guide

## Quick Diagnosis

**If you can't click a button or link**, it's likely because:

1. ❌ **You don't have permission** - The button shouldn't show for your role
2. ❌ **Route is blocked** - Middleware is preventing access
3. ❌ **Page not found** - Controller method is missing
4. ✅ **Cache issue** - Cleared! Run `php artisan cache:clear`

---

## What Each Role Should See

### 📊 OWNER

**Sidebar Navigation:**

- ✅ Dashboard
- ✅ Products
- ✅ Inventory + Low Stock
- ✅ Sales
- ✅ Online Orders
- ✅ Returns & Refunds
- ✅ Reports
- ✅ User Management

**Dashboard Buttons:**

- ✅ New Sale
- ✅ View Products
- ✅ Check Stock
- ✅ Process Return
- ✅ View Returns

**Product Page:**

- ✅ Add New Product button
- ✅ Edit buttons on each product

**Inventory Page:**

- ✅ Low Stock Items button
- ✅ Edit buttons on each item

**Returns Page:**

- ✅ New Return button

---

### 👨‍💼 ADMIN

**Sidebar Navigation:**

- ✅ Dashboard
- ✅ Online Orders
- ✅ Returns & Refunds
- ✅ Reports
- ✅ User Management
- ❌ Products (hidden)
- ❌ Inventory (hidden)
- ❌ Sales (hidden)

**Dashboard Buttons:**

- (View role-specific admin dashboard)

**Admin Features:**

- ✅ Can create Staff & Cashier users (in their branch only)
- ✅ Can manage online orders
- ✅ Can approve/reject returns
- ✅ Can view reports

---

### 👤 STAFF

**Sidebar Navigation:**

- ✅ Dashboard
- ✅ Products
- ✅ Inventory + Low Stock
- ❌ Sales (hidden)
- ❌ Online Orders (hidden)
- ❌ Returns & Refunds (hidden)
- ❌ Reports (hidden)
- ❌ User Management (hidden)

**Dashboard Buttons:**

- ✅ View All Products
- ✅ Manage Inventory
- ✅ View Low Stock

**Product Page:**

- ✅ Add New Product button
- ✅ Edit buttons on each product

**Inventory Page:**

- ✅ Low Stock Items button
- ✅ Edit buttons on each item

**Cannot Access:**

- ❌ Sales management
- ❌ Returns management
- ❌ Reports
- ❌ User management

---

### 💳 CASHIER

**Sidebar Navigation:**

- ✅ Dashboard
- ✅ Products (View only)
- ✅ Stock Level (View only)
- ✅ Sales
- ✅ Returns & Refunds
- ❌ Inventory (hidden - only "Stock Level")
- ❌ Online Orders (hidden)
- ❌ Reports (hidden)
- ❌ User Management (hidden)

**Dashboard Buttons:**

- ✅ New Sale
- ✅ View Products
- ✅ Check Stock
- ✅ Process Return
- ✅ View Returns

**Product Page:**

- ❌ Add New Product button (hidden)
- ❌ Edit buttons (hidden - read-only only)

**Inventory Page:**

- ❌ Low Stock Items button (hidden)
- ❌ Edit buttons (hidden - read-only only)

**Returns Page:**

- ✅ New Return button
- Can create and view returns only

---

## Fixed Issues

### ✅ What Was Fixed

1. **Edit Button Visibility**
    - Cashiers no longer see "Edit" buttons (read-only access)
    - Staff sees "Edit" buttons (can manage products/inventory)

2. **Action Button Visibility**
    - "Add New Product" button hidden from Cashiers
    - "Low Stock Items" button hidden from Cashiers
    - "Process Return" button only shows for Cashier/Owner

3. **Returns Management**
    - Returns link shows for Cashier/Owner and Admin
    - Staff cannot see or access returns

4. **Route Consolidation**
    - Removed duplicate route definitions
    - Proper middleware routing for each role

---

## Testing Checklist

### For Cashier User:

```
✓ Can see Products link (view-only)
✓ Can see Stock Level link (view-only)
✓ Can see Sales link (full access)
✓ Can see Returns & Refunds link (can create/view)
✓ Cannot see Edit buttons in products
✓ Cannot see Edit buttons in inventory
✓ CAN see "Process Return" button in dashboard
✓ CAN see "New Return" button in returns page
✓ Cannot see "Add New Product" button
```

### For Staff User:

```
✓ Can see Products link (full access)
✓ Can see Inventory + Low Stock links (full access)
✓ CAN see Edit buttons in products
✓ CAN see Edit buttons in inventory
✓ CAN see "Add New Product" button
✓ CAN see "Low Stock Items" button
✓ Cannot see Returns link
✓ Cannot see Sales link
✓ Cannot see Reports link
```

---

## If Something Still Doesn't Work

**Try these steps:**

1. **Clear cache:**

    ```bash
    php artisan cache:clear
    php artisan config:cache
    ```

2. **Check routes:**

    ```bash
    php artisan route:list | grep returns
    php artisan route:list | grep products
    php artisan route:list | grep inventory
    ```

3. **Check middleware:**
    - Routes use `role:owner,staff` or `role:owner,cashier` or `role:owner,admin`
    - Verify bootstrap/app.php has these middleware registered

4. **Check controller methods:**
    - `ProductController@create`
    - `InventoryController@edit`
    - `ReturnAndRefundController@create`

5. **Check User Model methods:**
    ```php
    $user->canManageProducts()      // Staff & Owner
    $user->canManageInventory()     // Staff & Owner
    $user->canAccessPOS()           // Cashier & Owner
    $user->canManageReturns()       // Admin & Owner
    ```

---

## Permission Matrix (Complete)

| Feature          | Owner | Admin | Staff | Cashier |
| ---------------- | ----- | ----- | ----- | ------- |
| Dashboard        | ✅    | ✅    | ✅    | ✅      |
| Products (CRUD)  | ✅    | ❌    | ✅    | ❌      |
| Products (Read)  | ✅    | ❌    | ✅    | ✅      |
| Inventory (CRUD) | ✅    | ❌    | ✅    | ❌      |
| Inventory (Read) | ✅    | ❌    | ✅    | ✅      |
| Sales (Create)   | ✅    | ❌    | ❌    | ✅      |
| Sales (Read)     | ✅    | ✅    | ❌    | ✅      |
| Online Orders    | ✅    | ✅    | ❌    | ❌      |
| Returns (Create) | ✅    | ❌    | ❌    | ✅      |
| Returns (Manage) | ✅    | ✅    | ❌    | ❌      |
| Reports          | ✅    | ✅    | ❌    | ❌      |
| Users            | ✅    | ✅    | ❌    | ❌      |

---

## Questions?

**Check these Blade template sections:**

- `resources/views/layouts/app.blade.php` - Sidebar links
- `resources/views/products/index.blade.php` - Product buttons
- `resources/views/inventory/index.blade.php` - Inventory buttons
- `resources/views/returns/index.blade.php` - Return buttons
- `resources/views/dashboards/cashier.blade.php` - Cashier dashboard
- `resources/views/dashboards/staff.blade.php` - Staff dashboard

All permission checks now properly show/hide buttons based on user roles! 🎯
