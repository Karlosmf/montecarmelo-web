<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slide::create([
            'title' => 'Picadas Premium',
            'description' => 'Disfruta de la mejor selección de fiambres y quesos artesanales en tu mesa.',
            'image_path' => 'products/picada-premium.jpg',
            'button_text' => 'Ver Catálogo',
            'button_url' => '/products',
            'order' => 1,
            'is_active' => true,
        ]);

        Slide::create([
            'title' => 'Salamines Caseros',
            'description' => 'Elaboración propia con recetas tradicionales y el tiempo justo de maduración.',
            'image_path' => 'products/salame-picado-grueso.jpg',
            'button_text' => 'Comprar Ahora',
            'button_url' => '/products?category=1',
            'order' => 2,
            'is_active' => true,
        ]);

        Slide::create([
            'title' => 'Quesos de Campo',
            'description' => 'Variedad de quesos saborizados y tradicionales para acompañar tus mejores momentos.',
            'image_path' => 'products/queso-pategras.jpg',
            'button_text' => 'Descubrir',
            'button_url' => '/products?category=2',
            'order' => 3,
            'is_active' => true,
        ]);
    }
}
