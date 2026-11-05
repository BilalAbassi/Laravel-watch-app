<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 40, 2000);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_title' => Str::title(fake()->words(3, true)).' Watch',
            'unit_price' => $unitPrice,
            'quantity' => 1,
            'dial_variant' => 'Signature Dial',
            'line_total' => $unitPrice,
        ];
    }
}
