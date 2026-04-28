# BoutiquePOS - Quick Start Guide

## Get Started in 5 Minutes

### 1. Run Database Migrations

```bash
php artisan migrate
```

### 2. Create First Admin User

```bash
php artisan tinker

App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@boutique.com',
    'password' => bcrypt('password123'),
    'role' => 'admin'
])
```

### 3. Start Development Server

```bash
php artisan serve
```

### 4. Login

- URL: http://localhost:8000
- Email: `admin@boutique.com`
- Password: `password123`

## Creating Test Data

### Add Categories

```bash
php artisan tinker

App\Models\Category::create(['name' => 'Shirts', 'description' => 'T-shirts and formal shirts'])
App\Models\Category::create(['name' => 'Pants', 'description' => 'Jeans and formal pants'])
App\Models\Category::create(['name' => 'Shoes', 'description' => 'All types of shoes'])
App\Models\Category::create(['name' => 'Accessories', 'description' => 'Bags, belts, etc.'])
```

### Add Sample Products

```bash
App\Models\Product::create([
    'name' => 'Classic White T-Shirt',
    'category_id' => 1,
    'price' => 499.99,
    'brand' => 'V\'s Casual',
    'size' => 'M',
    'description' => 'Premium white cotton t-shirt',
    'date_added' => now()->toDateString()
])

App\Models\Product::create([
    'name' => 'Slim Fit Jeans',
    'category_id' => 2,
    'price' => 1299.99,
    'brand' => 'V\'s Premium',
    'size' => '32',
    'description' => 'Comfortable slim fit jeans',
    'date_added' => now()->toDateString()
])
```

### Add Inventory

```bash
App\Models\Inventory::create([
    'product_id' => 1,
    'quantity' => 50,
    'branch' => 'Dagupan',
    'reorder_level' => 10,
    'last_updated' => now()->toDateString()
])

App\Models\Inventory::create([
    'product_id' => 1,
    'quantity' => 30,
    'branch' => 'San Carlos',
    'reorder_level' => 10,
    'last_updated' => now()->toDateString()
])
```

## Key System Workflows

### Daily Operations Checklist

**Morning:**

- [ ] Check dashboard for low stock items
- [ ] Review pending online orders
- [ ] Check returns awaiting approval

**Throughout Day:**

- [ ] Process in-store sales
- [ ] Update online order statuses
- [ ] Manage customer returns

**End of Day:**

- [ ] Generate daily sales report
- [ ] Verify inventory counts
- [ ] Process refunds/replacements

### Processing Your First Sale

1. Go to **Sales** menu
2. Click **New Sale**
3. Select "In-Store" as type
4. Choose your branch
5. Select payment method
6. Add items (search for "Classic White T-Shirt")
7. Set quantity: 2
8. System calculates amount: ₱999.98
9. Click "Complete Sale"
10. View receipt and transaction saved

### Creating Your First Online Order

1. Go to **Online Orders**
2. Click **New Order**
3. Fill in customer details
4. Select "Slim Fit Jeans"
5. Quantity: 1
6. Platform: Facebook
7. Delivery address
8. Order status: Pending
9. Admin approves order
10. Update to Shipped/Delivered

## Command Reference

```bash
# Development
php artisan serve                           # Start dev server
php artisan tinker                         # Interactive shell
npm run dev                                 # Watch frontend files

# Database
php artisan migrate                        # Run migrations
php artisan migrate:rollback               # Undo last migration
php artisan migrate:fresh                  # Reset and migrate (⚠️ deletes data)
php artisan db:seed                        # Seed with test data

# Cache & Optimization
php artisan cache:clear                    # Clear application cache
php artisan config:cache                   # Cache configuration
php artisan view:cache                     # Cache views

# Production
php artisan migrate --force               # Run migrations in production
npm run build                             # Build production assets
```

## Accessing Different Modules

| Module        | URL         | Required Role |
| ------------- | ----------- | ------------- |
| Dashboard     | /dashboard  | Any           |
| Inventory     | /products   | Any           |
| Sales         | /sales      | Any           |
| Online Orders | /orders     | Any           |
| Returns       | /returns    | Any           |
| Reports       | /reports/\* | Admin         |

## API Testing

### Test Product Listing

```bash
curl http://localhost:8000/api/v1/products
```

### Test Order Creation

```bash
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Content-Type: application/json" \
  -d '{
    "product_id": 1,
    "customer_name": "Test Customer",
    "customer_email": "test@example.com",
    "customer_phone": "09123456789",
    "quantity": 1,
    "delivery_address": "123 Test St",
    "platform": "Facebook"
  }'
```

## Common Tasks

### Add New Staff Member

1. Use `php artisan tinker`
2. Create user with appropriate role
3. Assign to branch (Dagupan/San Carlos)

### Update Product Price

1. Go to Inventory
2. Click product name
3. Click Edit
4. Update price field
5. Save changes

### Check Daily Sales

1. Go to Sales > View Report
2. Leave date range empty for today
3. Download or print report

### Approve Customer Return

1. Go to Returns & Refunds
2. Click pending return
3. Choose action (Refund/Replacement)
4. Set amount if refunding
5. Click Approve

## System Limits & Defaults

- **Reorder Level**: Default 5 units
- **Stock Warning**: Shows when below reorder level
- **Order Status**: Pending > Approved > Shipped > Delivered
- **Return Status**: Pending > Approved > Refunded/Replaced
- **Upload Size**: 2MB for product images
- **Pagination**: 20 records per page

## Useful Tips

- Use filter buttons to quickly find products by status
- Products with red quantity are low stock
- Always assign sales to correct branch for inventory tracking
- Run reports by period for better analysis
- Check API documentation for mobile app integration
- Use "Low Stock Only" filter in inventory reports

## Reset to Fresh State

If you want to start over with clean data:

```bash
# DANGER: This deletes all data!
php artisan migrate:fresh

# Then create admin again
php artisan tinker
App\Models\User::create([...])
```

## Need Help?

- Check error messages in browser console (F12)
- Review logs: `tail -f storage/logs/laravel.log`
- Ensure all migrations ran: `php artisan migrate:status`
- Verify database connection in `.env`

---

**Happy Selling!** 🎉
