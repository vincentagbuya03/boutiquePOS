# BoutiquePOS - Sales and Inventory Management System

A comprehensive point-of-sale (POS) and inventory management system designed for V's Fashion Boutique with support for multiple branches and online selling.

## System Features

### Core Modules

1. **Dashboard** - Overview of business metrics
    - Total sales and today's sales
    - Online orders statistics
    - Low stock alerts
    - Sales trends (7 days)
    - Monthly sales summary

2. **Inventory Management**
    - Product catalog management (add, edit, delete)
    - Multi-branch inventory tracking (Dagupan & San Carlos)
    - Stock reorder level management
    - Low stock warnings
    - Product categorization

3. **Sales Processing**
    - In-store sales transactions
    - Online order integration
    - Multiple payment methods (Cash, Card, Online Transfer)
    - Quick transaction entry
    - Sales history and tracking

4. **Online Orders Management**
    - Order creation from multiple platforms (Facebook, TikTok, Website)
    - Order status tracking (Pending, Approved, Shipped, Delivered, Cancelled)
    - Inventory deduction on approval
    - Customer information management
    - Delivery date tracking

5. **Returns & Refunds Management**
    - Product return recording (damaged, defective, etc.)
    - Refund processing
    - Replacement handling
    - Store credit option
    - Admin approval workflow

6. **Business Reports**
    - Inventory reports with stock valuation
    - Sales reports by period (daily, weekly, monthly)
    - Profit analysis
    - Returns and refunds tracking
    - Print-friendly reports

### User Roles

- **Admin/Owner** - Full system access, report generation, return approval
- **Cashier** - Sales processing, order management, returns submission

## Installation & Setup

### Prerequisites

- PHP 8.2 or higher
- Laravel 11
- MySQL/MariaDB
- Composer
- Node.js & NPM

### Step 1: Install Dependencies

```bash
cd /path/to/Boutique-Pos
composer install
npm install
```

### Step 2: Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit `.env` file and configure:

- Database connection (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Application name and URL

### Step 3: Setup Database

Run migrations to create all necessary tables:

```bash
php artisan migrate
```

**Tables Created:**

- users - Staff accounts with roles
- categories - Product categories
- products - Product information
- inventory - Stock tracking by branch
- sales - Sales transactions
- sale_items - Sales line items
- online_orders - Online order records
- returns_and_refunds - Return management
- password_reset_tokens - Password recovery
- sessions - User sessions

### Step 4: Create Admin User (Optional)

```bash
php artisan tinker

# In tinker console:
>>> App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@boutique.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
    'branch' => 'Dagupan'
])
```

### Step 5: Build Frontend Assets

```bash
npm run build    # Production
npm run dev      # Development with watch
```

### Step 6: Start Application

```bash
# Option 1: Using Laravel development server
php artisan serve

# Then access at http://localhost:8000
```

## Usage Guide

### Login

- Navigate to `/login`
- Use registered email and password
- Redirects to dashboard on successful authentication

### Recording a Sale

1. Navigate to **Sales** > **New Sale**
2. Select sale type (In-store/Online) and branch
3. Choose payment method
4. Add items by selecting products and quantities
5. System automatically calculates totals
6. Click "Complete Sale" to process

### Managing Inventory

1. Go to **Inventory Management**
2. View all products and current stock levels
3. Click **View** to see details by branch
4. Update stock quantities as needed
5. Monitor items below reorder level (marked in red)

### Processing Online Orders

1. Navigate to **Online Orders**
2. **Create New Order** with customer details
3. Track order status from Pending to Delivered
4. Update status as order progresses
5. Manage returns if customer reports issues

### Handling Returns

1. Go to **Returns & Refunds** > **New Return**
2. Select product and quantity returned
3. Specify reason (Damaged, Defective, etc.)
4. Admin reviews and approves/rejects returns
5. Process refunds or replacements accordingly

### Generating Reports

1. Navigate to **Reports** section
2. Choose report type:
    - **Inventory Report** - Stock levels and valuation
    - **Sales Report** - Sales by period
    - **Returns Report** - Return tracking
3. Apply filters (date range, branch, etc.)
4. View or print reports as PDF

## API Endpoints

The system includes REST API endpoints for external integrations:

### Base URL

```
http://localhost:8000/api/v1
```

### Endpoints

**Get All Products**

```
GET /products
Returns: List of all available products with prices and stock status
```

**Get Specific Product**

```
GET /products/{id}
Returns: Detailed product information including inventory
```

**Create Online Order**

```
POST /orders
Body: {
    "product_id": 1,
    "customer_name": "John Doe",
    "customer_email": "john@example.com",
    "customer_phone": "09123456789",
    "quantity": 2,
    "delivery_address": "123 Main St, City",
    "platform": "Facebook"
}
Returns: Order confirmation with order ID and total amount
```

**Check Order Status**

```
GET /orders/{order_id}/status
Returns: Current order status and delivery information
```

## System Architecture

### Database Schema

**Products** - Master product catalog
**Categories** - Product classification
**Inventory** - Stock levels per branch per product
**Sales** - Transaction records
**SaleItems** - Line items in each transaction
**OnlineOrders** - Customer orders from platforms
**ReturnsAndRefunds** - Return and refund records
**Users** - Staff accounts with role-based access

### Key Features

- **Multi-branch Support** - Separate inventory tracking for Dagupan and San Carlos
- **Role-based Access Control** - Admin and Cashier permissions
- **Real-time Inventory Updates** - Stock automatically adjusted on sales
- **Damage Management** - Systematic handling of product returns
- **Comprehensive Reporting** - Business analytics and performance metrics
- **Mobile-Friendly API** - Integration with mobile apps and external platforms

## Security Features

- User authentication with hashed passwords
- Role-based authorization controls
- CSRF protection on all forms
- Server-side input validation
- Secure database transactions
- User session management

## Troubleshooting

### Database Connection Issues

- Verify MySQL is running
- Check `.env` database credentials
- Ensure database exists: `CREATE DATABASE boutique_pos;`

### Migration Errors

- Clear application cache: `php artisan cache:clear`
- Run migrations fresh: `php artisan migrate:fresh` (Warning: deletes all data)

### Asset Loading Issues

- Rebuild assets: `npm run build`
- Clear browser cache
- Check file permissions in `public/` directory

### Login Issues

- Verify user exists in database
- Check password is correctly hashed
- Clear sessions: `php artisan cache:clear`

## Support & Maintenance

### Regular Tasks

- Monitor low stock items daily
- Review pending orders and returns
- Generate daily/weekly sales reports
- Backup database regularly

### Performance Optimization

- Index frequently queried fields
- Archive old transactions periodically
- Optimize image sizes
- Monitor application logs

## Future Enhancements

- Mobile app (iOS/Android) for field sales
- Advanced analytics dashboard
- Barcode/QR code integration
- Multi-currency support
- Supplier management
- Employee time tracking
- Customer loyalty program
- SMS/Email notifications

## License

This system is proprietary software for V's Fashion Boutique.

## Support

For technical support or feature requests, contact the IT Support Partner.
