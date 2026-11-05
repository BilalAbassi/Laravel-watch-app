<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_visiting_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_dashboard_renders_customer_orders_stats_and_active_coupons_while_isolating_other_users_orders(): void
    {
        $customer = User::factory()->create(['name' => 'Hamza Tariq']);
        $otherCustomer = User::factory()->create();

        $pendingOrder = Order::factory()->for($customer)->create([
            'order_number' => 'TY-ORD-2026-OWN0001',
            'delivery_city' => 'Karachi',
            'total_amount' => 110.00,
        ]);
        OrderItem::factory()->for($pendingOrder)->create([
            'product_title' => 'Casio G-Shock CasiOak',
        ]);

        $deliveredOrder = Order::factory()->for($customer)->delivered()->create([
            'order_number' => 'TY-ORD-2026-OWN0002',
            'delivery_city' => 'Lahore',
            'total_amount' => 450.00,
        ]);
        OrderItem::factory()->for($deliveredOrder)->create([
            'product_title' => 'Citizen Tsuyosa Sunray',
        ]);

        Order::factory()->for($customer)->cancelled()->create([
            'order_number' => 'TY-ORD-2026-OWN0003',
            'total_amount' => 999.00,
        ]);

        $otherUserOrder = Order::factory()->for($otherCustomer)->create([
            'order_number' => 'TY-ORD-2026-OTHER99',
        ]);

        Coupon::factory()->create([
            'code' => 'TIMEYRAH10',
            'value' => 10,
        ]);

        $response = $this->actingAs($customer)->get(route('dashboard'));

        $response
            ->assertSee('Welcome back, Hamza Tariq')
            ->assertSee('TY-ORD-2026-OWN0001')
            ->assertSee('Casio G-Shock CasiOak')
            ->assertSee('TY-ORD-2026-OWN0002')
            ->assertSee('Citizen Tsuyosa Sunray')
            ->assertSee('$560.00')
            ->assertSee('TIMEYRAH10')
            ->assertDontSee($otherUserOrder->order_number);
    }

    public function test_dashboard_renders_empty_state_and_featured_watches_for_new_customer(): void
    {
        $customer = User::factory()->create(['name' => 'Sara Khan']);
        $featuredWatch = Product::factory()->featured()->create([
            'title' => 'Rado Captain Cook Ceramic',
        ]);

        $response = $this->actingAs($customer)->get(route('dashboard'));

        $response
            ->assertSee('Welcome back, Sara Khan')
            ->assertSee('No Watch Orders Yet')
            ->assertSee($featuredWatch->title);
    }
}
