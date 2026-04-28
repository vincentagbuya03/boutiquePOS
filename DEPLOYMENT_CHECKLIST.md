# BoutiquePOS - Deployment Checklist

## Pre-Deployment Requirements

### System Requirements

- [ ] PHP 8.2+ installed
- [ ] MySQL/MariaDB installed and running
- [ ] Composer installed
- [ ] Node.js and npm installed
- [ ] Git installed (optional)

### Server Preparation

- [ ] Create database: `CREATE DATABASE boutique_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`
- [ ] Configure MySQL user with appropriate permissions
- [ ] Ensure `storage/` and `bootstrap/cache/` directories are writable
- [ ] Set up proper file permissions (755 for directories, 644 for files)

## Installation Steps

### Phase 1: Dependencies

- [ ] Extract/clone project to web root
- [ ] Run `composer install`
- [ ] Run `npm install`
- [ ] Copy `.env.example` to `.env`
- [ ] Generate app key: `php artisan key:generate`

### Phase 2: Configuration

- [ ] Configure `.env` file:
    - [ ] APP_NAME=BoutiquePOS
    - [ ] APP_ENV=production
    - [ ] APP_DEBUG=false
    - [ ] APP_URL=your-domain.com
    - [ ] DB_CONNECTION=mysql
    - [ ] DB_HOST=localhost
    - [ ] DB_PORT=3306
    - [ ] DB_DATABASE=boutique_pos
    - [ ] DB_USERNAME=pos_user
    - [ ] DB_PASSWORD=secure_password

### Phase 3: Database

- [ ] Run migrations: `php artisan migrate --force`
- [ ] Verify all tables created correctly
- [ ] Test database connections

### Phase 4: Assets

- [ ] Build assets: `npm run build`
- [ ] Verify public/assets exist
- [ ] Check image upload directory: `storage/app/public/`
- [ ] Link storage: `php artisan storage:link`

### Phase 5: Initial Data

- [ ] Create admin user (see QUICK_START.md)
- [ ] Create product categories
- [ ] Add sample products
- [ ] Set up inventory for branches
- [ ] Test user login

## Pre-Production Testing

### User Access

- [ ] Admin can login
- [ ] Cashier can login
- [ ] Invalid credentials rejected
- [ ] Register functionality works
- [ ] Logout works correctly

### Product Management

- [ ] Add new product
- [ ] Edit existing product
- [ ] Delete product with cascade
- [ ] Update inventory by branch
- [ ] Upload product image
- [ ] View low stock alerts

### Sales Processing

- [ ] Create in-store sale
- [ ] Create online sale
- [ ] Inventory deducts correctly
- [ ] Transactions saved properly
- [ ] Receipt displays correctly
- [ ] Multiple items per sale works

### Orders Management

- [ ] Create online order from different platforms
- [ ] Update order status
- [ ] Status validation works
- [ ] Inventory reserves on approval
- [ ] Can view order details

### Returns Processing

- [ ] Submit return/refund request
- [ ] Status goes to "pending"
- [ ] Admin can approve return
- [ ] Refund amount calculated
- [ ] Replacement adds inventory back
- [ ] Admin can reject return

### Reporting

- [ ] Inventory report generates
- [ ] Sales report filters work
- [ ] Reports show correct totals
- [ ] Print functionality works
- [ ] Date ranges filter correctly

### API Testing

- [ ] GET /api/v1/products returns data
- [ ] GET /api/v1/products/{id} works
- [ ] POST /api/v1/orders creates order
- [ ] GET /api/v1/orders/{id}/status works
- [ ] Error responses formatted correctly

### Security

- [ ] Non-authenticated users redirected to login
- [ ] CSRF tokens on all forms
- [ ] Password fields masked
- [ ] Role restrictions enforced
- [ ] Unauthorized actions blocked

### Performance

- [ ] Dashboard loads in <2 seconds
- [ ] Sales page pagination works
- [ ] Reports generate without timeout
- [ ] No database errors in logs
- [ ] Image uploads don't timeout

## Server Optimization

### Before Production

- [ ] Run: `php artisan config:cache`
- [ ] Run: `php artisan route:cache`
- [ ] Run: `php artisan view:cache`
- [ ] Configure web server (Apache/Nginx)
- [ ] Set up SSL/TLS certificate
- [ ] Enable gzip compression
- [ ] Set up automated backups

### Caching Strategy

