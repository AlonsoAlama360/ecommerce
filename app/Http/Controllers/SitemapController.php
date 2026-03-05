<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = Cache::remember('sitemap_xml', 3600, function () {
            $products = Product::where('is_active', true)
                ->select('slug', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->get();

            $categories = Category::where('is_active', true)
                ->select('slug', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->get();

            $baseUrl = rtrim(config('app.url'), '/');

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            // Home
            $xml .= $this->buildUrl($baseUrl . '/', now()->toW3cString(), 'daily', '1.0');

            // Catálogo
            $xml .= $this->buildUrl($baseUrl . '/catalogo', now()->toW3cString(), 'daily', '0.9');

            // Ofertas
            $xml .= $this->buildUrl($baseUrl . '/ofertas', now()->toW3cString(), 'daily', '0.9');

            // Búsqueda
            $xml .= $this->buildUrl($baseUrl . '/buscar', now()->toW3cString(), 'weekly', '0.5');

            // Productos dinámicos
            foreach ($products as $product) {
                $xml .= $this->buildUrl(
                    $baseUrl . '/producto/' . $product->slug,
                    $product->updated_at->toW3cString(),
                    'weekly',
                    '0.8'
                );
            }

            // Catálogo filtrado por categoría
            foreach ($categories as $category) {
                $xml .= $this->buildUrl(
                    $baseUrl . '/catalogo?categories[]=' . $category->slug,
                    $category->updated_at->toW3cString(),
                    'weekly',
                    '0.7'
                );
            }

            // Páginas legales e informativas
            $staticPages = [
                ['/contacto', 'monthly', '0.6'],
                ['/terminos-y-condiciones', 'yearly', '0.4'],
                ['/politica-cambios-devoluciones', 'yearly', '0.4'],
                ['/preguntas-frecuentes', 'monthly', '0.5'],
                ['/libro-de-reclamaciones', 'monthly', '0.3'],
            ];

            foreach ($staticPages as [$path, $changefreq, $priority]) {
                $xml .= $this->buildUrl($baseUrl . $path, null, $changefreq, $priority);
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    private function buildUrl(string $loc, ?string $lastmod, string $changefreq, string $priority): string
    {
        $url = "  <url>\n";
        $url .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
        if ($lastmod) {
            $url .= "    <lastmod>{$lastmod}</lastmod>\n";
        }
        $url .= "    <changefreq>{$changefreq}</changefreq>\n";
        $url .= "    <priority>{$priority}</priority>\n";
        $url .= "  </url>\n";

        return $url;
    }
}
