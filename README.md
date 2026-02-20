# Gamartisan

**Crafted to Give. Made to Matter.**

## 🎨 About

Gamartisan is a haven for handmade stories. A marketplace where artisans share their craft, and every piece holds the warmth of the hands that created it. We believe that creativity can spark kindness — when you bring home something crafted with heart, each order carries a gift forward to someone in need.

## 🛠️ Technology Stack

This project is built with a modern Laravel stack:

```
┌─────────────────────────────┐
│        TAILWIND CSS         │
├──────────────┬──────────────┤
│     CSS      │  JAVASCRIPT  │
├──────────────┴──────────────┤
│            HTML             │
├─────────────────────────────┤
│            BLADE            │
├─────────────────────────────┤
│           LARAVEL           │
├─────────────────────────────┤
│             PHP             │
├──────────────┬──────────────┤
│  APACHE HTTP │    MySQL     │
│    SERVER    │              │
└──────────────┴──────────────┘
```

- **Frontend**: Tailwind CSS, JavaScript, HTML
- **Templating**: Blade (Laravel's templating engine)
- **Backend Framework**: Laravel 10 (PHP 8.1+)
- **Server**: Apache HTTP Server (XAMPP)
- **Database**: MySQL

## 📋 Prerequisites

Before you begin, ensure you have:

- ✅ XAMPP (Apache + MySQL + PHP 8.1+)
- ✅ Composer (PHP dependency manager)
- ✅ Node.js & NPM (for Tailwind CSS)

## 🚀 Quick Start

1. **Install Dependencies**
```bash
composer install
npm install
```

2. **Configure Environment**
```bash
copy .env.example .env
php artisan key:generate
```

3. **Set Up Database**
   - Start XAMPP
   - Create database `gamartisan` in phpMyAdmin
   - Import `database/gamartisan.sql`

4. **Build Assets**
```bash
npm run dev
```

5. **Access the Application**
   - Open: `http://localhost/Gamartisan/public`

For detailed setup instructions, see **[SETUP.md](SETUP.md)**

## 📁 Project Structure

```
Gamartisan/
├── app/                    # Application core
│   └── Http/
│       └── Controllers/    # Controllers
├── config/                 # Configuration files
├── database/               # Database files & migrations
├── public/                 # Public assets & entry point
├── resources/              # Frontend resources
│   ├── css/               # Tailwind CSS
│   ├── js/                # JavaScript
│   └── views/             # Blade templates
├── routes/                 # Route definitions
└── vendor/                 # Composer dependencies
```

## 🌟 Features

- 🎨 Beautiful, modern UI with Tailwind CSS
- 📱 Fully responsive design
- 🛍️ Product showcase with elegant cards
- 🎯 Smooth navigation and animations
- 💚 Charity-focused marketplace concept
- 🔐 Laravel authentication ready
- 📊 MySQL database integration

## 🎯 Mission

Every handmade piece is a quiet act of charity. When you purchase from Gamartisan:
- You support independent artisans
- You help fund charitable causes
- You bring home something unique and meaningful

*"Your crafts aren't just beautiful — they're meaningful."*

## 📸 Screenshots

Visit the homepage to see:
- Hero section with compelling messaging
- Product grid with beautiful imagery
- Inspirational quote section
- About section explaining our mission
- Call-to-action for placing orders

## 🔧 Development

### Running the Application

1. Start XAMPP (Apache + MySQL)
2. Run `npm run dev` for asset compilation
3. Access `http://localhost/Gamartisan/public`

### Key Commands

```bash
# Clear caches
php artisan optimize:clear

# View routes
php artisan route:list

# Create controller
php artisan make:controller ControllerName

# Create model
php artisan make:model ModelName
```

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs/10.x)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Blade Templates](https://laravel.com/docs/10.x/blade)

## 🤝 Contributing

This is a student/learning project. Feel free to fork and experiment!

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

**Gamartisan** - Where every purchase tells a story, supports an artisan, and gives back to charity.

*Crafted with heart. Shared with love. Given with purpose.*
