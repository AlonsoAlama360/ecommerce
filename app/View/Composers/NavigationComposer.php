<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view): void
    {
        try {
            $categories = Cache::remember('nav_categories', 3600, function () {
                return Category::active()
                    ->ordered()
                    ->select('id', 'name', 'slug', 'icon')
                    ->get();
            });
        } catch (\Exception $e) {
            $categories = collect();
        }

        $view->with('navCategories', $categories);
    }
}
