# SiteGround Deployment Guide

This guide provides step-by-step instructions for deploying the RightPath LMS to SiteGround shared hosting.

## Pre-Deployment Checklist

Before deploying, ensure you have:

- [ ] SiteGround hosting account with SSH access
- [ ] Domain name pointed to SiteGround
- [ ] All local changes committed
- [ ] Assets built with `npm run build`

## Quick Deployment Steps

### 1. Prepare Locally

```bash
# Install production dependencies only
composer install --no-dev --optimize-autoloader

# Build frontend assets
npm install
npm run build

# Remove development files
rm -rf node_modules
rm -rf tests
rm phpunit.xml
```

### 2. Create Database on SiteGround

1. Log into SiteGround Site Tools
2. Navigate to **Site** → **MySQL**
3. Click **Create Database**
4. Note down:
   - Database name
   - Username
   - Password

### 3. Upload Files

**Via SFTP:**
1. Connect with SFTP (credentials in Site Tools → FTP Accounts)
2. Upload to `public_html/lms/` (or your preferred folder)
3. Ensure all files including hidden files (.htaccess) are uploaded

**Via Git (Recommended):**
```bash
# On SiteGround via SSH
cd ~/public_html
git clone <your-repo> lms
cd lms
composer install --no-dev --optimize-autoloader
```

### 4. Configure Environment

Create `.env` file in the root directory:

```bash
# Via SSH on SiteGround
cd ~/public_html/lms
nano .env
```

Add these production settings:

```env
APP_NAME="RightPath LMS"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="RightPath LMS"
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Migrations

```bash
php artisan migrate --seed
```

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Set Permissions

```bash
chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 9. Cache Configuration (Production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 10. Configure Document Root

In SiteGround Site Tools:

1. Go to **Site** → **Domain**
2. Click on your domain settings
3. Change **Document Root** to: `/public_html/lms/public`

Or use a subdomain:
1. Go to **Domain** → **Subdomains**
2. Create subdomain (e.g., `learn`)
3. Set root to: `/public_html/lms/public`

## Post-Deployment

### SSL Certificate

1. Site Tools → **Security** → **SSL Manager**
2. Select your domain
3. Click **Get SSL** (Let's Encrypt)

### Cron Jobs (Optional)

For scheduled tasks, add in Site Tools → **Cron Jobs**:

```
* * * * * cd ~/public_html/lms && php artisan schedule:run >> /dev/null 2>&1
```

## Troubleshooting

### 500 Internal Server Error

1. Check error log:
   ```bash
   tail -f ~/public_html/lms/storage/logs/laravel.log
   ```

2. Ensure correct permissions:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

3. Clear all caches:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

### Database Connection Issues

1. Verify `.env` credentials match SiteGround database
2. Ensure database user has all privileges
3. Try connecting via command line:
   ```bash
   mysql -u your_user -p your_database
   ```

### Assets Not Loading

1. Verify `public/build/` directory exists with compiled assets
2. Run `npm run build` locally and re-upload `public/build/`
3. Check browser console for specific errors

### Storage/Uploads Not Working

1. Ensure storage link exists:
   ```bash
   ls -la public/storage
   ```

2. Recreate if missing:
   ```bash
   rm public/storage
   php artisan storage:link
   ```

## Updating the Application

```bash
# SSH into SiteGround
cd ~/public_html/lms

# Pull latest changes (if using Git)
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate

# Clear and rebuild caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Security Recommendations

1. **Disable Debug Mode**: Ensure `APP_DEBUG=false` in production
2. **HTTPS Only**: Force HTTPS in production
3. **Strong Passwords**: Use strong database and admin passwords
4. **Regular Updates**: Keep Laravel and packages updated
5. **Backup**: Set up regular database backups in SiteGround

## Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check PHP error logs in SiteGround Site Tools
3. Consult Laravel documentation: https://laravel.com/docs












