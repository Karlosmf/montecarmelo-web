<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Clean previous data
        Schema::disableForeignKeyConstraints();
        Order::truncate();
        DB::table('product_tag')->truncate();
        Product::truncate();
        Tag::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Categories
        // "Asegurarse de que las Categorías Embutidos, Quesos y Picadas existan primero"
        $categoriesData = [
            ['name' => 'Embutidos', 'slug' => 'embutidos', 'color' => 'bg-red-100 text-red-800'],
            ['name' => 'Quesos', 'slug' => 'quesos', 'color' => 'bg-yellow-100 text-yellow-800'],
            ['name' => 'Picadas', 'slug' => 'picadas', 'color' => 'bg-orange-100 text-orange-800'],
        ];

        $categories = collect();
        foreach ($categoriesData as $cat) {
            $categories->push(Category::create($cat));
        }

        // 2. Tags (Optional but good to have)
        $tagsData = [
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
            // Categoría: Embutidos
            [
                'name' => 'Bondiola Feteada al Vacío',
                'description' => 'Curada pacientemente con especias naturales. Sabor intenso y textura mantecosa que se deshace en el paladar. Ideal para bruschettas.',
                'price' => 318900,
                'unit_type' => 'kg',
                'image_path' => 'products/bondiola-feteada.jpg',
                'category_slug' => 'embutidos',
            ],
            [
                'name' => 'Lomo de Cerdo a las Hierbas',
                'description' => 'La pieza más noble del cerdo, curada con una selección de hierbas de campo. Magro, suave y aromático.',
                'price' => 350000,
                'unit_type' => 'kg',
                'image_path' => 'products/lomo-cerdo.jpg',
                'category_slug' => 'embutidos',
            ],
            [
                'name' => 'Jamón Crudo Reserva',
                'description' => 'Nuestra estrella. Estacionamiento prolongado para lograr el punto justo de sal y dulzura. Feteado con separadores para conservar su frescura.',
                'price' => 411100,
                'unit_type' => 'kg',
                'image_path' => 'products/jamon-crudo.jpg',
                'category_slug' => 'embutidos',
            ],
            [
                'name' => 'Salame Picado Grueso (Tipo Casero)',
                'description' => 'La receta del abuelo. Carne de cerdo seleccionada, tocino en cubos y pimienta en grano. Atado a mano.',
                'price' => 1800000,
                'unit_type' => 'unit',
                'image_path' => 'products/salame-picado-grueso.jpg',
                'category_slug' => 'embutidos',
            ],

            // Categoría: Quesos (De Terceros)
            [
                'name' => 'Queso Pategrás Selección',
                'description' => 'Pasta semidura, ojos bien formados y sabor levemente picante. El compañero indiscutido del salame.',
                'price' => 120000,
                'unit_type' => 'kg',
                'image_path' => 'products/queso-pategras.jpg',
                'category_slug' => 'quesos',
            ],

            // Categoría: Picadas
            [
                'name' => 'Tabla "Monte Carmelo" (4 Personas)',
                'description' => 'La experiencia completa. Selección de bondiola, jamón crudo, lomo, salame y quesos, acompañados de aceitunas y pan de campo.',
                'price' => 2500000,
                'unit_type' => 'unit',
                'image_path' => 'products/picada-premium.jpg',
                'category_slug' => 'picadas',
            ],
        ];

        foreach ($products as $pData) {
            $catSlug = $pData['category_slug'];
            unset($pData['category_slug']);

            $pData['category_id'] = $categories->firstWhere('slug', $catSlug)->id;
            $pData['slug'] = Str::slug($pData['name']);
            $pData['is_active'] = true;
            $pData['is_featured'] = str_contains($pData['name'], 'Monte Carmelo') || str_contains($pData['name'], 'Crudo'); // Simple logic to feature some items

            Product::create($pData);
        }
    }
}