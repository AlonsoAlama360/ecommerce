<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Compartir categorías con el layout (cached 1 hour)
        View::composer('layouts.app', function ($view) {
            $navCategories = Cache::remember('nav_categories', 3600, function () {
                return Category::active()
                    ->ordered()
                    ->select('id', 'name', 'slug', 'icon')
                    ->get();
            });

            $view->with('navCategories', $navCategories);
        });

        // Compartir settings del sitio con todas las vistas
        View::composer('*', function ($view) {
            $view->with('settings', SiteSetting::allCached());
        });
    }
}
