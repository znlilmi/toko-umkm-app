<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MerchantDashboardController;
use App\Http\Controllers\MerchantOrderController;
use App\Http\Controllers\MerchantProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public product catalog
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Auth Routes (all authenticated users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard Redirect based on Role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'merchant') {
            return redirect()->route('merchant.dashboard');
        }
        return redirect()->route('products.index');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Addresses (all roles)
    Route::resource('addresses', AddressController::class)
        ->except(['show']);

    /*
    |----------------------------------------------------------------------
    | Customer Routes
    |----------------------------------------------------------------------
    */
    Route::middleware(['role:customer,merchant'])->group(function () {

        // Shopping Cart
        Route::resource('cart', CartController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
        Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

        // Orders (checkout, history, tracking, confirm delivery)
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/checkout', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

        // Payment (upload proof of payment)
        Route::get('/orders/{order}/payment', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/orders/{order}/payment', [PaymentController::class, 'store'])->name('payments.store');

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    });

    // Shop registration (customer intent to become merchant)
    Route::middleware(['role:customer'])->group(function () {
        Route::get('/shop/register', [ShopController::class, 'create'])->name('shop.create');
        Route::post('/shop/register', [ShopController::class, 'store'])->name('shop.store');
    });

    /*
    |----------------------------------------------------------------------
    | Merchant Routes  (prefix: /merchant)
    |----------------------------------------------------------------------
    */
    Route::prefix('merchant')->name('merchant.')->middleware(['role:merchant'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [MerchantDashboardController::class, 'index'])->name('dashboard');

        // Shop settings
        Route::get('/shop', [ShopController::class, 'edit'])->name('shop.edit');
        Route::patch('/shop', [ShopController::class, 'update'])->name('shop.update');

        // Product management (CRUD)
        Route::resource('products', MerchantProductController::class)
            ->except(['show']);

        // Order management
        Route::get('/orders', [MerchantOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [MerchantOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/accept', [MerchantOrderController::class, 'accept'])->name('orders.accept');
        Route::patch('/orders/{order}/ship', [MerchantOrderController::class, 'ship'])->name('orders.ship');
        Route::patch('/orders/{order}/cancel', [MerchantOrderController::class, 'cancel'])->name('orders.cancel');

        // Inventory / Stock management
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/{product}', [InventoryController::class, 'show'])->name('inventory.show');
        Route::post('/inventory/{product}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    });

    /*
    |----------------------------------------------------------------------
    | Admin Routes  (prefix: /admin)
    |----------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Category management (CRUD)
        Route::resource('categories', AdminCategoryController::class);

        // Shop moderation
        Route::get('/shops', [AdminShopController::class, 'index'])->name('shops.index');
        Route::get('/shops/{shop}', [AdminShopController::class, 'show'])->name('shops.show');
        Route::patch('/shops/{shop}/verify', [AdminShopController::class, 'verify'])->name('shops.verify');
        Route::patch('/shops/{shop}/reject', [AdminShopController::class, 'reject'])->name('shops.reject');
        Route::patch('/shops/{shop}/suspend', [AdminShopController::class, 'suspend'])->name('shops.suspend');
    });
});

require __DIR__ . '/auth.php';
