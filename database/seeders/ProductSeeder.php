<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Categories
        $categoriesData = [
            ['name' => 'Fiambres', 'slug' => 'fiambres', 'color' => 'bg-red-100 text-red-800'],
            ['name' => 'Quesos', 'slug' => 'quesos', 'color' => 'bg-yellow-100 text-yellow-800'],
            ['name' => 'Bebidas', 'slug' => 'bebidas', 'color' => 'bg-purple-100 text-purple-800'],
            ['name' => 'Gourmet', 'slug' => 'gourmet', 'color' => 'bg-green-100 text-green-800'],
        ];

        $categories = collect();
        foreach ($categoriesData as $cat) {
            $categories->push(Category::create($cat));
        }

        // 2. Tags
        $tagsData = [
            ['name' => 'Sin TACC', 'color' => 'bg-orange-500'],
            ['name' => 'Picante', 'color' => 'bg-red-600'],
            ['name' => 'Premium', 'color' => 'bg-gold-500'],
            ['name' => 'Oferta', 'color' => 'bg-blue-500'],
            ['name' => 'Nuevo', 'color' => 'bg-green-500'],
        ];

        $tags = collect();
        foreach ($tagsData as $tag) {
            $tags->push(Tag::create($tag));
        }

        // 3. Products
        $products = [
            // FIAMBRES
            [
                'name' => 'Salame de Milán Premium',
                'description' => 'Salame de grano fino con especias seleccionadas, estacionado 60 días.',
                'price' => 280000,
                'unit_type' => 'kg',
                'category_id' => $categories->firstWhere('slug', 'fiambres')->id,
                'is_featured' => true,
                'tags' => ['Premium', 'Sin TACC'],
            ],
            [
                'name' => 'Jamón Crudo Serrano',
                'description' => 'Curado natural por 12 meses. Sabor intenso y textura suave.',
                'price' => 450000,
                'unit_type' => 'kg',
                'category_id' => $categories->firstWhere('slug', 'fiambres')->id,
                'is_featured' => true,
                'tags' => ['Premium'],
            ],
            [
                'name' => 'Mortadela con Pistachos',
                'description' => 'Clásica receta italiana con pistachos enteros.',
                'price' => 210000,
                'unit_type' => 'kg',
                'category_id' => $categories->firstWhere('slug', 'fiambres')->id,
                'is_featured' => false,
                'tags' => ['Nuevo'],
            ],

            // QUESOS
            [
                'name' => 'Queso Brie Francés',
                'description' => 'Pasta blanda con corteza enmohecida, cremoso y delicado.',
                'price' => 550000,
                'unit_type' => 'kg',
                'category_id' => $categories->firstWhere('slug', 'quesos')->id,
                'is_featured' => true,
                'tags' => ['Premium'],
            ],
            [
                'name' => 'Queso Azul Danés',
                'description' => 'Sabor picante y salado, ideal para salsas o tablas.',
                'price' => 420000,
                'unit_type' => 'kg',
                'category_id' => $categories->firstWhere('slug', 'quesos')->id,
                'is_featured' => false,
                'tags' => ['Picante'],
            ],

            // BEBIDAS
            [
                'name' => 'Malbec Reserva 2020',
                'description' => 'Vino de autor, criado 12 meses en barrica de roble.',
                'price' => 850000,
                'unit_type' => 'unit',
                'category_id' => $categories->firstWhere('slug', 'bebidas')->id,
                'is_featured' => true,
                'tags' => ['Oferta'],
            ],

            // GOURMET
            [
                'name' => 'Aceite de Oliva Extra Virgen',
                'description' => 'Primera prensada en frío. Acidez menor a 0.3%.',
                'price' => 1200000,
                'unit_type' => 'unit',
                'category_id' => $categories->firstWhere('slug', 'gourmet')->id,
                'is_featured' => false,
                'tags' => ['Sin TACC', 'Premium'],
            ],
        ];

        foreach ($products as $pData) {
            $tagsToAttach = $pData['tags'] ?? [];
            unset($pData['tags']);
            
            $pData['slug'] = Str::slug($pData['name']);
            $pData['is_active'] = true;
            $pData['image_path'] = null; // Placeholder

            $product = Product::create($pData);

            // Attach tags
            $tagIds = $tags->whereIn('name', $tagsToAttach)->pluck('id');
            $product->tags()->attach($tagIds);
        }

        // 4. Fake Orders (for Stats)
        $statuses = ['pending', 'contacted', 'completed', 'cancelled'];
        
        for ($i = 0; $i < 15; $i++) {
            Order::create([
                'customer_name' => 'Cliente ' . ($i + 1),
                'customer_phone' => '54911' . rand(10000000, 99999999),
                'total' => rand(500000, 5000000), // $5,000 to $50,000
                'items' => [
                    ['name' => 'Producto Random', 'qty' => 1, 'subtotal' => 1000]
                ],
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}