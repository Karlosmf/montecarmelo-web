<?php

namespace Database\Seeders;

use App\Models\MasterPick;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MasterPickSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        MasterPick::truncate();
        Schema::enableForeignKeyConstraints();

        $defaults = [
            [
                'slug' => 'queso-pategras-seleccion',
                'kicker' => 'Maridaje de Quesos',
                'recommendation' => 'Queso Pategrás madurado a punto, cremoso y de carácter. El maestro lo recomienda con miel de monte o dulce de leche repostero, acompañado de un salame curado de picado grueso y un buen vino tinto. La grasa y la dulzura se equilibran en cada bocado.',
            ],
            [
                'slug' => 'salame-picado-grueso-tipo-casero',
                'kicker' => 'Maridaje de Salames',
                'recommendation' => 'Salame de elaboración propia con picado grueso y curación lenta. Para disfrutarlo como manda el maestro: lonjas finas a temperatura ambiente, queso Pategrás de la casa, pan criollo recién horneado y un vaso de cerveza artesanal o tinto ligero.',
            ],
            [
                'slug' => 'bondiola-feteada-al-vacio',
                'kicker' => 'Maridaje de Bondiola',
                'recommendation' => 'Bondiola feteada al vacío, jugosa y con apenas el punto justo de sal. Ideal en sándwich con pan de campo tostado, tomate y rúcula, o en una tabla con quesos blandos y un Malbec de la región.',
            ],
        ];

        $order = 1;
        foreach ($defaults as $data) {
            $product = Product::where('slug', $data['slug'])->first();
            if (! $product) {
                continue;
            }

            MasterPick::create([
                'product_id' => $product->id,
                'kicker' => $data['kicker'],
                'recommendation' => $data['recommendation'],
                'order' => $order++,
                'is_active' => true,
            ]);
        }
    }
}
