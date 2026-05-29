# CBS System - Quick Reference Guide

**Version:** 1.1  
**Last Updated:** May 23, 2026

---

## 🚀 Quick Start

### Development
```bash
# Install dependencies
composer install
npm install

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Build frontend assets
npm run build

# Start development server
php artisan serve

# Open in browser
http://127.0.0.1:8000/login
```

### Production Deployment
```bash
# 1. Deploy code
git pull origin main

# 2. Install dependencies
composer install --no-dev

# 3. Run migrations
php artisan migrate --force

# 4. Cache configuration
php artisan config:cache
php artisan view:cache

# Optional deployment helper
bash scripts/deploy.sh

# 5. Set permissions
chmod -R 775 storage bootstrap/cache
chmod 644 storage/*.log

# 6. Restart services
sudo systemctl restart php-fpm
sudo systemctl restart nginx
```

---

## 🔐 Security Checklist

### Before Deployment
- [ ] `.env` has `APP_DEBUG=false`
- [ ] `.env` has valid `MAIL_*` configuration
- [ ] `.env` has valid `DATABASE_*` configuration
- [ ] SSL certificate installed (HTTPS enabled)
- [ ] All migrations applied
- [ ] Database backed up

### Testing
- [ ] User registration works
- [ ] Email verification works
- [ ] Vehicle authorization enforced
- [ ] Booking conflict prevention active
- [ ] All tests passing

---

## 📊 Key Routes & Endpoints

### User Routes
```
GET  /login                 → Login form
POST /login                 → Process login
GET  /register              → Register form
POST /register              → Create account
POST /logout                → Logout
GET  /email/verify          → Verify email page
POST /email/verification-notification → Resend verification
```

### Dashboard Routes
```
GET  /dashboard             → Role-based dashboard
```

### Vehicle Routes
```
GET    /vehicles            → Browse all vehicles
GET    /vehicles/create     → Create vehicle form
POST   /vehicles            → Store vehicle
GET    /vehicles/{id}       → View vehicle details
GET    /vehicles/{id}/edit  → Edit form
PUT    /vehicles/{id}       → Update vehicle
DELETE /vehicles/{id}       → Delete vehicle
```

### Booking Routes
```
GET    /bookings            → List bookings
GET    /bookings/create/{vehicle}  → Book test drive
POST   /bookings            → Store booking
GET    /bookings/{id}       → View booking
PUT    /bookings/{id}       → Update booking status
DELETE /bookings/{id}       → Cancel booking
```

### API Routes
```
GET /api/vehicles/{id}      → Vehicle details (Rate limited: 60/min)
```

---

## 👥 Role Summary

- **Admin**: full system access, user management, reports, seller requests, payroll
- **Agent**: vehicle management, transactions, inquiries
- **Seller**: manage own vehicles and inquiries
- **Buyer**: browse vehicles, create bookings, send inquiries

---

## 🗄️ Database Schema Quick Reference

### Key Tables
```
users              - User accounts (admin, seller, buyer, agent)
sellers            - Seller profiles (linked to users)
buyers             - Buyer profiles (linked to users)
vehicles           - Vehicle listings
bookings           - Test drive bookings
transactions       - Vehicle purchases & sales
inquiries          - Vehicle inquiries
payroll_records    - Employee payroll
deductions         - Payroll deductions
```

### Important Columns
```
Vehicle:
  - status: available | reserved | sold
  - created_by: user_id (listing creator)
  - seller_id: seller_id (seller profile)
  - images: json array of image paths

Booking:
  - status: pending | confirmed | completed | cancelled
  - booking_time: datetime
  - seller_id: user_id
  - buyer_id: user_id

Transaction:
  - status: pending | completed | failed
  - sale_price: decimal (Nu.)
  - deleted_at: soft delete timestamp
```

---

## 🔑 Key Models & Relationships

### Vehicle
```php
Vehicle::class
  - belongsTo(Seller::class, 'seller_id')
  - belongsTo(User::class, 'created_by')
  - hasMany(Booking::class)
  - hasMany(Transaction::class)
  - hasMany(Inquiry::class)
```

