<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prices are stored in cents to avoid floating point errors in calculations.
        // Example: $15.50 becomes 1550.
        
        $products = [
            [
                'name' => 'Salame de Milán',
                'slug' => 'salame-milan',
                'description' => 'Salame de grano fino, estacionado 45 días.',
                'price' => 250000, // $2500.00
                'unit_type' => 'kg',
                'category' => 'Fiambres',
                'image_path' => 'products/salame-milan.jpg',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Queso Holanda',
                'slug' => 'queso-holanda',
                'description' => 'Queso semiduro de sabor suave y ligeramente picante.',
                'price' => 180000, // $1800.00
                'unit_type' => 'kg',
                'category' => 'Quesos',
                'image_path' => 'products/queso-holanda.jpg',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Vino Tinto Malbec',
                'slug' => 'vino-tinto-malbec',
                'description' => 'Vino de cuerpo medio con notas de frutos rojos.',
                'price' => 450000, // $4500.00
                'unit_type' => 'unit',
                'category' => 'Bebidas',
                'image_path' => 'products/vino-malbec.jpg',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Tabla de Picada Grande',
                'slug' => 'tabla-picada-grande',
                'description' => 'Surtido de fiambres y quesos para 4 personas.',
                'price' => 1200000, // $12000.00
                'unit_type' => 'unit',
                'category' => 'Tablas',
                'image_path' => 'products/tabla-grande.jpg',
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Aceitunas Verdes',
                'slug' => 'aceitunas-verdes',
                'description' => 'Aceitunas verdes en salmuera, calibre grande.',
                'price' => 80000, // $800.00
                'unit_type' => 'pack',
                'category' => 'Conservas',
                'image_path' => 'products/aceitunas.jpg',
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}