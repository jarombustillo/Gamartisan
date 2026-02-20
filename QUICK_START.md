# ⚡ Quick Start Guide

**Get Gamartisan running in 5 minutes!**

## Prerequisites Check ✅

Make sure you have these installed:
- [ ] XAMPP
- [ ] Composer ([getcomposer.org](https://getcomposer.org/))
- [ ] Node.js ([nodejs.org](https://nodejs.org/))

---

## 🚀 Installation (Copy & Paste These Commands)

Open your terminal in `C:\xampp\htdocs\Gamartisan\` and run:

### 1️⃣ Install Dependencies
```bash
composer install
npm install
```

### 2️⃣ Setup Environment
```bash
copy .env.example .env
php artisan key:generate
```

### 3️⃣ Configure Database
Edit `.env` file:
```env
DB_DATABASE=gamartisan
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Import Database
1. Start XAMPP Control Panel
2. Start Apache + MySQL
3. Open browser: `http://localhost/phpmyadmin`
4. Create new database: `gamartisan`
5. Click "Import" → Choose `database/gamartisan.sql` → Click "Go"

### 5️⃣ Build Assets
```bash
npm run dev
```

### 6️⃣ Open Your Browser
```
http://localhost/Gamartisan/public
```

---

## ✨ You're Done!

You should now see the beautiful Gamartisan homepage!

## 🆘 Something Not Working?

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "npm not found"
Install Node.js from: https://nodejs.org/

### Error: "composer not found"
Install Composer from: https://getcomposer.org/

### Error: Database connection failed
1. Make sure MySQL is running in XAMPP
2. Check database name is `gamartisan`
3. Verify username is `root` and password is empty

### Error: Page not found
Make sure you're accessing: `http://localhost/Gamartisan/public`
(Don't forget the `/public` at the end!)

---

## 📁 Project Files

Your static HTML has been converted to:
- **Views**: `resources/views/home.blade.php`
- **Layout**: `resources/views/layouts/app.blade.php`
- **Controller**: `app/Http/Controllers/HomeController.php`
- **Routes**: `routes/web.php`
- **Styles**: `resources/css/app.css`
- **Scripts**: `resources/js/app.js`

---

## 🎯 Next Steps

1. Explore the Blade templates in `resources/views/`
2. Modify styles in `resources/css/app.css`
3. Add new routes in `routes/web.php`
4. Read **[SETUP.md](SETUP.md)** for detailed instructions
5. Read **[MIGRATION_NOTES.md](MIGRATION_NOTES.md)** to understand the migration

---

**Need Help?** Check out:
- 📖 [SETUP.md](SETUP.md) - Detailed setup guide
- 📝 [MIGRATION_NOTES.md](MIGRATION_NOTES.md) - What changed from HTML to Laravel
- 📚 [README.md](README.md) - Project overview

**Happy Coding!** 🎨✨
