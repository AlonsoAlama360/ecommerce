<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
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
});
