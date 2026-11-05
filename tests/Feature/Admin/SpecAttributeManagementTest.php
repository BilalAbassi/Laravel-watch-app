<?php

namespace Tests\Feature\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SpecAttribute;
use App\Models\SpecAttributeValue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpecAttributeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_specifications(): void
    {
        $this->get(route('admin.specifications.index'))
            ->assertRedirect(route('login'));

        $this->get(route('admin.specifications.create'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_receives_forbidden_when_accessing_admin_specifications(): void
    {
        $customer = User::factory()->create(['is_admin' => false]);

        $this->actingAs($customer)
            ->get(route('admin.specifications.index'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('admin.specifications.create'))
            ->assertForbidden();
    }

    public function test_admin_can_view_specifications_list_and_create_page(): void
    {
        $admin = User::factory()->admin()->create();
        $attr = SpecAttribute::factory()->create([
            'name' => 'Power Reserve',
            'slug' => 'power-reserve',
        ]);
        SpecAttributeValue::create([
            'spec_attribute_id' => $attr->id,
            'value' => '80 Hours',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.specifications.index'));

        $response->assertOk()
            ->assertSee('Technical Specifications')
            ->assertSee('Power Reserve')
            ->assertSee('80 Hours');

        $this->actingAs($admin)
            ->get(route('admin.specifications.create'))
            ->assertOk()
            ->assertSee('Add Specification Attribute');
    }

    public function test_admin_can_store_new_specification_attribute_with_options(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.specifications.store'), [
            'name' => 'Bezel Material',
            'slug' => 'bezel-material',
            'sort_order' => 5,
            'is_active' => '1',
            'values' => [
                'Ceramic Unidirectional Rotating',
                'Tachymeter Scale Steel',
            ],
        ]);

        $response->assertRedirect(route('admin.specifications.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('spec_attributes', [
            'name' => 'Bezel Material',
            'slug' => 'bezel-material',
        ]);

        $attribute = SpecAttribute::where('slug', 'bezel-material')->firstOrFail();
        $this->assertDatabaseHas('spec_attribute_values', [
            'spec_attribute_id' => $attribute->id,
            'value' => 'Ceramic Unidirectional Rotating',
        ]);
    }

    public function test_admin_can_edit_attribute_and_manage_predefined_options(): void
    {
        $admin = User::factory()->admin()->create();
        $attribute = SpecAttribute::create([
            'name' => 'Glass',
            'slug' => 'glass',
        ]);
        $val1 = SpecAttributeValue::create([
            'spec_attribute_id' => $attribute->id,
            'value' => 'Mineral',
        ]);
        $val2 = SpecAttributeValue::create([
            'spec_attribute_id' => $attribute->id,
            'value' => 'Old Plastic',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.specifications.edit', $attribute))
            ->assertOk()
            ->assertSee('Edit Specification: Glass');

        $response = $this->actingAs($admin)->put(route('admin.specifications.update', $attribute), [
            'name' => 'Crystal Glass',
            'slug' => 'crystal-glass',
            'sort_order' => 1,
            'is_active' => '1',
            'values' => [
                $val1->id => 'Sapphire Crystal (Scratch Resistant)',
            ],
            'delete_value_ids' => [
                $val2->id,
            ],
            'new_values' => [
                'Hardlex Crystal',
            ],
        ]);

        $response->assertRedirect(route('admin.specifications.index'));

        $this->assertDatabaseHas('spec_attributes', [
            'id' => $attribute->id,
            'name' => 'Crystal Glass',
            'slug' => 'crystal-glass',
        ]);

        // val1 was updated
        $this->assertDatabaseHas('spec_attribute_values', [
            'id' => $val1->id,
            'value' => 'Sapphire Crystal (Scratch Resistant)',
        ]);

        // val2 was deleted
        $this->assertDatabaseMissing('spec_attribute_values', [
            'id' => $val2->id,
        ]);

        // new value was inserted
        $this->assertDatabaseHas('spec_attribute_values', [
            'spec_attribute_id' => $attribute->id,
            'value' => 'Hardlex Crystal',
        ]);
    }

    public function test_admin_can_delete_specification_attribute(): void
    {
        $admin = User::factory()->admin()->create();
        $attribute = SpecAttribute::create(['name' => 'Temporary Spec', 'slug' => 'temp-spec']);

        $response = $this->actingAs($admin)->delete(route('admin.specifications.destroy', $attribute));

        $response->assertRedirect(route('admin.specifications.index'));
        $this->assertDatabaseMissing('spec_attributes', ['id' => $attribute->id]);
    }

    public function test_product_creation_with_dynamic_specs_attaches_properly(): void
    {
        $admin = User::factory()->admin()->create();
        $brand = Brand::factory()->create(['name' => 'Omega']);
        $category = Category::factory()->create(['name' => 'AUTOMATIC']);
        $movementAttr = SpecAttribute::create(['name' => 'Movement', 'slug' => 'movement']);
        $powerAttr = SpecAttribute::create(['name' => 'Power Reserve', 'slug' => 'power-reserve']);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'title' => 'Omega Seamaster 300m',
            'sku' => 'TY-OMG-300',
            'brand_id' => $brand->id,
            'category_ids' => [$category->id],
            'price' => 5400.00,
            'stock' => 2,
            'specs' => [
                $movementAttr->id => 'Co-Axial Master Chronometer 8800',
                $powerAttr->id => '55 Hours',
            ],
            'custom_specs' => [
                ['name' => 'Bezel', 'value' => 'Blue Ceramic with White Enamel Scale'],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('slug', 'omega-seamaster-300m')->firstOrFail();

        // Verify product specifications table records
        $this->assertDatabaseHas('product_specifications', [
            'product_id' => $product->id,
            'spec_attribute_id' => $movementAttr->id,
            'value' => 'Co-Axial Master Chronometer 8800',
        ]);
        $this->assertDatabaseHas('product_specifications', [
            'product_id' => $product->id,
            'spec_attribute_id' => $powerAttr->id,
            'value' => '55 Hours',
        ]);

        // Verify direct product movement column is also synchronized
        $this->assertEquals('Co-Axial Master Chronometer 8800', $product->movement);

        // Verify custom spec was automatically created as an attribute
        $this->assertDatabaseHas('spec_attributes', ['slug' => 'bezel']);
    }
}
