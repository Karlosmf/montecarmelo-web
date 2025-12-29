<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(100, 10000), // cents
            'unit_type' => $this->faker->randomElement(['kg', 'unit', 'pack']),
            'category' => $this->faker->word(),
            'image_path' => null,
            'is_active' => true,
            'is_featured' => false,
        ];
    }
}
