<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
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
        $title = Str::title(fake()->unique()->words(3, true)).' Watch';

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 99999),
            'sku' => 'TY-'.strtoupper(fake()->unique()->bothify('???-#####')),
            'brand_id' => Brand::factory(),
            'category_id' => Category::factory(),
            'price' => fake()->randomFloat(2, 40, 5000),
            'sale_price' => null,
            'stock' => fake()->numberBetween(5, 50),
            'movement' => 'Swiss-Engineered Quartz',
            'case_size' => '40.0 mm',
            'case_material' => '316L Stainless Steel',
            'strap_type' => 'Italian Handcrafted Calfskin Leather',
            'glass' => 'Scratch-Resistant Sapphire Crystal',
            'water_resistance' => '100m / 10 ATM Water Resistant',
            'warranty' => '2 Years International Warranty',
            'gender' => "Men's",
            'badge' => null,
            'rating' => fake()->randomFloat(2, 4, 5),
            'reviews_count' => fake()->numberBetween(0, 300),
            'description' => fake()->sentence(18),
            'is_featured' => false,
            'is_active' => true,
        ];
    }

    /**
     * Keep the main category in the many-to-many list as well.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Product $product): void {
            if ($product->category_id !== null) {
                $product->categories()->syncWithoutDetaching([$product->category_id]);
            }
        });
    }

    /**
     * A watch with a lower sale price (20% off).
     */
    public function onSale(): static
    {
        return $this->state(fn (array $attributes): array => [
            'sale_price' => round($attributes['price'] * 0.8, 2),
        ]);
    }

    /**
     * A watch that is sold out.
     */
    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes): array => ['stock' => 0]);
    }

    /**
     * A watch that is hidden from the store.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => ['is_active' => false]);
    }

    /**
     * A watch shown first on the shop page.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes): array => ['is_featured' => true]);
    }
}
