# RightPath LMS

A simple, elegant Learning Management System built with Laravel 11, Tailwind CSS, and Alpine.js. Designed for easy deployment on SiteGround shared hosting.

## Features

- **Course Management**: Create and manage courses with modules and lessons
- **User Authentication**: Registration, login, and password reset
- **Progress Tracking**: Track student progress through courses
- **Drip Content**: Release modules over time based on enrollment date
- **Certificates**: Auto-generate PDF certificates on course completion
- **Video Support**: Embed YouTube, Vimeo, or custom video URLs
- **Responsive Design**: Beautiful UI that works on all devices

## Requirements

- PHP 8.2 or higher
- MySQL 8.0 or MariaDB 10.6+
- Composer
- Node.js 18+ (for building assets)

## Local Development Setup

1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd RightPathPlatform
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Create environment file**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Configure your database in `.env`**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=rightpath_lms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

7. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

8. **Create storage link**
   ```bash
   php artisan storage:link
   ```

9. **Build assets**
   ```bash
   npm run build
   ```

10. **Start development server**
    ```bash
    php artisan serve
    ```

## Default Login Credentials

After running the seeder:
- **Admin**: admin@rightpath.com / password
- **Student**: student@rightpath.com / password

## SiteGround Deployment Guide

### Step 1: Prepare Your Database

1. Log in to SiteGround cPanel
2. Go to **MySQL Databases**
3. Create a new database (e.g., `username_lms`)
4. Create a database user with a strong password
5. Assign the user to the database with all privileges

### Step 2: Upload Files

1. Build assets locally:
   ```bash
   npm run build
   ```

2. Upload all files to SiteGround via SFTP or Git
3. Upload to: `public_html/rightpath-lms/` (or your desired folder)

### Step 3: Configure Environment

1. SSH into your SiteGround account or use File Manager
2. Create `.env` file in the root directory:
   ```
   APP_NAME="RightPath LMS"
   APP_ENV=production
   APP_KEY=base64:YOUR_GENERATED_KEY
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=username_lms
   DB_USERNAME=username_user
   DB_PASSWORD=your_password
   
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=sync
   ```

3. Generate the application key:
   ```bash
   php artisan key:generate
   ```

### Step 4: Run Migrations

Via SSH:
```bash
cd ~/public_html/rightpath-lms
php artisan migrate --seed
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Configure Document Root

**Option A: Subdomain Setup (Recommended)**
1. Create a subdomain (e.g., `learn.yourdomain.com`)
2. Point the document root to: `public_html/rightpath-lms/public`

**Option B: Main Domain Setup**
1. In cPanel, go to **Domains** or **Site Tools**
2. Change the document root to: `public_html/rightpath-lms/public`

### Step 6: Set Permissions

Via SSH:
```bash
cd ~/public_html/rightpath-lms
chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Step 7: SSL Certificate

SiteGround provides free SSL via Let's Encrypt:
1. Go to **Security** > **SSL Manager**
2. Enable Let's Encrypt for your domain

## Directory Structure

```
rightpath-lms/
├── app/                    # Application code
│   ├── Http/
│   │   ├── Controllers/    # Route controllers
│   │   └── Middleware/     # Custom middleware
│   ├── Models/             # Eloquent models
│   └── Services/           # Business logic services
├── bootstrap/              # Framework bootstrap
├── config/                 # Configuration files
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── public/                 # Document root
│   └── build/              # Compiled assets
├── resources/
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript
│   └── views/              # Blade templates
├── routes/                 # Route definitions
└── storage/                # Logs, cache, uploads
```

## Customization

### Adding a New Course

1. Log in as admin (admin@rightpath.com)
2. Go to Admin Dashboard > Courses
3. Click "New Course"
4. Fill in course details and publish

### Configuring Drip Content

When creating/editing a module, set the "Drip Days" field:
- `0` = Immediately available
- `7` = Available 7 days after enrollment
- `14` = Available 14 days after enrollment

### Customizing Certificate Design

Edit `resources/views/certificates/pdf.blade.php` to customize the PDF certificate design.

## Troubleshooting

### 500 Server Error
- Check `storage/logs/laravel.log` for error details
- Ensure correct permissions on `storage/` and `bootstrap/cache/`

### CSS/JS Not Loading
- Run `npm run build` and re-upload `public/build/`
- Clear browser cache

### Database Connection Error
- Verify `.env` database credentials
- Ensure MySQL user has proper privileges

## License

This project is open-sourced software licensed under the MIT license.