### Booking
```php
Booking::class
  - belongsTo(Vehicle::class)
  - belongsTo(User::class, 'buyer_id')
  - belongsTo(User::class, 'seller_id')
```

### Transaction
```php
Transaction::class [SoftDeletes]
  - belongsTo(Vehicle::class)
  - belongsTo(Buyer::class)
  - belongsTo(Seller::class)
  - belongsTo(User::class, 'broker_id')
```

---

## 🚨 Authorization & Policies

### VehiclePolicy
```php
$this->authorize('view', $vehicle)      // Anyone can view available
$this->authorize('create', Vehicle::class)   // Sellers/admin/buyer
$this->authorize('update', $vehicle)    // Owner or admin only
$this->authorize('delete', $vehicle)    // Owner or admin only
```

### BookingPolicy
```php
$this->authorize('view', $booking)      // Buyer, Seller, Admin
$this->authorize('update', $booking)    // Seller or Admin
$this->authorize('delete', $booking)    // Buyer, Seller, Admin
```

---

## 📧 Email Notifications

### Triggers
```
NewBuyerRegistration     → When buyer signs up
                         → Sent to: All admin users
                         
NewSellerRegistration    → When seller is created
                         → Sent to: All admin users
                         
BookingConfirmed         → When admin confirms booking
                         → Sent to: Seller + Buyer
```

### Configuration (`.env`)
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=app-password-here
MAIL_FROM_ADDRESS=noreply@cbs.com
MAIL_FROM_NAME="CBS Platform"
```

### Testing
```bash
php artisan tinker
// send a test notification or mailable
```

---

## 💰 Financial Calculations

### Admin Revenue (0.5% Commission)
```php
$totalSales = Transaction::where('status', 'completed')->sum('sale_price');
$adminProfit = ($totalSales * 0.5) / 100;

Example:
  Total Sales: Nu. 5,000,000
  Admin Profit: Nu. 25,000 (0.5%)
```

### Booking Amount
```php
$totalAmount = $vehicle->price * $duration_hours;

Example:
  Vehicle Price: Nu. 500,000
  Duration: 2 hours
  Total Amount: Nu. 1,000,000
```

---

## 🐛 Debugging

### Enable Query Logging
```bash
php artisan tinker
DB::enableQueryLog();
// Run queries...
dd(DB::getQueryLog());
```

### Check Error Logs
```bash
tail -f storage/logs/laravel.log
```

### Test Email
```bash
php artisan tinker
Mail::raw('Test', function($msg){$msg->to('test@example.com');})->send();
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🔍 Common Issues & Solutions

### Issue: "SQLSTATE[HY000] General error: 1030"
```bash
Solution: 
  php artisan optimize
  php artisan cache:clear
```

### Issue: "Call to undefined method authorize()"
```php
Solution:
  - Check AuthServiceProvider has VehiclePolicy registered
  - Ensure trait: use Illuminate\Foundation\Auth\Access\Authorizable;
```

### Issue: "Email not sending"
```bash
Solution:
  1. Check MAIL_* vars in .env
  2. Test: php artisan mail:send
  3. Check firewall allows SMTP port
```

### Issue: "Booking can be booked twice"
```php
Solution:
  - Upgrade BookingController (already fixed)
  - Run migrations
  - Test again
```

### Issue: "User can edit other users' vehicles"
```php
Solution:
  - Check VehiclePolicy initialized in AuthServiceProvider
  - Add $this->authorize('update', $vehicle); in controller
```

---

## 📈 Performance Monitoring

### Key Metrics
```
Page Load Time:          Target < 250ms
API Response Time:       Target < 150ms
Database Query Time:     Target < 50ms
Error Rate:              Target < 0.1%
Uptime:                  Target 99.9%
```

### Monitoring Commands
```bash
# Check system resources
free -h           # Memory
df -h             # Disk space
top               # Process usage
ps aux | grep php # PHP processes

# Check service status
sudo systemctl status nginx
sudo systemctl status php-fpm
sudo systemctl status mysql
```

---

## 🔄 Maintenance Tasks

### Daily
```bash
# Check error logs
tail -n 100 storage/logs/laravel.log

# Check disk space
df -h
```

