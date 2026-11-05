<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 40, 2000);

        return [
            'order_number' => 'TY-ORD-'.date('Y').'-'.strtoupper(fake()->unique()->regexify('[A-F0-9]{7}')),
            'user_id' => null,
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '+92 300 '.fake()->numerify('#######'),
            'delivery_city' => fake()->randomElement(['Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Peshawar']),
            'delivery_address' => fake()->streetAddress(),
            'delivery_state' => null,
            'postal_code' => fake()->numerify('#####'),
            'country' => 'Pakistan',
            'delivery_notes' => null,
            'payment_method' => PaymentMethod::Cod,
            'shipping_method' => 'armored',
            'coupon_code' => null,
            'subtotal' => $subtotal,
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'total_amount' => $subtotal,
            'order_status' => OrderStatus::Pending,
            'engraving' => null,
            'wrist_size' => null,
        ];
    }

    /**
     * An order that has been delivered.
     */
    public function delivered(): static
    {
        return $this->state(fn (array $attributes): array => ['order_status' => OrderStatus::Delivered]);
    }

    /**
     * An order that has been cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes): array => ['order_status' => OrderStatus::Cancelled]);
    }
}
