<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BuyerController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CharityController;
use App\Http\Controllers\CheckoutController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [HomeController::class, 'products'])->name('products');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/products/{product}', [HomeController::class, 'showProduct'])->name('products.show');

// Checkout Routes (Buyer or Artist)
Route::middleware('user.auth')->group(function () {
    Route::get('/checkout/{product}', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout/{product}', [CheckoutController::class, 'store'])->name('checkout.store');
});


// Authentication Routes
Route::get('/join', [AuthController::class, 'join'])->name('join');
Route::get('/register/buyer', [AuthController::class, 'registerBuyer'])->name('register.buyer');
Route::get('/register/seller', [AuthController::class, 'registerSeller'])->name('register.seller');
Route::post('/register/buyer', [AuthController::class, 'storeBuyer'])->name('register.buyer.store');
Route::post('/register/seller', [AuthController::class, 'storeSeller'])->name('register.seller.store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Authentication - redirect to main login
    Route::get('/login', function () {
        return redirect()->route('login');
    })->name('login');
    Route::post('/login', function () {
        return redirect()->route('login');
    })->name('login.store');
    
    // Protected Admin Routes
    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        
        // Resource Routes
        Route::resource('buyers', BuyerController::class);
        Route::resource('artists', ArtistController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
        Route::resource('categories', CategoryController::class);
        Route::resource('charities', CharityController::class);
        
        // Toggle status routes for suspend/reactivate
        Route::post('buyers/{buyer}/toggle-status', [BuyerController::class, 'toggleStatus'])->name('buyers.toggle-status');
        Route::post('artists/{artist}/toggle-status', [ArtistController::class, 'toggleStatus'])->name('artists.toggle-status');

        // Charity Donation Reports
        Route::get('charities-summary', [CharityController::class, 'donationSummary'])->name('charities.summary');
        Route::get('charities-reports', [CharityController::class, 'impactReports'])->name('charities.reports');
    });
});

// Buyer Dashboard Routes
use App\Http\Controllers\Buyer\BuyerDashboardController;

Route::prefix('buyer')->name('buyer.')->middleware('user.auth:buyer')->group(function () {
    Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [BuyerDashboardController::class, 'orders'])->name('orders');
    Route::get('/profile', [BuyerDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [BuyerDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [BuyerDashboardController::class, 'updatePassword'])->name('password.update');
    Route::get('/transactions', [BuyerDashboardController::class, 'transactions'])->name('transactions');
});

// Artist Dashboard Routes
use App\Http\Controllers\Artist\ArtistDashboardController;

Route::prefix('artist')->name('artist.')->middleware('user.auth:artist')->group(function () {
    Route::get('/dashboard', [ArtistDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ArtistDashboardController::class, 'products'])->name('products');
    Route::get('/products/create', [ArtistDashboardController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [ArtistDashboardController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [ArtistDashboardController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [ArtistDashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [ArtistDashboardController::class, 'destroyProduct'])->name('products.destroy');
    Route::get('/orders', [ArtistDashboardController::class, 'orders'])->name('orders');
    Route::put('/orders/{order}/status', [ArtistDashboardController::class, 'updateOrderStatus'])->name('orders.status');
    Route::get('/profile', [ArtistDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [ArtistDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [ArtistDashboardController::class, 'updatePassword'])->name('password.update');
    Route::get('/transactions', [ArtistDashboardController::class, 'transactions'])->name('transactions');
});
