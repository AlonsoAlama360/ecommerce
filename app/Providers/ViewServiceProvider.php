<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;
// use Illuminate\View\View;
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
        // Compartir categorías con el layout
        View::composer('layouts.app', function ($view) {
            $navCategories = Category::active()
                ->ordered()
                ->select('id', 'name', 'slug', 'icon')
                ->get();

            $view->with('navCategories', $navCategories);
        });
    }
}
