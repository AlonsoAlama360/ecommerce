<?php

namespace App\Providers;

use App\View\Composers\NavigationComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $this->configureRateLimiting();
    }

    private function configureRateLimiting(): void
    {
        // Login: 5 intentos por minuto por IP
        RateLimiter::for('login', function (Request $request) {
            $key = $request->input('email', '') . '|' . $request->ip();
            return Limit::perMinute(5)->by($key)->response(function () {
                return back()->withErrors([
                    'email' => 'Demasiados intentos de inicio de sesión. Intenta de nuevo en un minuto.',
                ]);
            });
        });

        // Registro: 3 por hora por IP
        RateLimiter::for('register', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response(function () {
                return back()->withErrors([
                    'email' => 'Demasiados intentos de registro. Intenta más tarde.',
                ]);
            });
        });

        // Password reset: 3 por hora por IP
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response(function () {
                return back()->withErrors([
                    'email' => 'Demasiados intentos. Intenta más tarde.',
                ]);
            });
        });

        // Formularios públicos (contacto, reclamos, newsletter): 5 por hora por IP
        RateLimiter::for('forms', function (Request $request) {
            return Limit::perHour(5)->by($request->ip());
        });

        // Carrito: 30 acciones por minuto por IP
        RateLimiter::for('cart', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // API interna: 60 por minuto por IP
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Reviews: 10 por hora por usuario
        RateLimiter::for('reviews', function (Request $request) {
            return Limit::perHour(10)->by($request->user()?->id ?: $request->ip());
        });

        // Wishlist toggle: 30 por minuto por usuario
        RateLimiter::for('wishlist', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        // Global web: 120 por minuto por IP
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });
    }
}
