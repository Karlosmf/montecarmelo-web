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
        // Prices are stored in cents.
        
        $products = [
            // FIAMBRES
            [
                'name' => 'Salame de Milán Premium',
                'slug' => 'salame-milan-premium',
                'description' => 'Salame de grano fino con especias seleccionadas, estacionado 60 días.',
                'price' => 280000, // $2800/kg
                'unit_type' => 'kg',
                'category' => 'Fiambres',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Jamón Crudo Serrano',
                'slug' => 'jamon-crudo-serrano',
                'description' => 'Curado natural por 12 meses. Sabor intenso y textura suave.',
                'price' => 450000, // $4500/kg
                'unit_type' => 'kg',
                'category' => 'Fiambres',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Bondiola Casera',
                'slug' => 'bondiola-casera',
                'description' => 'Bondiola de cerdo condimentada con pimentón ahumado y ajo.',
                'price' => 320000, // $3200/kg
                'unit_type' => 'kg',
                'category' => 'Fiambres',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => false,
            ],
             [
                'name' => 'Mortadela con Pistachos',
                'slug' => 'mortadela-pistachos',
                'description' => 'Clásica receta italiana con pistachos enteros y pimienta negra.',
                'price' => 210000, // $2100/kg
                'unit_type' => 'kg',
                'category' => 'Fiambres',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => false,
            ],

            // QUESOS
            [
                'name' => 'Queso Holanda Artesanal',
                'slug' => 'queso-holanda-artesanal',
                'description' => 'Queso de pasta semidura, notas a nuez y manteca.',
                'price' => 220000, // $2200/kg
                'unit_type' => 'kg',
                'category' => 'Quesos',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Queso Brie Francés',
                'slug' => 'queso-brie',
                'description' => 'Pasta blanda con corteza enmohecida, cremoso y delicado.',
                'price' => 550000, // $5500/kg
                'unit_type' => 'kg',
                'category' => 'Quesos',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => true,
            ],
             [
                'name' => 'Queso Azul Danés',
                'slug' => 'queso-azul',
                'description' => 'Sabor picante y salado, ideal para salsas o tablas.',
                'price' => 420000, // $4200/kg
                'unit_type' => 'kg',
                'category' => 'Quesos',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => false,
            ],

            // BEBIDAS & GOURMET
            [
                'name' => 'Malbec Reserva 2020',
                'slug' => 'malbec-reserva-2020',
                'description' => 'Vino de autor, criado 12 meses en barrica de roble.',
                'price' => 850000, // $8500/un
                'unit_type' => 'unit',
                'category' => 'Bodega',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'Aceite de Oliva Extra Virgen',
                'slug' => 'aceite-oliva-500',
                'description' => 'Primera prensada en frío. Acidez menor a 0.3%. Botella 500ml.',
                'price' => 1200000, // $12000/un
                'unit_type' => 'unit',
                'category' => 'Gourmet',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Aceitunas Negras Griegas',
                'slug' => 'aceitunas-negras',
                'description' => 'Aceitunas secas con hierbas aromáticas. Frasco 300g.',
                'price' => 450000, // $4500/un
                'unit_type' => 'unit',
                'category' => 'Conservas',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}