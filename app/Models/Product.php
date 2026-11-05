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
