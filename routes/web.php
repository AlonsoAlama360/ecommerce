<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\CategoryProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfertasController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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

// Rutas de autenticación - solo para usuarios NO autenticados
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Rutas que requieren autenticación
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/lista-de-deseos', [WishlistController::class, 'index'])->name('wishlist.index');

    Route::get('/mi-perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/mi-perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/mi-perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
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
    Route::get('orders-search-products', [AdminOrderController::class, 'searchProducts'])->name('orders.search-products');
    Route::get('orders-search-users', [AdminOrderController::class, 'searchUsers'])->name('orders.search-users');

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
