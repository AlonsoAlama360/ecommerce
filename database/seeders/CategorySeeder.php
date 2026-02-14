<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Collares',
                'slug' => 'collares',
                'description' => 'Collares artesanales con diseños románticos y elegantes',
                'icon' => 'fas fa-gem',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pulseras',
                'slug' => 'pulseras',
                'description' => 'Pulseras delicadas que expresan amor y conexión',
                'icon' => 'fas fa-ring',
                'sort_order' => 2,
            ],
            [
                'name' => 'Anillos',
                'slug' => 'anillos',
                'description' => 'Anillos de compromiso y ocasiones especiales',
                'icon' => 'fas fa-circle-notch',
                'sort_order' => 3,
            ],
            [
                'name' => 'Flores Eternas',
                'slug' => 'flores-eternas',
                'description' => 'Rosas preservadas que duran para siempre',
                'icon' => 'fas fa-seedling',
                'sort_order' => 4,
            ],
            [
                'name' => 'Luces LED',
                'slug' => 'luces-led',
                'description' => 'Luces decorativas LED con diseños románticos',
                'icon' => 'fas fa-lightbulb',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
