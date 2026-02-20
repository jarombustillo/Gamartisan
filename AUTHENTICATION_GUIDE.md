# 🔐 Authentication System Guide

## Overview

The Gamartisan authentication system allows users to register as either **Buyers** or **Sellers**, each with their own registration forms and functionalities.

## 🎯 User Roles

### Buyer
- Browse and purchase handmade products
- Support artisans and charity causes
- Manage orders and wishlist
- Simple registration process

### Seller
- Create and manage their shop
- List products for sale
- Track sales and orders
- Build their artisan profile
- Contribute to charity with each sale

## 📋 Authentication Flow

### 1. Join Page (`/join`)
- **Route**: `{{ route('join') }}`
- **View**: `resources/views/auth/join.blade.php`
- **Description**: Role selection page where users choose between Buyer or Seller

### 2. Buyer Registration (`/register/buyer`)
- **Route**: `{{ route('register.buyer') }}`
- **View**: `resources/views/auth/register-buyer.blade.php`
- **Fields**:
  - Full Name
  - Email Address
  - Phone Number
  - Delivery Address
  - Password & Confirmation
  - Terms acceptance

### 3. Seller Registration (`/register/seller`)
- **Route**: `{{ route('register.seller') }}`
- **View**: `resources/views/auth/register-seller.blade.php`
- **Fields**:
  - Full Name / Artist Name
  - Shop / Brand Name
  - Email Address
  - Phone Number
  - Business Address
  - About Your Craft (Bio)
  - Password & Confirmation
  - Terms acceptance

### 4. Login (`/login`)
- **Route**: `{{ route('login') }}`
- **View**: `resources/views/auth/login.blade.php`
- **Fields**:
  - Email Address
  - Password
  - Remember Me (optional)

## 🛣️ Routes

```php
// Authentication Routes
Route::get('/join', [AuthController::class, 'join'])->name('join');
Route::get('/register/buyer', [AuthController::class, 'registerBuyer'])->name('register.buyer');
Route::get('/register/seller', [AuthController::class, 'registerSeller'])->name('register.seller');
Route::post('/register/buyer', [AuthController::class, 'storeBuyer'])->name('register.buyer.store');
Route::post('/register/seller', [AuthController::class, 'storeSeller'])->name('register.seller.store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

## 🎨 Design Features

### Consistent Styling
- **Color Scheme**: Forest green (#2D5A3D) as primary color
- **Typography**: Playfair Display for headings, Inter for body text
- **Components**: Rounded corners (3xl), shadow effects, hover animations
- **Forms**: Clean input fields with focus states

### User Experience
- Back buttons on all pages for easy navigation
- Clear role icons (shopping cart for buyers, artisan for sellers)
- Inline validation hints
- Success/error messaging
- Mobile-responsive design

## 🔨 Implementation Status

### ✅ Completed
- [x] Join/role selection page
- [x] Buyer registration form
- [x] Seller registration form
- [x] Login form
- [x] Routes configuration
- [x] AuthController structure
- [x] Navigation links updated

### 🚧 To Implement (Database & Logic)

#### 1. Database Migration
Create users table with role support:

```php
php artisan make:migration create_users_table
```

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('phone');
    $table->text('address');
    $table->enum('role', ['buyer', 'seller']);
    $table->string('password');
    
    // Seller-specific fields
    $table->string('shop_name')->nullable();
    $table->text('bio')->nullable();
    
    $table->timestamp('email_verified_at')->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

#### 2. User Model
Update `app/Models/User.php`:

```php
protected $fillable = [
    'name',
    'email',
    'phone',
    'address',
    'role',
    'shop_name',
    'bio',
    'password',
];

protected $hidden = [
    'password',
    'remember_token',
];

protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];

// Add role check methods
public function isBuyer()
{
    return $this->role === 'buyer';
}

public function isSeller()
{
    return $this->role === 'seller';
}
```

#### 3. Complete AuthController Methods

**storeBuyer() method:**
```php
public function storeBuyer(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'phone' => 'required|string',
        'address' => 'required|string',
        'password' => 'required|min:8|confirmed',
        'terms' => 'required|accepted',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'address' => $validated['address'],
        'role' => 'buyer',
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);

    return redirect()->route('home')->with('success', 'Welcome to Gamartisan!');
}
```

**storeSeller() method:**
```php
public function storeSeller(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'shop_name' => 'required|string|max:255|unique:users',
        'email' => 'required|email|unique:users',
        'phone' => 'required|string',
        'address' => 'required|string',
        'bio' => 'required|string',
        'password' => 'required|min:8|confirmed',
        'terms' => 'required|accepted',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'shop_name' => $validated['shop_name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'address' => $validated['address'],
        'bio' => $validated['bio'],
        'role' => 'seller',
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);

    return redirect()->route('seller.dashboard')->with('success', 'Welcome to Gamartisan!');
}
```

**login() method:**
```php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();
        
        if ($user->isSeller()) {
            return redirect()->route('seller.dashboard');
        }
        
        return redirect()->route('home');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}
```

#### 4. Add Validation Messages

Create `resources/lang/en/validation.php` for custom error messages.

#### 5. Add Flash Messages to Layout

In `resources/views/layouts/app.blade.php`, add after `@include('partials.header')`:

```blade
@if(session('success'))
    <div class="fixed top-20 right-6 z-50 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="fixed top-20 right-6 z-50 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

## 🔒 Security Features to Implement

1. **Email Verification**: Send verification email after registration
2. **Password Reset**: Implement forgot password functionality
3. **CSRF Protection**: Already enabled with `@csrf` directive
4. **Rate Limiting**: Limit login attempts
5. **Two-Factor Authentication** (Optional): For enhanced security

## 📱 Navigation Updates

The header navigation has been updated:
- **Login** link → Points to `/login`
- **Register** link → Points to `/join`

## 🎯 Next Steps

1. **Run migrations**: Create users table
   ```bash
   php artisan migrate
   ```

2. **Update User model**: Add fillable fields and role methods

3. **Implement controller logic**: Complete the TODO sections in AuthController

4. **Test registration flow**: 
   - Register as buyer
   - Register as seller
   - Login with both accounts

5. **Create dashboards**:
   - Buyer dashboard
   - Seller dashboard with shop management

6. **Add middleware**: Protect routes based on user roles

## 📚 Additional Resources

- [Laravel Authentication](https://laravel.com/docs/10.x/authentication)
- [Laravel Validation](https://laravel.com/docs/10.x/validation)
- [Blade Templates](https://laravel.com/docs/10.x/blade)

---

**Ready to authenticate! 🔐** The UI is complete, now implement the backend logic following the guides above.
