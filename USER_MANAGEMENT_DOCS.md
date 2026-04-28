# User Management System Documentation

## Overview

The user management system has been implemented to replace public user registration with admin-controlled user creation. This ensures secure and organized user account management across all branches.

## Key Changes

### 1. Public Registration Disabled

- **File**: `routes/web.php`
- Registration routes are now commented out
- Only Owner and Admin users can create new user accounts
- Users cannot self-register anymore

### 2. User Management Controller

- **File**: `app/Http/Controllers/UserController.php`
- New REST resource controller for managing users
- Features:
    - `index()` - List users (Owner sees all, Admin sees their branch)
    - `create()` - Show user creation form
    - `store()` - Create new user
    - `show()` - View user details
    - `edit()` - Show edit form
    - `update()` - Update user information
    - `destroy()` - Deactivate (soft delete) user
    - `forceDelete()` - Permanently delete (Owner only)

### 3. Routes

**User Management Routes** (Owner & Admin only):

```
POST   /users               - Create user
GET    /users               - List users
GET    /users/{user}        - View user details
GET    /users/{user}/edit   - Show edit form
PUT    /users/{user}        - Update user
DELETE /users/{user}        - Deactivate user
DELETE /users/{user}/force-delete - Permanently delete (Owner only)
```

### 4. Authorization Rules

#### Owner Privileges

- Can view all users across all branches
- Can create users with any role (Owner, Admin, Staff, Cashier)
- Can create users in any branch
- Can edit/update any user
- Can deactivate any user
- Can permanently delete any user

#### Admin Privileges

- Can view users only from their own branch
- Can create Staff and Cashier users only
- Can create users only in their own branch
- Can edit/update Staff and Cashier users from their branch
- Can edit own profile but cannot change own role
- Can deactivate Staff and Cashier users (not themselves)
- Cannot permanently delete users

#### Staff & Cashier Privileges

- Cannot access user management
- Cannot create, edit, or delete users

### 5. User Views

#### users/index.blade.php

- Lists all users (filtered by branch for Admin)
- Shows user details: name, email, role, branch, contact
- Action buttons: View, Edit, Delete
- "Add New User" button for Owner/Admin
- Role badges with color coding

#### users/create.blade.php

