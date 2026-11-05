<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_place_cod_order_in_pakistan_and_stock_is_decremented(): void
    {
        $watch = Product::factory()->create([
            'title' => 'Casio G-Shock CasiOak',
            'price' => 135.00,
            'sale_price' => 110.00,
            'stock' => 10,
        ]);

        $response = $this->post(route('checkout.process'), $this->validCheckoutPayload([
            'product_id' => $watch->id,
            'quantity' => 2,
            'variant' => 'Matte Black Dial',
            'payment_method' => 'cod',
            'shipping_method' => 'armored',
        ]));

        $order = Order::with('items')->firstOrFail();

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('order.confirmation', ['orderNumber' => $order->order_number]));

        $this->assertNull($order->user_id);
        $this->assertSame('Ali Khan', $order->customer_name);
        $this->assertSame('Lahore', $order->delivery_city);
        $this->assertSame(PaymentMethod::Cod, $order->payment_method);
        $this->assertSame(OrderStatus::Pending, $order->order_status);
        $this->assertSame('220.00', $order->subtotal);
        $this->assertSame('0.00', $order->shipping_cost);
        $this->assertSame('220.00', $order->total_amount);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $watch->id,
            'product_title' => 'Casio G-Shock CasiOak',
            'unit_price' => 110.00,
            'quantity' => 2,
            'dial_variant' => 'Matte Black Dial',
            'line_total' => 220.00,
        ]);

        $this->assertSame(8, $watch->fresh()->stock);
    }

    public function test_authenticated_customer_order_applies_database_coupon_and_ignores_tampered_client_prices(): void
    {
        $user = User::factory()->create(['name' => 'Bilal Ahmed', 'email' => 'bilal@example.com']);
        $watch = Product::factory()->create([
            'title' => 'Seiko Speedtimer SSC813',
            'price' => 700.00,
            'sale_price' => 500.00,
            'stock' => 5,
        ]);
        Coupon::factory()->create([
            'code' => 'TIMEYRAH10',
            'value' => 10,
        ]);

        $tamperedCartJson = json_encode([
            [
                'id' => $watch->id,
                'price' => 1.00,
                'unit_price' => 1.00,
                'quantity' => 2,
                'variant' => 'Panda Dial',
            ],
        ], JSON_THROW_ON_ERROR);

        $response = $this->actingAs($user)->post(route('checkout.process'), $this->validCheckoutPayload([
            'payment_method' => 'wire',
            'shipping_method' => 'concierge',
            'promo_code' => 'timeyrah10',
            'cart_items_json' => $tamperedCartJson,
        ]));

        $order = Order::firstOrFail();

        $response->assertRedirect(route('order.confirmation', ['orderNumber' => $order->order_number]));

        $this->assertSame($user->id, $order->user_id);
        $this->assertSame(PaymentMethod::Bank, $order->payment_method);
        $this->assertSame('TIMEYRAH10', $order->coupon_code);
        $this->assertSame('1000.00', $order->subtotal);
        $this->assertSame('100.00', $order->discount_amount);
        $this->assertSame('150.00', $order->shipping_cost);
        $this->assertSame('1050.00', $order->total_amount);
    }

    public function test_expired_coupon_gives_zero_discount(): void
    {
        $watch = Product::factory()->create([
            'price' => 300.00,
            'sale_price' => null,
            'stock' => 4,
        ]);
        Coupon::factory()->expired()->fixed(100)->create([
            'code' => 'OLD100',
        ]);

        $this->post(route('checkout.process'), $this->validCheckoutPayload([
            'product_id' => $watch->id,
            'quantity' => 1,
            'promo_code' => 'OLD100',
        ]));

        $order = Order::firstOrFail();

        $this->assertNull($order->coupon_code);
        $this->assertSame('0.00', $order->discount_amount);
        $this->assertSame('300.00', $order->total_amount);
    }

    public function test_checkout_rejects_quantity_exceeding_available_stock_without_saving_order(): void
    {
        $watch = Product::factory()->create([
            'title' => 'Rado Limited Edition',
            'stock' => 2,
        ]);

        $response = $this->from(route('checkout'))->post(route('checkout.process'), $this->validCheckoutPayload([
            'product_id' => $watch->id,
            'quantity' => 3,
        ]));

        $response
            ->assertRedirect(route('checkout'))
            ->assertSessionHasErrors([
                'quantity' => 'Sorry, Rado Limited Edition currently has 2 left in stock.',
            ]);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertSame(2, $watch->fresh()->stock);
    }

    public function test_checkout_rejects_empty_payload_with_validation_messages(): void
    {
        $response = $this->from(route('checkout'))->post(route('checkout.process'), []);

        $response
            ->assertRedirect(route('checkout'))
            ->assertSessionHasErrors([
                'first_name' => 'The first name field is required.',
                'last_name' => 'The last name field is required.',
                'email' => 'The email field is required.',
                'phone' => 'The phone field is required.',
                'address' => 'The address field is required.',
                'city' => 'The city field is required.',
                'postal_code' => 'The postal code field is required.',
            ]);

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_order_confirmation_renders_saved_cod_order_from_database_without_card_digits(): void
    {
        $watch = Product::factory()->create([
            'title' => 'Citizen Automatic Yellow',
            'price' => 450.00,
            'sale_price' => null,
            'stock' => 6,
        ]);

        $this->post(route('checkout.process'), $this->validCheckoutPayload([
            'first_name' => 'Zain',
            'last_name' => 'Malik',
            'city' => 'Islamabad',
            'payment_method' => 'cod',
            'product_id' => $watch->id,
            'quantity' => 1,
        ]));

        $order = Order::firstOrFail();

        $response = $this->get(route('order.confirmation', ['orderNumber' => $order->order_number]));

        $response
            ->assertSee($order->order_number)
            ->assertSee('Zain Malik')
            ->assertSee('Citizen Automatic Yellow')
            ->assertSee('Islamabad')
            ->assertSee('Cash on Delivery')
            ->assertDontSee('••••');
    }

    public function test_order_confirmation_returns_404_for_unknown_order_number(): void
    {
        $this->get(route('order.confirmation', ['orderNumber' => 'TY-ORD-2026-MISSING']))
            ->assertNotFound();
    }

    /**
     * Build a valid Pakistani checkout form payload with optional overrides.
     *
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validCheckoutPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Ali',
            'last_name' => 'Khan',
            'email' => 'ali.khan@example.com',
            'phone' => '+92 300 1234567',
            'address' => 'House 22, Block Y, DHA Phase 3',
            'apartment' => 'Ground Floor',
            'city' => 'Lahore',
            'state' => 'Punjab',
            'postal_code' => '54792',
            'country' => 'Pakistan',
            'delivery_notes' => 'Please call before arrival.',
            'shipping_method' => 'armored',
            'payment_method' => 'cod',
            'engraving' => 'AK 2026',
            'wrist_size' => '7.0',
            'quantity' => 1,
            'variant' => 'Signature Dial',
        ], $overrides);
    }
}
