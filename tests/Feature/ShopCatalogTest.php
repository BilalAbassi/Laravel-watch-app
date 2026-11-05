<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CouponSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_seeder_populates_all_curated_brands_categories_watches_and_coupons(): void
    {
        $this->seed([ProductSeeder::class, CouponSeeder::class]);

        $this->assertDatabaseCount('brands', 20);
        $this->assertDatabaseCount('categories', 6);
        $this->assertDatabaseCount('products', 17);
        $this->assertDatabaseCount('coupons', 2);
        $this->assertDatabaseHas('products', [
            'id' => 1,
            'title' => 'Rado Captain Cook High-Tech Ceramic Diver',
            'price' => 4200.00,
            'sale_price' => 3850.00,
        ]);
    }

    public function test_shop_page_renders_active_watches_and_hides_inactive_watches(): void
    {
        $visibleWatch = Product::factory()->create(['title' => 'Seiko Panda Chrono']);
        $hiddenWatch = Product::factory()->inactive()->create(['title' => 'Hidden Prototype Watch']);

        $response = $this->get(route('shop'));

        $response
            ->assertSee($visibleWatch->title)
            ->assertDontSee($hiddenWatch->title);
    }

    public function test_shop_filters_watches_by_brand_category_search_and_sale_price_range(): void
    {
        $rado = Brand::factory()->create(['name' => 'Rado', 'slug' => 'rado']);
        $casio = Brand::factory()->create(['name' => 'Casio G Shock', 'slug' => 'casio-g-shock']);

        $automatic = Category::factory()->create(['name' => 'AUTOMATIC', 'slug' => 'automatic']);
        $digital = Category::factory()->create(['name' => 'DIGITAL', 'slug' => 'digital']);

        $matchingWatch = Product::factory()
            ->for($rado)
            ->for($automatic)
            ->create([
                'title' => 'Rado Ceramic Diver',
                'price' => 500.00,
                'sale_price' => 390.00,
                'movement' => 'Swiss Automatic',
            ]);

        $otherBrandWatch = Product::factory()
            ->for($casio)
            ->for($digital)
            ->create([
                'title' => 'Casio Stealth Digital',
                'price' => 120.00,
                'sale_price' => null,
            ]);

        $expensiveWatch = Product::factory()
            ->for($rado)
            ->for($automatic)
            ->create([
                'title' => 'Rado Skeleton Tourbillon',
                'price' => 1500.00,
                'sale_price' => 1200.00,
            ]);

        $response = $this->get(route('shop', [
            'brand' => 'Rado',
            'category' => 'AUTOMATIC',
            'search' => 'Ceramic',
            'min_price' => 300,
            'max_price' => 450,
        ]));

        $response
            ->assertSee($matchingWatch->title)
            ->assertDontSee($otherBrandWatch->title)
            ->assertDontSee($expensiveWatch->title);
    }

    public function test_shop_json_endpoint_paginates_and_sorts_by_current_price_ascending(): void
    {
        Product::factory()->create([
            'title' => 'Luxury Gold Watch',
            'price' => 900.00,
            'sale_price' => null,
        ]);
        Product::factory()->create([
            'title' => 'Discounted Diver Watch',
            'price' => 400.00,
            'sale_price' => 180.00,
        ]);
        Product::factory()->create([
            'title' => 'Everyday Classic Watch',
            'price' => 250.00,
            'sale_price' => null,
        ]);

        $response = $this->getJson(route('shop', [
            'sort' => 'price_asc',
            'per_page' => 2,
            'page' => 1,
        ]));

        $response
            ->assertOk()
            ->assertJsonPath('total', 3)
            ->assertJsonPath('per_page', 2)
            ->assertJsonPath('watches.0.title', 'Discounted Diver Watch')
            ->assertJsonPath('watches.1.title', 'Everyday Classic Watch');
    }

    public function test_product_detail_renders_active_watch_by_id_or_slug_with_related_watches(): void
    {
        $brand = Brand::factory()->create(['name' => 'Citizen', 'slug' => 'citizen']);
        $watch = Product::factory()->for($brand)->create([
            'title' => 'Citizen Tsuyosa Sunray',
            'slug' => 'citizen-tsuyosa-sunray',
            'price' => 495.00,
            'sale_price' => 450.00,
        ]);
        $related = Product::factory()->for($brand)->create([
            'title' => 'Citizen Promaster Diver',
        ]);

        $response = $this->get(route('product.show', $watch->slug));

        $response
            ->assertSee('Citizen Tsuyosa Sunray')
            ->assertSee('Citizen Promaster Diver')
            ->assertSee($related->title);
    }

    public function test_product_detail_returns_404_when_watch_is_inactive_or_missing(): void
    {
        $inactiveWatch = Product::factory()->inactive()->create();

        $this->get(route('product.show', $inactiveWatch->id))->assertNotFound();
        $this->get(route('product.show', 999999))->assertNotFound();
    }
}