- User creation form with fields:
    - Full Name
    - Email (unique)
    - Contact Number
    - Address
    - Role (filtered by current user's permissions)
    - Branch (filtered by current user's permissions)
    - Password (with show/hide toggle)
    - Password Confirmation
- Permission matrix showing what each role can do
- Inline validation and error messages
- Password strength indicator

#### users/edit.blade.php

- User edit form with same fields as create
- User information sidebar showing:
    - Creation date
    - Last update date
    - Current status (Active/Inactive)
- Password reset section (placeholder for future implementation)
- Danger zone for deactivating user
- Permissions summary card

#### users/show.blade.php

- View-only user profile page
- Shows all user information
- Displays permissions matrix for that specific user
- Timeline information (created, updated, deactivated dates)
- Current status indicator

### 6. User Model Methods

New methods in `app/Models/User.php`:

**Permission Checks:**

```php
$user->canManageUsers()      // Can user manage other users?
$user->canManageProducts()   // Can user manage products?
$user->canManageInventory()  // Can user manage inventory?
$user->canAccessPOS()        // Can user access POS/Sales?
$user->canManageSales()      // Can user manage sales?
$user->canManageReturns()    // Can user manage returns?
$user->canManageOrders()     // Can user manage orders?
$user->canViewReports()      // Can user view reports?
```

**Display Methods:**

```php
$user->getRoleDisplayName()  // Returns: "Owner", "Admin", "Staff", "Cashier"
$user->getRoleBadgeColor()   // Returns: "danger", "warning", "info", "success"
```

### 7. Navigation Updates

Updated `resources/views/layouts/app.blade.php`:

- Added "Users" link to navbar (Owner/Admin only)
- Added "User Management" link to sidebar (Owner/Admin only)

### 8. Soft Deletes for Users

Users can be deactivated (soft deleted) which:

- Prevents login
- Marks user as inactive
- Keeps historical data intact
- Can be restored (Owner only)

Owner can permanently delete users (hard delete):

- Completely removes user from database
- Use with caution

### 9. Role Assignment Rules

**Owner Creating Users:**

- Can assign any role
- Can choose any branch

**Admin Creating Users:**

- Can only assign Staff or Cashier roles
- Can only create in their own branch
- Cannot create Owner or Admin users

### 10. Database Considerations

**Users Table Columns Used:**

- `id` - Primary key
- `name` - Full name
- `email` - Email address (unique)
- `password` - Hashed password
- `contact` - Contact number
- `address` - Address
- `role` - User role (owner, admin, staff, cashier)
- `branch` - Branch assignment
- `deleted_at` - Soft delete timestamp (for deactivation)
- `created_at` - Account creation date
- `updated_at` - Last update date

## Workflow Examples

### Creating a New Cashier User

1. Owner/Admin clicks "User Management" in sidebar
2. Clicks "Add New User" button
3. Fills in form:
    - Name: "Maria Santos"
    - Email: "maria@boutique.com"
    - Contact: "09171234567"
    - Address: "123 Shop Street"
    - Role: "Cashier"
    - Branch: "Dagupan"
    - Password: (min 8 characters)
4. Clicks "Create User"
5. Maria can now log in and access POS

### Deactivating a User

1. Owner/Admin navigates to Users Management
2. Clicks delete button next to user
3. Confirms deactivation
4. User is marked as inactive and cannot log in
5. User data is preserved for records

### Editing User Information

1. Owner/Admin navigates to Users Management
2. Click "Edit" button on user
3. Update fields (name, contact, address, role, etc.)
4. Click "Update User"
5. Changes are applied immediately
    - If role changed from Staff to Cashier, they lose inventory access
    - If branch changed, they can only see new branch data

## Security Considerations

1. **Password Security**
    - Passwords are hashed using Laravel's default hasher
    - Minimum 8 characters required
    - Password confirmation prevents typos
    - Passwords are never displayed or sent via email

2. **Branch Isolation**
    - Admins cannot escape their branch
    - Staff and Cashiers cannot view other branch data
    - Owner has full access across all branches

3. **Role Restrictions**
    - Admins cannot create other Admins or Owner accounts
    - Users cannot delete or deactivate themselves
    - Only Owner can permanently delete users

4. **Audit Trail**
    - User creation dates tracked
    - Update timestamps recorded
    - Deactivation dates preserved
    - Useful for security audits

## Test Accounts

Default test accounts with user management enabled:

### Owner Account

- **Email**: owner@boutique.com
- **Password**: password
- **Permissions**: Full access to all branches and all users

### Branch Admin Accounts

- **Dagupan Admin**: dagupan.admin@boutique.com
- **San Carlos Admin**: sancarlos.admin@boutique.com
- **Permissions**: Can manage users in their branch only

All test account passwords: `password`

## Future Enhancements

1. **Password Reset Functionality**
    - Email-based password reset link
    - Security questions backup
    - Admin-initiated password reset

2. **User Roles Management**
    - Option to create custom roles
    - Fine-grained permissions per role
    - Permission matrix UI

3. **Audit Logging**
    - Track all user management actions
    - Log who created/modified which users
    - View audit trail in admin panel

4. **Two-Factor Authentication**
    - SMS/Email OTP
    - Authenticator app support
    - Recovery codes

5. **User Deactivation Reasons**
    - Require reason when deactivating user
    - Track why users were deactivated
    - Automatic re-activation scheduling

## Troubleshooting

### User Cannot See User Management Link

- **Cause**: User doesn't have permission
- **Solution**: Only Owner and Admin can see this. User might be Staff or Cashier.

### Admin Seeing Other Branch Users

- **Cause**: Bug or admin privilege level
- **Solution**: Check user role. Admins should only see own branch.

### Cannot Create User Error

- **Cause**: Permission denied
- **Solution**: Only Owner and Admin can create users. Login as Owner/Admin.

### Cannot Delete Own Account

- **Cause**: Safety feature
- **Solution**: Only Owner can delete other users. Contact system admin.

## Files Modified/Created

**New Files:**

- `app/Http/Controllers/UserController.php`
- `app/Policies/UserPolicy.php`
- `resources/views/users/index.blade.php`
- `resources/views/users/create.blade.php`
- `resources/views/users/edit.blade.php`
- `resources/views/users/show.blade.php`

**Modified Files:**

- `routes/web.php` - Disabled public registration, added user management routes
- `app/Models/User.php` - Added helper methods for authorization and display
- `resources/views/layouts/app.blade.php` - Added user management navigation links
