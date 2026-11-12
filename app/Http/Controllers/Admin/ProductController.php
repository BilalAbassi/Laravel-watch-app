<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\SpecAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of all products with search, filters, and pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $brandId = $request->query('brand_id');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');

        $query = Product::with(['brand', 'categories', 'images']);

        if ($search) {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('movement', 'like', "%{$search}%");
            });
        }

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        if ($categoryId) {
            $query->whereHas('categories', fn (Builder $c) => $c->where('categories.id', $categoryId));
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        } elseif ($status === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        } elseif ($status === 'featured') {
            $query->where('is_featured', true);
        }

        $products = $query->latest('id')->paginate(15)->withQueryString();

        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', [
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
            'search' => $search,
            'brandId' => $brandId,
            'categoryId' => $categoryId,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $specAttributes = SpecAttribute::active()
            ->with('values')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.products.create', [
            'brands' => $brands,
            'categories' => $categories,
            'specAttributes' => $specAttributes,
            'product' => new Product,
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = DB::transaction(function () use ($validated, $request): Product {
            $brand = Brand::findOrFail($validated['brand_id']);

            $slug = Str::slug($validated['title']);
            $baseSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            $sku = ! empty($validated['sku'])
                ? trim((string) $validated['sku'])
                : 'TY-'.strtoupper(substr($brand->name, 0, 3)).'-'.rand(1000, 9999);

            $primaryCategoryId = $validated['category_ids'][0] ?? null;

            $product = Product::create([
                'title' => $validated['title'],
                'slug' => $slug,
                'sku' => $sku,
                'brand_id' => $brand->id,
                'category_id' => $primaryCategoryId,
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'stock' => $validated['stock'],
                'movement' => $validated['movement'] ?? null,
                'case_size' => $validated['case_size'] ?? null,
                'case_material' => $validated['case_material'] ?? null,
                'strap_type' => $validated['strap_type'] ?? null,
                'glass' => $validated['glass'] ?? null,
                'water_resistance' => $validated['water_resistance'] ?? null,
                'warranty' => $validated['warranty'] ?? '2 Years Official Brand Warranty',
                'gender' => $validated['gender'] ?? 'Men\'s',
                'badge' => $validated['badge'] ?? null,
                'rating' => 5.00,
                'reviews_count' => 0,
                'description' => $validated['description'] ?? null,
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active', true),
            ]);

            $product->categories()->sync($validated['category_ids']);

            $this->syncProductSpecifications($product, $request);

            if ($request->hasFile('images')) {
                $uploadDir = public_path('uploads/watches');
                File::ensureDirectoryExists($uploadDir);

                $images = $request->file('images');
                $colorNames = $request->input('image_color_names', []);
                $colorCodes = $request->input('image_color_codes', []);

                foreach ($images as $index => $uploadedFile) {
                    $ext = $uploadedFile->getClientOriginalExtension() ?: 'webp';
                    $fileName = 'watch_'.$product->id.'_'.time().'_'.Str::random(6).'.'.$ext;
                    $uploadedFile->move($uploadDir, $fileName);

                    $colorName = ! empty($colorNames[$index]) ? trim((string) $colorNames[$index]) : null;
                    $colorCode = ! empty($colorCodes[$index]) ? trim((string) $colorCodes[$index]) : null;

                    $product->images()->create([
                        'image_path' => 'uploads/watches/'.$fileName,
                        'color_name' => $colorName,
                        'color_code' => $colorCode,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            return $product;
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Watch \"{$product->title}\" has been published successfully and is now live in the store.");
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $product->loadMissing(['brand', 'categories', 'images', 'specifications.attribute']);
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $specAttributes = SpecAttribute::active()
            ->with('values')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $productSpecs = $product->specifications->keyBy('spec_attribute_id')->map->value->all();

        return view('admin.products.edit', [
            'product' => $product,
            'brands' => $brands,
            'categories' => $categories,
            'specAttributes' => $specAttributes,
            'productSpecs' => $productSpecs,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $product): void {
            $brand = Brand::findOrFail($validated['brand_id']);

            if ($product->title !== $validated['title']) {
                $slug = Str::slug($validated['title']);
                $baseSlug = $slug;
                $counter = 1;
                while (Product::where('slug', $slug)->whereKeyNot($product->id)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }
                $product->slug = $slug;
            }

            if (! empty($validated['sku'])) {
                $product->sku = trim((string) $validated['sku']);
            }

            $primaryCategoryId = $validated['category_ids'][0] ?? null;

            $product->fill([
                'title' => $validated['title'],
                'brand_id' => $brand->id,
                'category_id' => $primaryCategoryId,
                'price' => $validated['price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'stock' => $validated['stock'],
                'movement' => $validated['movement'] ?? null,
                'case_size' => $validated['case_size'] ?? null,
                'case_material' => $validated['case_material'] ?? null,
                'strap_type' => $validated['strap_type'] ?? null,
                'glass' => $validated['glass'] ?? null,
                'water_resistance' => $validated['water_resistance'] ?? null,
                'warranty' => $validated['warranty'] ?? $product->warranty,
                'gender' => $validated['gender'] ?? $product->gender,
                'badge' => $validated['badge'] ?? null,
                'description' => $validated['description'] ?? null,
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
            ])->save();

            $product->categories()->sync($validated['category_ids']);

            $this->syncProductSpecifications($product, $request);

            // 1. Delete selected images
            if (! empty($validated['delete_image_ids'])) {
                $imagesToDelete = ProductImage::where('product_id', $product->id)
                    ->whereIn('id', $validated['delete_image_ids'])
                    ->get();

                foreach ($imagesToDelete as $img) {
                    if (Str::startsWith($img->image_path, 'uploads/watches/')) {
                        $fullPath = public_path($img->image_path);
                        if (File::exists($fullPath)) {
                            File::delete($fullPath);
                        }
                    }
                    $img->delete();
                }
            }

            // 2. Update colors for existing images
            if ($request->has('existing_image_colors')) {
                foreach ((array) $request->input('existing_image_colors') as $imageId => $colorData) {
                    ProductImage::where('product_id', $product->id)
                        ->where('id', $imageId)
                        ->update([
                            'color_name' => ! empty($colorData['name']) ? trim((string) $colorData['name']) : null,
                            'color_code' => ! empty($colorData['code']) ? trim((string) $colorData['code']) : null,
                        ]);
                }
            }

            // 3. Upload new images
            if ($request->hasFile('images')) {
                $uploadDir = public_path('uploads/watches');
                File::ensureDirectoryExists($uploadDir);

                $currentMaxSort = (int) $product->images()->max('sort_order');
                $newFiles = $request->file('images');
                $newColorNames = $request->input('new_image_color_names', []);
                $newColorCodes = $request->input('new_image_color_codes', []);

                foreach ($newFiles as $i => $file) {
                    $ext = $file->getClientOriginalExtension() ?: 'webp';
                    $fileName = 'watch_'.$product->id.'_'.time().'_'.Str::random(6).'.'.$ext;
                    $file->move($uploadDir, $fileName);

                    $colorName = ! empty($newColorNames[$i]) ? trim((string) $newColorNames[$i]) : null;
                    $colorCode = ! empty($newColorCodes[$i]) ? trim((string) $newColorCodes[$i]) : null;

                    $product->images()->create([
                        'image_path' => 'uploads/watches/'.$fileName,
                        'color_name' => $colorName,
                        'color_code' => $colorCode,
                        'is_primary' => false,
                        'sort_order' => $currentMaxSort + $i + 1,
                    ]);
                }
            }

            // 4. Update primary image
            if (! empty($validated['primary_image_id'])) {
                ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
                ProductImage::where('product_id', $product->id)
                    ->where('id', $validated['primary_image_id'])
                    ->update(['is_primary' => true]);
            } else {
                $hasPrimary = ProductImage::where('product_id', $product->id)->where('is_primary', true)->exists();
                if (! $hasPrimary) {
                    $firstImg = ProductImage::where('product_id', $product->id)->orderBy('sort_order')->first();
                    if ($firstImg) {
                        $firstImg->update(['is_primary' => true]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Watch \"{$product->title}\" has been updated successfully.");
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $title = $product->title;

        foreach ($product->images as $img) {
            if (Str::startsWith($img->image_path, 'uploads/watches/')) {
                $filePath = public_path($img->image_path);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Watch \"{$title}\" has been permanently deleted.");
    }

    /**
     * Synchronize dynamic specification attributes and custom specifications.
     */
    private function syncProductSpecifications(Product $product, Request $request): void
    {
        $submittedSpecs = $request->input('specs', []);
        $attributesById = SpecAttribute::all()->keyBy('id');

        if (is_array($submittedSpecs)) {
            foreach ($submittedSpecs as $attrId => $val) {
                $cleaned = trim((string) $val);
                if ($cleaned !== '' && isset($attributesById[$attrId])) {
                    ProductSpecification::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'spec_attribute_id' => $attrId,
                        ],
                        ['value' => $cleaned]
                    );

                    $slug = $attributesById[$attrId]->slug;
                    if ($slug === 'movement') {
                        $product->movement = $cleaned;
                    } elseif ($slug === 'case-size') {
                        $product->case_size = $cleaned;
                    } elseif ($slug === 'case-material') {
                        $product->case_material = $cleaned;
                    } elseif ($slug === 'strap-type') {
                        $product->strap_type = $cleaned;
                    } elseif ($slug === 'crystal-glass') {
                        $product->glass = $cleaned;
                    } elseif ($slug === 'water-resistance') {
                        $product->water_resistance = $cleaned;
                    } elseif ($slug === 'warranty') {
                        $product->warranty = $cleaned;
                    }
                } elseif ($cleaned === '') {
                    ProductSpecification::where('product_id', $product->id)
                        ->where('spec_attribute_id', $attrId)
                        ->delete();
                }
            }
        }

        $customSpecs = $request->input('custom_specs', []);
        if (is_array($customSpecs)) {
            foreach ($customSpecs as $cSpec) {
                $cName = trim((string) ($cSpec['name'] ?? ''));
                $cVal = trim((string) ($cSpec['value'] ?? ''));
                if ($cName !== '' && $cVal !== '') {
                    $cSlug = Str::slug($cName);
                    $attr = SpecAttribute::firstOrCreate(
                        ['slug' => $cSlug],
                        ['name' => $cName, 'sort_order' => 50, 'is_active' => true]
                    );

                    ProductSpecification::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'spec_attribute_id' => $attr->id,
                        ],
                        ['value' => $cVal]
                    );
                }
            }
        }

        $product->save();
    }
}
