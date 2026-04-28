# Branch Filter Implementation Guide

## Overview

The branch filter system allows owners to toggle between branches (Dagupan and San Carlos) to view filtered data for each branch. Non-owners are locked to their assigned branch.

## How It Works

### Frontend (UI Layer)

- **Branch Selector Links** in the navbar allow owners to click "DAGUPAN" or "SAN CARLOS" to toggle branches
- Branch selection is stored in the browser's localStorage
- Selected branches are synced to the server's session via an AJAX call to `/api/set-branch-filter`
- Non-owners see their navbar branch selector disabled (greyed out)

### Backend (Data Layer)

- The `getBranchFilter()` helper function returns an array of selected branches
- Non-owners always get their assigned branch: `[user.branch]`
- Owners get their selected branches from the session, or all branches if none selected

## Using Branch Filter in Controllers

### Method 1: Simple Query Filtering

```php
use App\Models\Inventory;

// Filter a single table by branch
$inventories = Inventory::query()
    ->filter(getBranchFilter(), 'branch')  // Requires scope
    ->get();
```

### Method 2: Helper Function

```php
// Using the helper
$sales = filterByBranch(Sale::query())
    ->where('date_sold', '>=', now()->subMonth())
    ->get();
```

### Method 3: Manual Where Clause

```php
$branches = getBranchFilter();
$inventories = Inventory::whereIn('branch', $branches)->get();
```

### Method 4: With Relationships

```php
// For products with inventory in selected branches
$products = Product::whereHas('inventories', function($query) {
    filterInventoryByBranch($query);
})->get();
```

## User Experience

### For Owners

1. Login to dashboard
2. See both "DAGUPAN" and "SAN CARLOS" links in the navbar (clickable)
3. Click a branch to filter data to that branch only
4. Click again to deselect (shows all branches)
5. Preference is saved in browser (persists across page refreshes)

### For Non-Owners (Admin/Staff/Cashier)

1. Login to dashboard
2. See branch links but they are disabled (greyed out)
3. Data automatically shows only their assigned branch
4. No branch switching available

## Database Schema Requirements

All tables that need branch filtering should have a `branch` column with values:

- 'Dagupan'
- 'San Carlos'

Affected tables:

- `inventory`
- `sales`
- `online_orders` (via product relationships)
- `returns_and_refunds` (via sale/order relationships)
- `users`

## API Endpoints

### POST /api/set-branch-filter

Sets the branch filter in the user's session.

**Request:**

```json
{
    "branches": ["Dagupan", "San Carlos"]
}
```

**Response:**

```json
{
    "success": true,
    "branches": ["Dagupan"]
}
```

## Debugging

### Check Current Branch Filter

```php
dd(getBranchFilter()); // In any controller or view
```

### View Session Data

```php
dd(session('branch_filter')); // Check what's in session
```

### Check localStorage in Browser Console

```javascript
console.log(JSON.parse(localStorage.getItem("selectedBranch")));
```

## Testing

### Test 1: Owner Branch Selection

1. Login as owner@boutique.com
2. Navigate to Inventory page
3. Click "SAN CARLOS" in navbar
4. Verify only San Carlos inventory is shown
5. Click "DAGUPAN"
6. Verify only Dagupan inventory is shown
7. Click "SAN CARLOS" again (should toggle)
8. Verify both branches show

### Test 2: Non-Owner Lock

1. Login as staff.dagupan@boutique.com
2. Notice "DAGUPAN" and "SAN CARLOS" are disabled/greyed out
3. Try clicking - should not respond
4. Data shows only Dagupan inventory

### Test 3: Persistence

1. Login as owner, select "SAN CARLOS"
2. Refresh the page
3. Verify "SAN CARLOS" is still selected
4. Select "DAGUPAN", refresh
5. Verify selection persists

### Test 4: Cross-Page Filter

1. Login as owner, select "SAN CARLOS"
2. Navigate to different pages (Sales, Orders, etc.)
3. Verify filter persists across navigation
4. Data on all pages shows San Carlos only

## Future Enhancements

1. **Batch Operations**: Filter applies to batch operations (bulk delete, bulk update)
2. **Reports**: Pre-filter reports by selected branches
3. **Audit Log**: Track which branches users accessed
4. **Default Branch**: Allow users to set a default branch preference
5. **Multi-Select UI**: Add checkboxes for better UX instead of toggle links
