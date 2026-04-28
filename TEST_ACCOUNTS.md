# Test Account Credentials

All test accounts have been seeded into the database. Use these credentials to test different roles and permissions.

**Default Password for All Test Accounts:** `password`

---

## Owner Account (Full System Access)

| Field        | Value                |
| ------------ | -------------------- |
| **Name**     | John Owner           |
| **Email**    | `owner@boutique.com` |
| **Password** | `password`           |
| **Contact**  | 09123456789          |
| **Role**     | Owner                |
| **Branch**   | All Branches         |

**Permissions:**

- ✅ Manage all users (admins, staff, cashiers)
- ✅ Manage products & inventory (all branches)
- ✅ View all sales & returns
- ✅ Access all reports
- ✅ Full system control

---

## Dagupan Branch Accounts

### Admin - Dagupan

| Field        | Value                        |
| ------------ | ---------------------------- |
| **Name**     | Maria Admin Dagupan          |
| **Email**    | `admin.dagupan@boutique.com` |
| **Password** | `password`                   |
| **Contact**  | 09187654321                  |
| **Role**     | Admin                        |
| **Branch**   | Dagupan                      |

**Permissions:**

- ✅ Manage users (staff & cashiers) of Dagupan
- ✅ View sales & returns
- ✅ Access reports
- ❌ Cannot manage products/inventory
- ❌ Cannot access other branches

---

### Staff - Dagupan

| Field        | Value                        |
| ------------ | ---------------------------- |
| **Name**     | Pedro Staff Dagupan          |
| **Email**    | `staff.dagupan@boutique.com` |
| **Password** | `password`                   |
| **Contact**  | 09198765432                  |
| **Role**     | Staff                        |
| **Branch**   | Dagupan                      |

**Permissions:**

- ✅ Create/manage products
- ✅ Manage inventory & stock
- ✅ View product quantities
- ❌ Cannot manage users
- ❌ Cannot access POS
- ❌ Cannot access other branches

---

### Cashier 1 - Dagupan

| Field        | Value                          |
| ------------ | ------------------------------ |
| **Name**     | Ana Cashier Dagupan            |
| **Email**    | `cashier.dagupan@boutique.com` |
| **Password** | `password`                     |
| **Contact**  | 09187654322                    |
| **Role**     | Cashier                        |
| **Branch**   | Dagupan                        |

**Permissions:**

- ✅ Create sales (POS)
- ✅ View products & quantities
- ✅ Create & view returns
- ❌ Cannot manage products/inventory
- ❌ Cannot manage users
- ❌ Cannot access other branches

---

### Cashier 2 - Dagupan

| Field        | Value                           |
| ------------ | ------------------------------- |
| **Name**     | Luis Cashier Dagupan            |
| **Email**    | `cashier2.dagupan@boutique.com` |
| **Password** | `password`                      |
| **Contact**  | 09187654323                     |
| **Role**     | Cashier                         |
| **Branch**   | Dagupan                         |

**Permissions:** Same as Cashier 1

---

## San Carlos Branch Accounts

### Admin - San Carlos

| Field        | Value                          |
| ------------ | ------------------------------ |
| **Name**     | Juan Admin San Carlos          |
| **Email**    | `admin.sancarlos@boutique.com` |
| **Password** | `password`                     |
| **Contact**  | 09154321098                    |
| **Role**     | Admin                          |
| **Branch**   | San Carlos                     |

**Permissions:** Same as Dagupan Admin

---

### Staff - San Carlos

| Field        | Value                          |
| ------------ | ------------------------------ |
| **Name**     | Rosa Staff San Carlos          |
| **Email**    | `staff.sancarlos@boutique.com` |
| **Password** | `password`                     |
| **Contact**  | 09165432109                    |
| **Role**     | Staff                          |
| **Branch**   | San Carlos                     |

**Permissions:** Same as Dagupan Staff

---

### Cashier 1 - San Carlos

| Field        | Value                            |
| ------------ | -------------------------------- |
| **Name**     | Carlos Cashier San Carlos        |
| **Email**    | `cashier.sancarlos@boutique.com` |
| **Password** | `password`                       |
| **Contact**  | 09176543210                      |
| **Role**     | Cashier                          |
| **Branch**   | San Carlos                       |

**Permissions:** Same as Dagupan Cashier 1

---

### Cashier 2 - San Carlos

| Field        | Value                             |
| ------------ | --------------------------------- |
| **Name**     | Stella Cashier San Carlos         |
| **Email**    | `cashier2.sancarlos@boutique.com` |
| **Password** | `password`                        |
| **Contact**  | 09176543211                       |
| **Role**     | Cashier                           |
| **Branch**   | San Carlos                        |

**Permissions:** Same as Dagupan Cashier 2

---

## How to Test

### 1. Seed the Test Accounts

```bash
php artisan db:seed --class=RoleBasedUserSeeder
```

### 2. Login with Test Account

Use any email and password from above to test the application.

### 3. Test Role-Based Access

- Try accessing features with different accounts
- Owner should have access to everything
- Admins should only see their branch
- Staff should only manage products/inventory
- Cashiers should only access POS and sales

### 4. Test Branch Isolation

- Login as Admin of Dagupan, try to access San Carlos data (should be blocked)
- Login as Owner, should see all branches

---

## Quick Copy-Paste Credentials

### Testing Different Roles:

```
Owner:        owner@boutique.com / password
Admin:        admin.dagupan@boutique.com / password
Staff:        staff.dagupan@boutique.com / password
Cashier:      cashier.dagupan@boutique.com / password
```

### Testing Branch Isolation:

```
Dagupan:      Any email with "dagupan" / password
San Carlos:   Any email with "sancarlos" / password
```

---

## Notes

- All passwords are: `password`
- Accounts persist until you reset the database
- To create new test accounts, edit `database/seeders/RoleBasedUserSeeder.php` and run migration
- Each role has specific permissions - see `RBAC_DOCUMENTATION.md` for details
