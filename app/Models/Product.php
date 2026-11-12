<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'title', 'slug', 'sku', 'brand_id', 'category_id', 'price', 'sale_price', 'stock',
    'movement', 'case_size', 'case_material', 'strap_type', 'glass', 'water_resistance',
    'warranty', 'gender', 'badge', 'rating', 'reviews_count', 'description',
    'is_featured', 'is_active',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'rating' => 'decimal:2',
            'stock' => 'integer',
            'reviews_count' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The brand that makes this watch.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * The main category of this watch.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * All categories of this watch (for example STRAP, QUARTZ and ANALOG).
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->orderBy('categories.id');
    }

    /**
     * Photos of this watch. The main photo comes first.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * The primary or main display photo of this watch.
     */
    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->ofMany([
            'is_primary' => 'max',
            'sort_order' => 'min',
            'id' => 'min',
        ]);
    }

    /**
     * Direct URL to the primary watch photo.
     */
    protected function primaryImageUrl(): Attribute
    {
        return Attribute::get(function (): string {
            if ($this->relationLoaded('primaryImage') && $this->primaryImage) {
                return $this->primaryImage->url;
            }

            if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
                $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();

                return $primary?->url ?? asset('watches/astra-black.png');
            }

            $img = $this->images()->orderByDesc('is_primary')->orderBy('sort_order')->first();

            return $img?->url ?? asset('watches/astra-black.png');
        });
    }

    /**
     * Color variants mapped to photos for this watch.
     *
     * @return list<array{id: int|null, name: string, color: string, border: string, image: string}>
     */
    protected function colorVariants(): Attribute
    {
        return Attribute::get(function (): array {
            $images = $this->relationLoaded('images') ? $this->images : $this->images()->get();
            $variants = [];

            $fallbackPalette = [
                ['name' => 'Obsidian Black', 'code' => '#14171a'],
                ['name' => 'Steel Gray', 'code' => '#2a2e34'],
                ['name' => 'Rose Bronze', 'code' => '#c99b82'],
                ['name' => 'Emerald Teal', 'code' => '#0d2124'],
                ['name' => 'Midnight Blue', 'code' => '#1e3a8a'],
            ];

            foreach ($images as $index => $img) {
                $name = $img->color_name;
                $code = $img->color_code;

                if (empty($name) || empty($code)) {
                    $path = (string) $img->image_path;
                    if (str_contains($path, 'astra-black')) {
                        $name = $name ?: 'Obsidian Black';
                        $code = $code ?: '#14171a';
                    } elseif (str_contains($path, 'nicva-shin')) {
                        $name = $name ?: 'Steel Gray';
                        $code = $code ?: '#2a2e34';
                    } elseif (str_contains($path, 'saisae-moonphase')) {
                        $name = $name ?: 'Rose Bronze';
                        $code = $code ?: '#c99b82';
                    } elseif (str_contains($path, 'titlon-dive')) {
                        $name = $name ?: 'Emerald Teal';
                        $code = $code ?: '#0d2124';
                    } else {
                        $paletteItem = $fallbackPalette[$index % count($fallbackPalette)];
                        $name = $name ?: $paletteItem['name'];
                        $code = $code ?: $paletteItem['code'];
                    }
                }

                $variants[] = [
                    'id' => $img->id,
                    'name' => (string) $name,
                    'color' => (string) $code,
                    'border' => $this->lightenHex((string) $code, 25),
                    'image' => $img->url,
                ];
            }

            if (empty($variants)) {
                $primaryUrl = $this->primary_image_url;
                $variants[] = [
                    'id' => null,
                    'name' => 'Obsidian Black',
                    'color' => '#14171a',
                    'border' => '#32373e',
                    'image' => $primaryUrl,
                ];
            }

            return $variants;
        });
    }

    /**
     * Compute a lighter border hex for a swatch color.
     */
    private function lightenHex(string $hex, int $percent = 20): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        if (strlen($hex) !== 6) {
            return '#444c56';
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = min(255, (int) ($r + ($percent * 255 / 100)));
        $g = min(255, (int) ($g + ($percent * 255 / 100)));
        $b = min(255, (int) ($b + ($percent * 255 / 100)));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    /**
     * Dynamic specifications attached to this watch.
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class);
    }

    /**
     * Specification attributes linked to this watch.
     */
    public function specAttributes(): BelongsToMany
    {
        return $this->belongsToMany(SpecAttribute::class, 'product_specifications')
            ->withPivot('value')
            ->withTimestamps();
    }

    /**
     * Only watches that are visible in the store.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * The price a customer pays: the sale price when there is one, otherwise the normal price.
     */
    protected function currentPrice(): Attribute
    {
        return Attribute::get(
            fn (): float => (float) ($this->sale_price ?? $this->price)
        );
    }

    /**
     * Convert this watch to the array shape used by the shop, product and checkout pages.
     *
     * @return array<string, mixed>
     */
    public function toCatalogArray(): array
    {
        $this->loadMissing(['brand', 'categories', 'images']);

        $regularPrice = (float) $this->price;
        $currentPrice = $this->current_price;
        $isOnSale = $this->sale_price !== null && $currentPrice < $regularPrice;

        $imageUrls = $this->images->map(fn (ProductImage $image): string => $image->url)->values()->all();
        $primaryImage = $imageUrls[0] ?? asset('watches/astra-black.png');

        if (count($imageUrls) < 2) {
            $imageUrls = [
                $primaryImage,
                asset('watches/astra-black.png'),
                asset('watches/titlon-dive.png'),
                asset('watches/saisae-moonphase.png'),
            ];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'brand' => $this->brand->name,
            'categories' => $this->categories->pluck('name')->all(),
            'price' => $currentPrice,
            'original_price' => $isOnSale ? $regularPrice : $currentPrice,
            'discount' => $isOnSale ? (int) floor(($regularPrice - $currentPrice) / $regularPrice * 100).'% OFF' : '',
            'badge' => (string) $this->badge,
            'rating' => (float) $this->rating,
            'reviews_count' => $this->reviews_count,
            'image' => $primaryImage,
            'images' => $imageUrls,
            'variants' => $this->color_variants,
            'description' => (string) $this->description,
            'gender' => (string) $this->gender,
            'featured' => (int) $this->is_featured,
            'stock' => $this->stock,
            'specs' => [
                'case_diameter' => (string) $this->case_size,
                'case_material' => (string) $this->case_material,
                'strap_type' => (string) $this->strap_type,
                'movement' => (string) $this->movement,
                'water_resistance' => (string) $this->water_resistance,
                'glass' => (string) $this->glass,
                'warranty' => (string) $this->warranty,
            ],
        ];
    }
}
