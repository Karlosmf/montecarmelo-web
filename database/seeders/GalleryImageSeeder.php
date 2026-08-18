<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GalleryImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            ['title' => 'Picada Premium con Malbec', 'image_path' => 'products/picada-premium.jpg', 'order' => 1],
            ['title' => 'Bondiola Feteada al Vacío', 'image_path' => 'products/bondiola-feteada.jpg', 'order' => 2],
            ['title' => 'Salame Picado Grueso', 'image_path' => 'products/salame-picado-grueso.jpg', 'order' => 3],
            ['title' => 'Queso Pategrás Selección', 'image_path' => 'products/queso-pategras.jpg', 'order' => 4],
            ['title' => 'Jamón Crudo Reserva', 'image_path' => 'products/jamon-crudo.jpg', 'order' => 5],
            ['title' => 'Cuchillos Artesanales', 'image_path' => 'products/login-background.jpg', 'order' => 6],
            ['title' => 'Lomo de Cerdo a las Hierbas', 'image_path' => 'products/lomo-cerdo.jpg', 'order' => 7],
        ];

        foreach ($images as $img) {
            GalleryImage::create([
                'title' => $img['title'],
                'image_path' => $img['image_path'],
                'alt_text' => $img['title'],
                'order' => $img['order'],
                'is_active' => true,
            ]);
        }
    }
}
