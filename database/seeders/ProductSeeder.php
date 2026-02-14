<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = $this->getProductsData();

        foreach ($products as $categorySlug => $items) {
            $category = Category::where('slug', $categorySlug)->first();

            foreach ($items as $item) {
                $images = $item['images'];
                unset($item['images']);

                $item['category_id'] = $category->id;
                $product = Product::create($item);

                foreach ($images as $index => $imageUrl) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $imageUrl,
                        'alt_text' => $product->name,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }
        }
    }

    private function getProductsData(): array
    {
        return [
            'collares' => [
                [
                    'name' => 'Collar Corazón Infinito',
                    'slug' => 'collar-corazon-infinito',
                    'short_description' => 'Elegante collar con dije de corazón infinito en oro rosa 18k',
                    'description' => 'Expresa tu amor eterno con este elegante collar de corazón infinito. Fabricado en oro de 18k con acabado pulido, este diseño atemporal simboliza un amor sin fin. Perfecto para regalar en aniversarios, San Valentín o cualquier ocasión especial.',
                    'price' => 129.99,
                    'sale_price' => 89.99,
                    'sku' => 'COL-001',
                    'stock' => 25,
                    'material' => 'Oro Rosa 18k',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/736x/05/f1/89/05f189b8862acc9f463bfa53af869d85.jpg',
                        'https://i.pinimg.com/736x/6e/69/3c/6e693cb8a3510566ad1d3d578b3129a1.jpg',
                        'https://i.pinimg.com/736x/37/82/3a/37823a2e13fd33b214058254e987b51f.jpg',
                        'https://i.pinimg.com/1200x/32/b7/74/32b774b0b008ed9eb3d2ee34772e2b39.jpg',
                    ],
                ],
                [
                    'name' => 'Collar Perla Clásico',
                    'slug' => 'collar-perla-clasico',
                    'short_description' => 'Collar con perla natural cultivada en plata 925',
                    'description' => 'Un clásico reinventado con elegancia moderna. Este collar presenta una perla natural cultivada montada en plata 925, ideal para un look sofisticado y atemporal.',
                    'price' => 99.99,
                    'sale_price' => null,
                    'sku' => 'COL-002',
                    'stock' => 18,
                    'material' => 'Plata 925',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/736x/6b/63/c7/6b63c7a1ac26edcd6ab4eeabecf2831a.jpg',
                        'https://i.pinimg.com/1200x/6e/6b/1f/6e6b1ff4f23c2a3221cbce5ad2960ca8.jpg',
                    ],
                ],
                [
                    'name' => 'Collar Diamante Luna',
                    'slug' => 'collar-diamante-luna',
                    'short_description' => 'Collar con dije de luna y micro diamantes',
                    'description' => 'Deslumbra con este collar de luna creciente adornado con micro diamantes. Un diseño celestial que captura la magia de la noche estrellada.',
                    'price' => 189.99,
                    'sale_price' => 149.99,
                    'sku' => 'COL-003',
                    'stock' => 12,
                    'material' => 'Oro Blanco 18k',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/1200x/a9/80/b0/a980b0f4e57cbd83f43326d451f5ddbd.jpg',
                        'https://i.pinimg.com/1200x/03/76/49/0376494356dd809c425be2f051e63155.jpg',
                    ],
                ],
                [
                    'name' => 'Collar Estrella Fugaz',
                    'slug' => 'collar-estrella-fugaz',
                    'short_description' => 'Collar con dije de estrella fugaz con zirconia',
                    'description' => 'Pide un deseo con este collar de estrella fugaz. Adornado con zirconia cúbica de alta calidad que brilla como una verdadera estrella en el cielo nocturno.',
                    'price' => 79.99,
                    'sale_price' => 67.49,
                    'sku' => 'COL-004',
                    'stock' => 30,
                    'material' => 'Plata 925 bañada en oro',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/1200x/0a/5c/e8/0a5ce86466bc5e5cd94bccbe872c298d.jpg',
                    ],
                ],
            ],
            'pulseras' => [
                [
                    'name' => 'Pulsera Amor Eterno',
                    'slug' => 'pulsera-amor-eterno',
                    'short_description' => 'Pulsera con charm de corazón en plata 925',
                    'description' => 'Esta pulsera simboliza el amor que trasciende el tiempo. Con un delicado charm de corazón en plata 925, es el complemento perfecto para cualquier outfit romántico.',
                    'price' => 64.99,
                    'sale_price' => 55.24,
                    'sku' => 'PUL-001',
                    'stock' => 35,
                    'material' => 'Plata 925',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/1200x/09/26/49/092649e7126954d695440adcfd78db80.jpg',
                    ],
                ],
                [
                    'name' => 'Pulsera Cadena Dorada',
                    'slug' => 'pulsera-cadena-dorada',
                    'short_description' => 'Pulsera de eslabones finos en oro 14k',
                    'description' => 'Elegancia pura en esta pulsera de eslabones finos fabricada en oro de 14 quilates. Ligera y sofisticada para uso diario.',
                    'price' => 119.99,
                    'sale_price' => null,
                    'sku' => 'PUL-002',
                    'stock' => 20,
                    'material' => 'Oro 14k',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/736x/a2/d8/7e/a2d87e5cb3ac8f2a2fa60dc0c8d2e923.jpg',
                    ],
                ],
                [
                    'name' => 'Pulsera Charm Corazones',
                    'slug' => 'pulsera-charm-corazones',
                    'short_description' => 'Pulsera con múltiples charms de corazón',
                    'description' => 'Llena de detalles especiales, esta pulsera cuenta con múltiples charms de corazón que tintinean delicadamente al caminar. Cada charm representa un momento especial.',
                    'price' => 74.99,
                    'sale_price' => 59.99,
                    'sku' => 'PUL-003',
                    'stock' => 22,
                    'material' => 'Plata 925 con baño de oro rosa',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/736x/8c/f0/b4/8cf0b434f7e6b2df5e78543e891a75f4.jpg',
                    ],
                ],
                [
                    'name' => 'Pulsera Infinito Cristal',
                    'slug' => 'pulsera-infinito-cristal',
                    'short_description' => 'Pulsera con símbolo infinito y cristales Swarovski',
                    'description' => 'El símbolo del infinito adornado con cristales Swarovski auténticos. Una pieza que combina significado profundo con brillo excepcional.',
                    'price' => 89.99,
                    'sale_price' => 69.99,
                    'sku' => 'PUL-004',
                    'stock' => 15,
                    'material' => 'Acero inoxidable con cristales',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/736x/3c/e6/ef/3ce6ef26e18ae6e1de1e0de1c8dff99e.jpg',
                    ],
                ],
            ],
            'anillos' => [
                [
                    'name' => 'Anillo Compromiso Elegante',
                    'slug' => 'anillo-compromiso-elegante',
                    'short_description' => 'Anillo solitario con diamante central de 0.5ct',
                    'description' => 'El anillo de compromiso perfecto. Presenta un diamante solitario de 0.5 quilates montado en oro blanco de 18k con un diseño clásico que nunca pasa de moda.',
                    'price' => 499.99,
                    'sale_price' => 349.99,
                    'sku' => 'ANI-001',
                    'stock' => 8,
                    'material' => 'Oro Blanco 18k con diamante',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/736x/e3/d3/25/e3d325157ffd4c80e2df7dd24de4f37e.jpg',
                    ],
                ],
                [
                    'name' => 'Anillo Corazón Eterno',
                    'slug' => 'anillo-corazon-eterno',
                    'short_description' => 'Anillo con diseño de corazón en oro rosa',
                    'description' => 'Un romántico anillo con diseño de corazón esculpido en oro rosa de 14 quilates. Delicado, femenino y lleno de significado.',
                    'price' => 159.99,
                    'sale_price' => 129.99,
                    'sku' => 'ANI-002',
                    'stock' => 14,
                    'material' => 'Oro Rosa 14k',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/736x/7e/26/82/7e2682b58e5a5b5ca5eea156e1b5c5f7.jpg',
                    ],
                ],
                [
                    'name' => 'Anillo Eternidad Zirconia',
                    'slug' => 'anillo-eternidad-zirconia',
                    'short_description' => 'Anillo tipo eternidad con zirconia alrededor',
                    'description' => 'Este anillo de eternidad está completamente rodeado de zirconia cúbica brillante, simbolizando un amor sin principio ni fin.',
                    'price' => 89.99,
                    'sale_price' => null,
                    'sku' => 'ANI-003',
                    'stock' => 20,
                    'material' => 'Plata 925',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/736x/1d/c9/5d/1dc95d3fa32a0a38ea7ca1c2ef8efb87.jpg',
                    ],
                ],
                [
                    'name' => 'Anillo Vintage Rosa',
                    'slug' => 'anillo-vintage-rosa',
                    'short_description' => 'Anillo estilo vintage con piedra rosa central',
                    'description' => 'Inspirado en la joyería clásica, este anillo vintage presenta una piedra rosa central rodeada de detalles ornamentales en plata oxidada.',
                    'price' => 109.99,
                    'sale_price' => 89.99,
                    'sku' => 'ANI-004',
                    'stock' => 16,
                    'material' => 'Plata 925 oxidada',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/736x/29/f5/e0/29f5e0f3dd9baed56a2f3a4c7a42fc00.jpg',
                    ],
                ],
            ],
            'flores-eternas' => [
                [
                    'name' => 'Rosa Eterna en Cúpula',
                    'slug' => 'rosa-eterna-cupula',
                    'short_description' => 'Rosa preservada roja en cúpula de cristal estilo La Bella y la Bestia',
                    'description' => 'Inspirada en el clásico cuento, esta rosa preservada roja descansa bajo una elegante cúpula de cristal con base de madera. Dura hasta 3 años sin agua ni cuidados.',
                    'price' => 129.99,
                    'sale_price' => 103.99,
                    'sku' => 'FLO-001',
                    'stock' => 30,
                    'material' => 'Rosa natural preservada',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/736x/cb/ef/6e/cbef6e099db2a2b90825505936e65985.jpg',
                    ],
                ],
                [
                    'name' => 'Rosa Eterna Azul',
                    'slug' => 'rosa-eterna-azul',
                    'short_description' => 'Rosa preservada azul única y exclusiva',
                    'description' => 'Una rosa azul preservada que simboliza lo imposible hecho realidad. Presentada en una elegante caja de terciopelo negro, es un regalo verdaderamente único.',
                    'price' => 139.99,
                    'sale_price' => null,
                    'sku' => 'FLO-002',
                    'stock' => 15,
                    'material' => 'Rosa natural preservada',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/736x/36/8d/fb/368dfb01be89c75e55d1c4dfd8f3fc4d.jpg',
                    ],
                ],
                [
                    'name' => 'Ramo Eterno Premium',
                    'slug' => 'ramo-eterno-premium',
                    'short_description' => 'Ramo de 12 rosas preservadas en caja lujosa',
                    'description' => 'Un lujoso ramo de 12 rosas preservadas en diferentes tonos de rosa y rojo, presentadas en una elegante caja redonda. Dura años sin perder su belleza.',
                    'price' => 249.99,
                    'sale_price' => 199.99,
                    'sku' => 'FLO-003',
                    'stock' => 10,
                    'material' => 'Rosas naturales preservadas',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/736x/f0/c5/6b/f0c56b23bb72c5fa67c0f0b87e7ef9ff.jpg',
                    ],
                ],
                [
                    'name' => 'Rosa Dorada Luxury',
                    'slug' => 'rosa-dorada-luxury',
                    'short_description' => 'Rosa bañada en oro de 24k en estuche premium',
                    'description' => 'Una rosa real bañada en oro de 24 quilates. Cada pétalo ha sido cuidadosamente preservado y cubierto en oro, creando una pieza de colección única.',
                    'price' => 179.99,
                    'sale_price' => 159.99,
                    'sku' => 'FLO-004',
                    'stock' => 8,
                    'material' => 'Rosa natural bañada en oro 24k',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/736x/e7/24/aa/e724aa6e8c3b8c5e8c5c8b5fec1d6a1d.jpg',
                    ],
                ],
            ],
            'luces-led' => [
                [
                    'name' => 'Luz LED Corazón',
                    'slug' => 'luz-led-corazon',
                    'short_description' => 'Lámpara LED con forma de corazón y luz cálida',
                    'description' => 'Ilumina tu espacio con esta romántica lámpara LED en forma de corazón. Emite una luz cálida y suave, perfecta para crear un ambiente acogedor.',
                    'price' => 39.99,
                    'sale_price' => 35.99,
                    'sku' => 'LED-001',
                    'stock' => 50,
                    'material' => 'Acrílico con base de madera',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/736x/b5/d8/fc/b5d8fc8b7ea8fc2c7dba0bc92c8e3bfc.jpg',
                    ],
                ],
                [
                    'name' => 'Luz Luna 3D',
                    'slug' => 'luz-luna-3d',
                    'short_description' => 'Lámpara lunar 3D con 16 colores y control remoto',
                    'description' => 'Réplica impresa en 3D de la superficie lunar con 16 colores diferentes controlados por mando a distancia. Una pieza decorativa y funcional.',
                    'price' => 59.99,
                    'sale_price' => 49.99,
                    'sku' => 'LED-002',
                    'stock' => 40,
                    'material' => 'PLA biodegradable',
                    'is_featured' => true,
                    'images' => [
                        'https://i.pinimg.com/736x/cd/43/e2/cd43e27b8e9a7b3f78d8a3a6e8b8e9c4.jpg',
                    ],
                ],
                [
                    'name' => 'Letrero LED Nombre Personalizado',
                    'slug' => 'letrero-led-nombre',
                    'short_description' => 'Letrero neón LED personalizable con nombre',
                    'description' => 'Personaliza este letrero LED con el nombre de tu ser querido. Efecto neón en diferentes colores, perfecto para decorar la habitación con un toque especial.',
                    'price' => 79.99,
                    'sale_price' => 59.99,
                    'sku' => 'LED-003',
                    'stock' => 25,
                    'material' => 'Acrílico con LED neón flexible',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/736x/15/2a/e2/152ae2b07c6b3c76ca3ff7dccbc2b7ef.jpg',
                    ],
                ],
                [
                    'name' => 'Guirnalda LED Estrellas',
                    'slug' => 'guirnalda-led-estrellas',
                    'short_description' => 'Guirnalda de luces LED con estrellas doradas',
                    'description' => 'Transforma cualquier espacio con esta encantadora guirnalda de estrellas LED. 3 metros de luces cálidas con estrellas doradas, perfecta para crear un rincón romántico.',
                    'price' => 29.99,
                    'sale_price' => null,
                    'sku' => 'LED-004',
                    'stock' => 60,
                    'material' => 'Alambre de cobre con LED',
                    'is_featured' => false,
                    'images' => [
                        'https://i.pinimg.com/736x/97/26/97/972697e47b8c6f9c73ef1ab9c1c0d59b.jpg',
                    ],
                ],
            ],
        ];
    }
}