### Weekly
```bash
# Database optimization
php artisan optimize

# Clear old logs
php artisan tinker
App\Models\Log::where('created_at', '<', now()->subDays(30))->delete();
```

### Monthly
```bash
# Database backup
mysqldump -u user -p database_name > backup.sql

# Check security updates
composer update --dry-run

# Performance analysis
# - Review slow query log
# - Check database indexes
# - Verify cache hit rates
```

---

## 🚀 Scalability Recommendations

### For 1K+ Concurrent Users
```bash
1. Database replication (master-slave)
2. Query caching with Redis
3. Load balancing (nginx upstream)
4. CDN for static assets
5. Queue jobs with Redis
```

### For 100K+ Vehicles
```bash
1. Add database indexes on: status, seller_id, created_at, price
2. Implement pagination (already done)
3. Archive old transactions
4. Use database sharding for transactions
```

### For 10K+ Daily Transactions
```bash
1. Async processing with queues
2. Denormalized reporting tables
3. Event streaming for real-time analytics
4. Separate reporting database
```

---

## 📋 Compliance & Audit Trail

### Financial Records Protection
```
Transactions    - SoftDeletes enabled ✓
PayrollRecords  - SoftDeletes enabled ✓
Deductions      - SoftDeletes enabled ✓
```

### Audit Information
```
Who: tracked via created_by, updated_by fields
What: tracked via model attributes
When: tracked via created_at, updated_at, deleted_at
```

### Data Retention
```
Active Records:    Kept indefinitely
Deleted Records:   Kept 90 days minimum (can restore)
Log Files:         Kept 30 days minimum
Backups:           Kept 7 days rolling
```

---

## 🎯 Role Permissions Summary

| Feature | Admin | Seller | Buyer | Agent |
|---------|-------|--------|-------|-------|
| View all vehicles | ✓ | ✓ | ✓ | ✓ |
| Create vehicle | ✓ | ✓ | ✓ | ✗ |
| Edit any vehicle | ✓ | Own only | Own only | ✗ |
| Delete vehicle | ✓ | Own only | Own only | ✗ |
| Approve vehicle | ✓ | ✗ | ✗ | ✗ |
| View all bookings | ✓ | ✓* | Own only | ✗ |
| Confirm booking | ✓ | ✓ | ✗ | ✗ |
| View revenue | ✓ | Own | ✗ | ✗ |
| Manage sellers | ✓ | ✗ | ✗ | ✗ |
| Manage payroll | ✓ | ✗ | ✗ | ✗ |

\* Seller can only see bookings for their own vehicles

---

## 📞 Emergency Contacts

**Database Issues:**
```
1. Check: sudo systemctl status mysql
2. Restart: sudo systemctl restart mysql
3. Check logs: tail -f /var/log/mysql/error.log
```

**Web Server Issues:**
```
1. Check: sudo systemctl status nginx
2. Check config: sudo nginx -t
3. Restart: sudo systemctl restart nginx
```

**Application Issues:**
```
1. Check logs: tail -f storage/logs/laravel.log
2. Clear cache: php artisan cache:clear
3. Restart: sudo systemctl restart php-fpm
```

---

## ✅ Deployment Verification

After deploying, verify:
```bash
# 1. Application loads
curl http://localhost/
# Should return 200

# 2. Database connected
php artisan db:seed (test command only)
# Should connect without errors

# 3. Migrations applied
php artisan migrate:status
# All migrations should show 'Yes' in Batch

# 4. Cache working
php artisan cache:clear && php artisan cache:test
# Should complete without errors

# 5. Services running
sudo systemctl is-active nginx php-fpm mysql
# Should return 'active' for all
```

---

## 📚 Documentation Links

- **Full QA Report:** `PROFESSIONAL-QA-REPORT.md`
- **Audit Report:** `AUDIT-REPORT.md`
- **Implementation Summary:** `IMPLEMENTATION-SUMMARY.md`
- **System Procedures:** Previous documentation
- **Laravel Docs:** https://laravel.com/docs

---

**Last Updated:** May 21, 2026  
**Status:** ✅ Production Ready  
**Version:** 1.0
