<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private array $uploadedFilesToClean = [];

    protected function tearDown(): void
    {
        foreach ($this->uploadedFilesToClean as $filePath) {
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        parent::tearDown();
    }

    public function test_guest_cannot_access_admin_product_routes(): void
    {
        $this->get(route('admin.products.index'))
            ->assertRedirect(route('login'));

        $this->get(route('admin.products.create'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_customer_is_forbidden_from_admin_products(): void
    {
        $customer = User::factory()->create(['is_admin' => false]);

        $this->actingAs($customer)
            ->get(route('admin.products.index'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('admin.products.create'))
            ->assertForbidden();
    }

    public function test_admin_can_view_product_index_and_create_page(): void
    {
        $admin = User::factory()->admin()->create();
        $brand = Brand::factory()->create(['name' => 'Omega']);
        $product = Product::factory()->for($brand)->create(['title' => 'Speedmaster Professional Moonwatch']);

        $response = $this->actingAs($admin)->get(route('admin.products.index'));

        $response->assertOk()
            ->assertSee('Admin Atelier')
            ->assertSee('Watch Catalog Management')
            ->assertSee('Speedmaster Professional Moonwatch')
            ->assertSee('Omega');

        $this->actingAs($admin)
            ->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('Add New Timepiece to Atelier')
            ->assertSee('Omega');
    }

    public function test_admin_can_store_new_product_with_image_and_it_appears_in_store(): void
    {
        $admin = User::factory()->admin()->create();
        $brand = Brand::factory()->create(['name' => 'Rolex', 'slug' => 'rolex']);
        $category = Category::factory()->create(['name' => 'AUTOMATIC', 'slug' => 'automatic']);

        $testImage = UploadedFile::fake()->image('submariner.jpg', 600, 600);

        $payload = [
            'title' => 'Submariner Date 41mm Oystersteel',
            'sku' => 'TY-ROL-SUB01',
            'brand_id' => $brand->id,
            'category_ids' => [$category->id],
            'price' => 10250.00,
            'sale_price' => 9800.00,
            'stock' => 3,
            'movement' => 'Rolex Calibre 3235 Automatic',
            'case_size' => '41mm',
            'case_material' => 'Oystersteel (904L)',
            'strap_type' => 'Oyster Bracelet',
            'glass' => 'Scratch-resistant Sapphire',
            'water_resistance' => '300m / 1000ft',
            'warranty' => '5 Years International Warranty',
            'gender' => "Men's",
            'badge' => 'ICONS',
            'description' => 'The archetype of the diver watch.',
            'is_featured' => '1',
            'is_active' => '1',
            'images' => [$testImage],
        ];

        $response = $this->actingAs($admin)->post(route('admin.products.store'), $payload);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'title' => 'Submariner Date 41mm Oystersteel',
            'slug' => 'submariner-date-41mm-oystersteel',
            'sku' => 'TY-ROL-SUB01',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'price' => 10250.00,
            'sale_price' => 9800.00,
            'stock' => 3,
            'is_featured' => 1,
            'is_active' => 1,
        ]);

        $createdProduct = Product::where('slug', 'submariner-date-41mm-oystersteel')->firstOrFail();
        $this->assertTrue($createdProduct->categories->contains($category->id));

        $savedImage = $createdProduct->images()->first();
        $this->assertNotNull($savedImage);
        $this->assertTrue($savedImage->is_primary);
        $this->assertStringStartsWith('uploads/watches/', $savedImage->image_path);

        $fullPath = public_path($savedImage->image_path);
        $this->uploadedFilesToClean[] = $fullPath;
        $this->assertTrue(File::exists($fullPath));

        // Verify it immediately appears on the public shop catalog
        $shopResponse = $this->get(route('shop'));
        $shopResponse->assertOk()
            ->assertSee('Submariner Date 41mm Oystersteel');

        // Verify product detail page is accessible
        $detailResponse = $this->get(route('product.show', $createdProduct->slug));
        $detailResponse->assertOk()
            ->assertSee('Submariner Date 41mm Oystersteel')
            ->assertSee('Rolex Calibre 3235 Automatic');
    }

    public function test_admin_can_update_product_details_pricing_and_stock(): void
    {
        $admin = User::factory()->admin()->create();
        $brand = Brand::factory()->create(['name' => 'Tissot']);
        $category = Category::factory()->create(['name' => 'CHRONOGRAPH']);
        $product = Product::factory()->for($brand)->create([
            'title' => 'Tissot PRX Powermatic 80',
            'price' => 650.00,
            'stock' => 10,
        ]);
        $product->categories()->attach($category);

        $this->actingAs($admin)
            ->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee('Edit Timepiece: Tissot PRX Powermatic 80');

        $updatePayload = [
            'title' => 'Tissot PRX Powermatic 80 Ice Blue',
            'sku' => $product->sku,
            'brand_id' => $brand->id,
            'category_ids' => [$category->id],
            'price' => 725.00,
            'sale_price' => 690.00,
            'stock' => 4,
            'movement' => 'Powermatic 80.111',
            'case_size' => '40mm',
            'case_material' => '316L Stainless Steel',
            'strap_type' => 'Integrated Stainless Steel',
            'glass' => 'Sapphire Crystal',
            'water_resistance' => '100m',
            'warranty' => '2 Years Official Brand Warranty',
            'gender' => "Men's",
            'is_featured' => '0',
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->put(route('admin.products.update', $product), $updatePayload);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Tissot PRX Powermatic 80 Ice Blue',
            'price' => 725.00,
            'sale_price' => 690.00,
            'stock' => 4,
        ]);
    }

    public function test_admin_can_delete_product_and_removes_from_store(): void
    {
        $admin = User::factory()->admin()->create();
        $brand = Brand::factory()->create(['name' => 'Grand Seiko']);
        $product = Product::factory()->for($brand)->create([
            'title' => 'Grand Seiko Snowflake SBGA211',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);

        $shopResponse = $this->get(route('shop'));
        $shopResponse->assertDontSee('Grand Seiko Snowflake SBGA211');
    }

    public function test_product_store_validation_requires_mandatory_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors(['title', 'brand_id', 'category_ids', 'price', 'stock']);
    }
}