- [ ] Enable query caching in MySQL
- [ ] Set session driver (file or Redis)
- [ ] Configure view caching
- [ ] Clear cache regularly: `php artisan cache:clear`

## Monitoring Setup

### Logging

- [ ] Review logs in `storage/logs/`
- [ ] Set up log rotation
- [ ] Monitor error frequency
- [ ] Set up log monitoring alerts

### Database

- [ ] Set up automatic backups
- [ ] Test backup restoration
- [ ] Monitor database size
- [ ] Set query logging (development only)

### Site Health

- [ ] Monitor uptime
- [ ] Check page load times
- [ ] Monitor disk space
- [ ] Check error rates
- [ ] Monitor user activity

## Maintenance Schedule

### Daily

- [ ] Check for high-priority errors
- [ ] Verify backup completion
- [ ] Review low stock alerts
- [ ] Check pending orders

### Weekly

- [ ] Generate executive reports
- [ ] Review system logs
- [ ] Check database integrity
- [ ] Validate backups

### Monthly

- [ ] Full database backup verification
- [ ] Security audit
- [ ] Performance optimization
- [ ] User access review

### Quarterly

- [ ] Update dependencies: `composer update`
- [ ] Security patches
- [ ] Performance tuning
- [ ] Feature evaluation

## Disaster Recovery

### Backup Strategy

- [ ] Database backups: Daily (7-day retention)
- [ ] File backups: Weekly
- [ ] Offsite backup: Monthly
- [ ] Test restore procedures monthly

### Recovery Procedures

- [ ] Document database restore steps
- [ ] Document file restore steps
- [ ] Document application recovery
- [ ] Test recovery annually

### Incident Response

- [ ] Have contact list ready
- [ ] Document incident procedures
- [ ] Keep recovery guide accessible
- [ ] Post-incident review process

## User Training

### Admin Users

- [ ] Dashboard interpretation
- [ ] Report generation
- [ ] Return approval process
- [ ] User management
- [ ] System settings

### Cashier Users

- [ ] Transaction processing
- [ ] Inventory lookup
- [ ] Return submission
- [ ] Order management
- [ ] Daily closing

## Post-Deployment

### First Week

- [ ] Monitor for critical errors
- [ ] Gather user feedback
- [ ] Make configuration adjustments
- [ ] Train all staff
- [ ] Run test transactions

### First Month

- [ ] Review actual usage patterns
- [ ] Optimize database queries if needed
- [ ] Adjust reorder levels
- [ ] Refine workflows based on feedback
- [ ] Update documentation

### Ongoing

- [ ] Monthly status meetings
- [ ] Quarterly system reviews
- [ ] Annual major updates
- [ ] Continuous user support

## Troubleshooting Guide

### White Screen of Death

1. Check `.env` configuration
2. Review `storage/logs/laravel.log`
3. Verify database connection
4. Run `php artisan cache:clear`
5. Check file permissions

### 404 Errors

1. Verify route definitions
2. Clear route cache: `php artisan route:clear`
3. Check web server configuration
4. Verify public folder is set correctly

### Database Errors

1. Test MySQL connection
2. Verify credentials in `.env`
3. Check database exists
4. Run migrations: `php artisan migrate:status`
5. Check file permissions for migrations

### Permission Denied

1. Check directory permissions (755)
2. Check file permissions (644)
3. Verify web server user ownership
4. Run: `chmod -R 755 storage bootstrap/cache`

### Slow Performance

1. Clear caches: `php artisan cache:clear`
2. Run `php artisan config:cache`
3. Check database slow query log
4. Review server resource usage
5. Optimize database indexes

## Contact Information

### Support Contacts

- **System Administrator:** [Name/Email]
- **Database Administrator:** [Name/Email]
- **Business Manager:** [Name/Email]
- **IT Support Partner:** [Organization/Contact]

### Emergency Contacts

- **Critical Issue:** [Phone/Email]
- **After-Hours Support:** [Phone/Email]

## Sign-Off

- [ ] Project Manager Sign-Off: ****\_\_**** Date: ****\_\_****
- [ ] IT Lead Sign-Off: ****\_\_**** Date: ****\_\_****
- [ ] Business Owner Sign-Off: ****\_\_**** Date: ****\_\_****

## Deployment Status

- **Deployment Date:** ******\_\_\_******
- **Deployment By:** ******\_\_\_\_******
- **Live URL:** ********\_\_\_********
- **Status:** ☐ Testing ☐ Staged ☐ Production

---

**System Ready for Deployment** ✅
