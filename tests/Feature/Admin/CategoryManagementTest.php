<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_categories(): void
    {
        $this->get(route('admin.categories.index'))
            ->assertRedirect(route('login'));

        $this->get(route('admin.categories.create'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_receives_forbidden_when_accessing_admin_categories(): void
    {
        $customer = User::factory()->create(['is_admin' => false]);

        $this->actingAs($customer)
            ->get(route('admin.categories.index'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('admin.categories.create'))
            ->assertForbidden();
    }

    public function test_admin_can_view_category_list_and_create_page(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['name' => 'TITANIUM COLLECTION']);

        $response = $this->actingAs($admin)->get(route('admin.categories.index'));

        $response->assertOk()
            ->assertSee('Filter Categories')
            ->assertSee('TITANIUM COLLECTION');

        $this->actingAs($admin)
            ->get(route('admin.categories.create'))
            ->assertOk()
            ->assertSee('Create New Watch Category');
    }

    public function test_admin_can_store_new_category_and_it_appears_in_store(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'SOLAR POWERED',
            'slug' => 'solar-powered',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'SOLAR POWERED',
            'slug' => 'solar-powered',
            'is_active' => 1,
        ]);

        // Category appears in public shop filter sidebar
        $this->get(route('shop'))
            ->assertOk()
            ->assertSee('SOLAR POWERED');
    }

    public function test_admin_can_edit_and_update_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['name' => 'OLD NAME', 'slug' => 'old-name']);

        $this->actingAs($admin)
            ->get(route('admin.categories.edit', $category))
            ->assertOk()
            ->assertSee('Edit Category: OLD NAME');

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'CERAMIC BEZEL',
            'slug' => 'ceramic-bezel',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'CERAMIC BEZEL',
            'slug' => 'ceramic-bezel',
        ]);
    }

    public function test_admin_can_delete_category_safely_without_deleting_watches(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create(['name' => 'TEMP CATEGORY']);
        $product = Product::factory()->create(['title' => 'Diver Pro']);
        $product->categories()->attach($category);

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

        // Product still exists
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_category_store_requires_name(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->post(route('admin.categories.store'), ['name' => '']);

        $response->assertSessionHasErrors(['name']);
    }
}
