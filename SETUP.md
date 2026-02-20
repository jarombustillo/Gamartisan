# Gamartisan - Laravel Setup Guide

Welcome to **Gamartisan**! This guide will help you set up and run the Laravel application on your XAMPP server.

## 🎯 Technology Stack

As per your requirements:
- **Frontend**: Tailwind CSS, JavaScript, HTML
- **Templating**: Blade (Laravel's templating engine)
- **Backend Framework**: Laravel 10 (PHP)
- **Server**: Apache HTTP Server (XAMPP)
- **Database**: MySQL

## 📋 Prerequisites

Before you begin, make sure you have:

1. ✅ **XAMPP** installed (Apache + MySQL + PHP)
2. ✅ **Composer** (PHP dependency manager) - [Download here](https://getcomposer.org/)
3. ✅ **Node.js & NPM** (for Tailwind CSS) - [Download here](https://nodejs.org/)
4. ✅ **PHP 8.1 or higher**

## 🚀 Installation Steps

### Step 1: Install Dependencies

Open your terminal/command prompt in the project directory and run:

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (for Tailwind CSS)
npm install
```

### Step 2: Configure Environment

1. Copy the environment file:
```bash
copy .env.example .env
```

2. Generate application key:
```bash
php artisan key:generate
```

3. Edit the `.env` file with your XAMPP settings:
```env
APP_NAME=Gamartisan
APP_URL=http://localhost/Gamartisan/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gamartisan
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Set Up Database

1. Start XAMPP (Apache + MySQL)
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Create a new database named `gamartisan`
4. Import the SQL file:
   - Click on the `gamartisan` database
   - Go to the "Import" tab
   - Choose the file: `database/gamartisan.sql`
   - Click "Go"

### Step 4: Configure Apache for Laravel

**Option A: Access via public folder (Recommended for XAMPP)**

Access your site at: `http://localhost/Gamartisan/public`

**Option B: Configure Virtual Host (Advanced)**

1. Open `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Add this configuration:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/Gamartisan/public"
    ServerName gamartisan.local
    
    <Directory "C:/xampp/htdocs/Gamartisan/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Open `C:\Windows\System32\drivers\etc\hosts` (as Administrator)
4. Add this line:
```
127.0.0.1 gamartisan.local
```

5. Restart Apache in XAMPP
6. Access your site at: `http://gamartisan.local`

### Step 5: Build Frontend Assets

For **development** with Tailwind CSS:
```bash
npm run dev
```

For **production**:
```bash
npm run build
```

## 🎨 Using Tailwind CSS

Currently, the project uses Tailwind CSS via CDN (in the Blade layout). To use the compiled version:

1. In `resources/views/layouts/app.blade.php`, replace the CDN script:
   
   Remove:
   ```html
   <script src="https://cdn.tailwindcss.com"></script>
   ```
   
   Add:
   ```blade
   @vite(['resources/css/app.css', 'resources/js/app.js'])
   ```

2. Run `npm run dev` or `npm run build`

## 📁 Project Structure

```
Gamartisan/
├── app/
│   └── Http/
│       └── Controllers/
│           └── HomeController.php
├── bootstrap/
│   └── app.php
├── config/
│   ├── app.php
│   └── database.php
├── database/
│   └── gamartisan.sql
├── public/
│   ├── index.php
│   ├── .htaccess
│   └── uploads/
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── partials/
│       │   ├── header.blade.php
│       │   └── footer.blade.php
│       └── home.blade.php
├── routes/
│   ├── web.php
│   └── console.php
├── .htaccess
├── artisan
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
└── README.md
```

## 🔧 Common Issues & Solutions

### Issue: "No such file or directory" errors

**Solution**: Make sure you've run `composer install` first.

### Issue: "Permission denied" errors

**Solution**: On Windows with XAMPP, you might need to run your terminal as Administrator.

### Issue: Page shows Laravel default page

**Solution**: Clear the configuration cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Issue: Styles not loading

**Solution**: 
1. Make sure you've run `npm install`
2. Run `npm run dev` or `npm run build`
3. Check that the `public` folder has write permissions

### Issue: Database connection error

**Solution**:
1. Verify MySQL is running in XAMPP
2. Check your `.env` file has correct database credentials
3. Make sure the `gamartisan` database exists

## 🌐 Routes

- **Home**: `http://localhost/Gamartisan/public/`
- **Products**: `http://localhost/Gamartisan/public/products` (or use anchor #products)
- **About**: `http://localhost/Gamartisan/public/about` (or use anchor #about)

## 📝 Next Steps

1. ✅ Install Laravel dependencies
2. ✅ Configure `.env` file
3. ✅ Import database
4. ✅ Build frontend assets
5. ✅ Start developing!

## 🛠️ Development Workflow

### Running the Application

1. Start XAMPP (Apache + MySQL)
2. Run `npm run dev` (for Tailwind CSS hot reload during development)
3. Open your browser: `http://localhost/Gamartisan/public`

### Making Changes

- **Backend**: Edit controllers in `app/Http/Controllers/`
- **Frontend**: Edit Blade templates in `resources/views/`
- **Styles**: Edit `resources/css/app.css` (Tailwind CSS)
- **Scripts**: Edit `resources/js/app.js`
- **Routes**: Edit `routes/web.php`

### Useful Artisan Commands

```bash
# Clear all caches
php artisan optimize:clear

# View routes
php artisan route:list

# Run migrations (when you create them)
php artisan migrate

# Create a new controller
php artisan make:controller ControllerName

# Create a new model
php artisan make:model ModelName
```

## 📚 Laravel Resources

- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Laravel Blade Templates](https://laravel.com/docs/10.x/blade)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

## 🎉 Success!

If everything is set up correctly, you should see the Gamartisan homepage with:
- Beautiful hero section
- Product cards
- Navigation menu
- Footer

---

**Happy Coding! 🚀**

*Crafted to Give. Made to Matter.*
