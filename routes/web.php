<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\KardexController as AdminKardexController;
use App\Http\Controllers\Admin\PurchaseController as AdminPurchaseController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\WishlistController as AdminWishlistController;
use App\Http\Controllers\Api\CategoryProductController;
use App\Http\Controllers\Api\UbigeoController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfertasController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Rutas públicas - cualquier usuario puede navegar
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog');
Route::get('/buscar', [CatalogController::class, 'search'])->name('search');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/ofertas', [OfertasController::class, 'index'])->name('ofertas');

// Carrito de compras (sesión, sin auth requerido)
Route::get('/carrito', [CartController::class, 'index'])->name('cart');
Route::post('/carrito/agregar', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/actualizar', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/eliminar', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/carrito/count', [CartController::class, 'count'])->name('cart.count');
Route::get('/carrito/items', [CartController::class, 'items'])->name('cart.items');

// Lista de deseos
Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');

// API interna (sin autenticación, para el mega menu)
Route::get('/api/categories/{slug}/products', [CategoryProductController::class, 'index']);

// API Ubigeo (departamentos, provincias, distritos)
Route::get('/api/departments', [UbigeoController::class, 'departments']);
Route::get('/api/departments/{id}/provinces', [UbigeoController::class, 'provinces']);
Route::get('/api/provinces/{id}/districts', [UbigeoController::class, 'districts']);

// Rutas de autenticación - solo para usuarios NO autenticados
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

    Route::get('/auth/facebook', [FacebookController::class, 'redirect'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [FacebookController::class, 'callback']);

    // Password Reset
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/lista-de-deseos', [WishlistController::class, 'index'])->name('wishlist.index');

    Route::get('/mi-perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/mi-perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/mi-perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/mis-pedidos', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/mis-pedidos/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');

    Route::post('/producto/{product}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// Panel de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('suppliers', AdminSupplierController::class)->except(['show']);

    // Orders (Sales)
    Route::resource('orders', AdminOrderController::class);
    Route::put('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('orders-export', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::get('orders-search-products', [AdminOrderController::class, 'searchProducts'])->name('orders.search-products');
    Route::get('orders-search-users', [AdminOrderController::class, 'searchUsers'])->name('orders.search-users');

    // Purchases (Compras a proveedores)
    Route::resource('purchases', AdminPurchaseController::class);
    Route::put('purchases/{purchase}/status', [AdminPurchaseController::class, 'updateStatus'])->name('purchases.status');
    Route::get('purchases-search-suppliers', [AdminPurchaseController::class, 'searchSuppliers'])->name('purchases.search-suppliers');
    Route::get('purchases-search-products', [AdminPurchaseController::class, 'searchProducts'])->name('purchases.search-products');

    // Kardex (Stock Movements)
    Route::get('kardex', [AdminKardexController::class, 'index'])->name('kardex.index');
    Route::get('kardex/exportar', [AdminKardexController::class, 'export'])->name('kardex.export');
    Route::get('kardex/producto/{product}', [AdminKardexController::class, 'show'])->name('kardex.show');
    Route::get('kardex/producto/{product}/exportar', [AdminKardexController::class, 'exportProduct'])->name('kardex.export-product');
    Route::post('kardex/ajuste', [AdminKardexController::class, 'adjust'])->name('kardex.adjust');
    Route::get('kardex-search-products', [AdminKardexController::class, 'searchProducts'])->name('kardex.search-products');

    // Wishlists (Lista de Deseos)
    Route::get('wishlists', [AdminWishlistController::class, 'index'])->name('wishlists.index');
    Route::get('wishlists/exportar', [AdminWishlistController::class, 'export'])->name('wishlists.export');
    Route::get('wishlists/producto/{product}', [AdminWishlistController::class, 'show'])->name('wishlists.show');
    Route::get('wishlists/producto/{product}/exportar', [AdminWishlistController::class, 'exportProduct'])->name('wishlists.export-product');

    // Reviews
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::put('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::put('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::put('reviews/{review}/featured', [AdminReviewController::class, 'toggleFeatured'])->name('reviews.featured');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Product Specifications (AJAX)
    Route::get('products/{product}/specifications', [AdminProductController::class, 'specifications'])->name('products.specifications');
    Route::put('products/{product}/specifications', [AdminProductController::class, 'updateSpecifications'])->name('products.specifications.update');

    // Product Images (API-style for AJAX)
    Route::get('products/{product}/images', [AdminProductImageController::class, 'index'])->name('products.images.index');
    Route::post('products/{product}/images', [AdminProductImageController::class, 'store'])->name('products.images.store');
    Route::put('products/{product}/images/{image}', [AdminProductImageController::class, 'update'])->name('products.images.update');
    Route::delete('products/{product}/images/{image}', [AdminProductImageController::class, 'destroy'])->name('products.images.destroy');
    Route::post('products/{product}/images/reorder', [AdminProductImageController::class, 'reorder'])->name('products.images.reorder');
});
