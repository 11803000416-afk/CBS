# 🚀 CBS System - Deployment & Operations Guide

## Table of Contents
1. [Quick Start](#quick-start)
2. [Pre-Deployment Checklist](#pre-deployment-checklist)
3. [Database Backup & Recovery](#database-backup--recovery)
4. [Monitoring & Error Tracking](#monitoring--error-tracking)
5. [CI/CD Pipeline](#cicd-pipeline)
6. [Security Hardening](#security-hardening)
7. [Performance Tuning](#performance-tuning)
8. [Troubleshooting](#troubleshooting)

---

## Quick Start

### 1. Environment Setup
```bash
# Clone repository
git clone <your-repo> cbs
cd cbs

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate
php artisan db:seed

# Build frontend
npm run build

# Start development server
php artisan serve
```

### 2. Database Configuration
Update `.env` with your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cbs
DB_USERNAME=cbs_user
DB_PASSWORD=secure_password
```

---

## Pre-Deployment Checklist

### ✅ Security Checks
- [ ] `.env` file is NOT committed to git
- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production` set
- [ ] SSL certificate installed and HTTPS enforced
- [ ] Database password is strong (16+ chars)
- [ ] API keys stored in `.env`, never hardcoded
- [ ] File permissions: `storage/` and `bootstrap/cache/` are writable but not world-readable
- [ ] `.gitignore` excludes sensitive files

### ✅ Performance Optimization
- [ ] Config cached: `php artisan config:cache`
- [ ] Routes cached: `php artisan route:cache`
- [ ] Classes optimized: `composer install --optimize-autoloader`
- [ ] Assets minified: `npm run build` (not `npm run dev`)
- [ ] Debug bar disabled in production

### ✅ Database
- [ ] All migrations applied: `php artisan migrate --force`
- [ ] Database backups scheduled (see below)
- [ ] Indexes created on frequently queried columns
- [ ] Connection pooling configured (if using MySQL 8.0+)

### ✅ Application Health
- [ ] Run tests: `php artisan test`
- [ ] Check for errors: `tail -f storage/logs/laravel.log`
- [ ] Verify file uploads work
- [ ] Test 2FA setup process
- [ ] Verify email sending (if configured)

---

## Database Backup & Recovery

### Automated Daily Backup

#### Option 1: Using Provided Script
```bash
# Make backup script executable
chmod +x scripts/backup-database.sh

# Run manually
./scripts/backup-database.sh

# Add to crontab for daily backup at 2 AM
0 2 * * * cd /opt/lampp/htdocs/CBS && ./scripts/backup-database.sh
```

#### Option 2: Using Laravel Command
```bash
# Create a backup immediately
php artisan backup:run

# Schedule automatic backups (add to `app/Console/Kernel.php`):
$schedule->command('backup:run')->daily();
```

#### Option 3: Using External Tools
```bash
# MySQL dump to file
mysqldump -u cbs_user -p cbs > backup_$(date +%Y%m%d).sql

# Compress and upload to S3
gzip backup_$(date +%Y%m%d).sql
aws s3 cp backup_$(date +%Y%m%d).sql.gz s3://my-bucket/backups/
```

### Manual Backup & Restore

**Backup:**
```bash
mysqldump -h localhost -u cbs_user -p cbs > backup_2026_05_29.sql
gzip backup_2026_05_29.sql
```

**Restore:**
```bash
gunzip backup_2026_05_29.sql.gz
mysql -h localhost -u cbs_user -p cbs < backup_2026_05_29.sql
```

### Retention Policy
- Keep daily backups for 7 days
- Keep weekly backups for 4 weeks
- Keep monthly backups for 1 year
- Store offsite backups on AWS S3, Google Cloud, or similar

---

## Monitoring & Error Tracking

### Sentry Integration

#### Setup
1. Create account at [sentry.io](https://sentry.io)
2. Create new project → Select "Laravel"
3. Copy your DSN and add to `.env`:
   ```
   SENTRY_LARAVEL_DSN=https://<key>@o<id>.ingest.sentry.io/<project>
   ```
4. Install package:
   ```bash
   composer require sentry/sentry-laravel
   ```
5. Publish config:
   ```bash
   php artisan vendor:publish --provider="Sentry\\Laravel\\ServiceProvider"
   ```

#### Monitor These Events
- **Errors**: Unhandled exceptions logged automatically
- **Transactions**: Database queries, HTTP requests
- **Release Tracking**: Each deployment tagged with version
- **Performance**: Response times, slow queries

#### Dashboard Alerts
Set up these alerts in Sentry:
- Error rate > 5% in 1 hour
- Response time > 1 second average
- Failed database connections
- Authentication failures (suspicious login patterns)

### Application Logging

**Log Location:**
```
storage/logs/laravel-*.log
```

**Log Levels (Debug → Critical):**
```
DEBUG, INFO, NOTICE, WARNING, ERROR, CRITICAL, ALERT, EMERGENCY
```

**Monitor Logs:**
```bash
# Real-time log tail
tail -f storage/logs/laravel.log

# Search for errors
grep -i "error\|exception" storage/logs/laravel.log

# Parse JSON logs (if configured)
tail -f storage/logs/laravel.log | jq .
```

---

## CI/CD Pipeline

### GitHub Actions Setup

The CI/CD pipeline runs on every push and pull request:

**Jobs:**
1. **Test** - Runs PHPUnit tests
2. **Build** - Builds Vite assets
3. **Security** - Checks dependencies for vulnerabilities
4. **Deploy** - Deploys to staging/production (manual configuration)

**Workflow File:**
```
.github/workflows/ci-cd.yml
```

### Configure Secrets
Add these to GitHub repo settings → Secrets:

**For Staging:**
```
DEPLOY_KEY_STAGING        # SSH private key
DEPLOY_HOST_STAGING       # IP/hostname
DEPLOY_USER_STAGING       # SSH username
DEPLOY_PATH_STAGING       # Path on server
```

**For Production:**
```
DEPLOY_KEY_PRODUCTION     # SSH private key
DEPLOY_HOST_PRODUCTION    # IP/hostname
DEPLOY_USER_PRODUCTION    # SSH username
DEPLOY_PATH_PRODUCTION    # Path on server
```

### Manual Deployment Script
```bash
#!/bin/bash
# deploy.sh

SERVER_IP="your.server.ip"
DEPLOY_USER="deploy"
APP_PATH="/var/www/cbs"

ssh $DEPLOY_USER@$SERVER_IP "cd $APP_PATH && \
  git pull origin main && \
  composer install --no-dev --optimize-autoloader && \
  npm ci && npm run build && \
  php artisan migrate --force && \
  php artisan config:cache && \
  php artisan route:cache && \
  php artisan cache:clear && \
  php-fpm -t"

echo "✓ Deployment complete!"
```

---

## Security Hardening

### 1. HTTP Security Headers
Already configured in `SecurityHeaders` middleware:
```
X-Frame-Options: DENY              # Prevent clickjacking
X-Content-Type-Options: nosniff    # Prevent MIME sniffing
X-XSS-Protection: 1; mode=block    # XSS protection
Strict-Transport-Security: ...     # Enforce HTTPS
```

### 2. Input Sanitization
Automatic sanitization via `SanitizeInput` middleware:
- Removes null bytes
- Escapes HTML entities
- Allows safe tags (p, br, strong, em, a, etc.)
- Skips password fields

### 3. Two-Factor Authentication (2FA)
- Enabled for admin users
- Uses Google Authenticator compatible QR codes
- Backup recovery codes generated automatically

**Enable 2FA:**
```php
auth()->user()->enableTwoFactor();
```

**Verify 2FA:**
```php
auth()->user()->hasTwoFactorEnabled();
```

### 4. Rate Limiting
Configured for sensitive endpoints:
- Login: 6 attempts per minute
- Registration: 6 attempts per minute
- Transaction creation: 10 per minute
- OTP verification: 5 per minute
- Payment approval: 5 per minute

### 5. CSRF Protection
All forms protected with CSRF tokens:
```blade
@csrf
```

### 6. HTTPS Enforcement
Use in `.env`:
```
APP_URL=https://yourdomain.com
SESSION_SECURE_COOKIES=true
SESSION_HTTP_ONLY=true
```

### 7. Database Security
- Use strong passwords
- Limit database user permissions
- Enable query logging in development only
- Use parameterized queries (Eloquent does this)

### 8. File Upload Security
- Validate MIME types
- Store uploads outside web root
- Use random filenames
- Virus scan uploads (optional)

---

## Performance Tuning

### PHP Optimization
```ini
; php.ini
memory_limit = 512M
max_execution_time = 60
upload_max_filesize = 100M
post_max_size = 100M
```

### MySQL/MariaDB Optimization
```sql
-- Increase buffer pool (50-80% of RAM)
[mysqld]
innodb_buffer_pool_size = 4G

-- Increase max connections
max_connections = 1000

-- Enable query cache
query_cache_size = 256M
query_cache_type = 1
```

### Laravel Optimization
```bash
# Cache configuration
php artisan config:cache

# Optimize class loader
composer dump-autoload --optimize

# Cache routes
php artisan route:cache

# Horizon for background jobs (if using queues)
php artisan horizon
```

### Asset Optimization
```bash
# Build for production
npm run build

# Verify gzip compression
gzip -t public/build/*.js public/build/*.css

# Check file sizes
ls -lh public/build/
```

### Database Optimization
```sql
-- Add indexes on frequently queried columns
ALTER TABLE vehicles ADD INDEX (status);
ALTER TABLE transactions ADD INDEX (broker_id, status);
ALTER TABLE users ADD INDEX (email);

-- Analyze query performance
EXPLAIN SELECT * FROM vehicles WHERE status = 'available';
```

---

## Troubleshooting

### Common Issues

#### 1. "Access denied for user 'cbs_user'"
**Solution:** Verify database credentials in `.env`
```bash
mysql -h localhost -u cbs_user -p -e "SELECT 1;"
```

#### 2. "500 Internal Server Error"
**Solution:** Check logs
```bash
tail -50 storage/logs/laravel.log
php artisan config:cache  # Clear cache
php artisan cache:clear
```

#### 3. "CSRF token mismatch"
**Solution:** Clear sessions
```bash
php artisan cache:clear
php artisan session:table  # If using database sessions
php artisan migrate
```

#### 4. "Permissions denied" errors
**Solution:** Set correct permissions
```bash
sudo chown -R www-data:www-data storage/ bootstrap/cache/
sudo chmod -R 775 storage/ bootstrap/cache/
```

#### 5. "Disk space full"
**Solution:** Clean up logs and cache
```bash
php artisan tinker
>>> File::delete(glob('storage/logs/*'));
>>> Cache::flush();
```

#### 6. "Email not sending"
**Solution:** Verify configuration
```bash
php artisan tinker
>>> Mail::mailer('smtp')->raw('Test', fn($m) => $m->to('test@example.com'));
```

---

## Maintenance Windows

### Weekly
- [ ] Review error logs in Sentry
- [ ] Check database backup logs
- [ ] Monitor server disk usage
- [ ] Verify all critical services running

### Monthly
- [ ] Update dependencies: `composer update`, `npm update`
- [ ] Review security advisories
- [ ] Analyze performance metrics
- [ ] Test disaster recovery process

### Quarterly
- [ ] Review and update security policies
- [ ] Conduct penetration testing
- [ ] Update SSL certificates
- [ ] Archive old logs

---

## Contact & Support

**Security Issues:**
Report to: security@yourdomain.com

**Technical Support:**
Contact: support@yourdomain.com

**Documentation:**
- Laravel: https://laravel.com/docs
- Sentry: https://docs.sentry.io/product/
- MySQL: https://dev.mysql.com/doc/

---

**Last Updated:** May 29, 2026  
**Version:** 1.0.0  
**Maintained By:** DevOps Team
