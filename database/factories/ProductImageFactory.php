<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'image_path' => 'watches/astra-black.png',
            'is_primary' => false,
            'sort_order' => 0,
        ];
    }

    /**
     * The main photo of the watch.
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes): array => ['is_primary' => true, 'sort_order' => 0]);
    }
}
