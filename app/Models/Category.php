<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'is_active'])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Watches that have this category as their main category.
     */
    public function primaryProducts(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * All watches tagged with this category (a watch can have many categories).
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Only categories that are visible in the store.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Atmospheric luxury cover photo for this category card.
     * Priority: Custom dynamic override > Curated horological stock asset.
     */
    public function getCoverImageUrlAttribute(): string
    {
        if (! empty($this->custom_cover_image)) {
            return $this->custom_cover_image;
        }

        $slug = strtolower($this->slug ?? Str::slug($this->name));

        return match (true) {
            str_contains($slug, 'women') => asset('images/footer/cat_womens.jpg'),
            str_contains($slug, 'men') => asset('images/footer/cat_mens.jpg'),
            str_contains($slug, 'unisex') => asset('images/footer/cat_limited.jpg'),
            str_contains($slug, 'strap') => asset('images/footer/cat_womens.jpg'),
            str_contains($slug, 'metal') || str_contains($slug, 'chain') => asset('images/footer/cat_sports.jpg'),
            str_contains($slug, 'automatic') => asset('watch1.jpg'),
            str_contains($slug, 'analog') => asset('images/footer/cat_mens.jpg'),
            str_contains($slug, 'quartz') => asset('images/footer/cat_limited.jpg'),
            str_contains($slug, 'digital') => asset('images/make-banner.jpg'),
            default => asset('images/footer/cat_mens.jpg'),
        };
    }

    /**
     * Curated horological subtitle for category cards.
     * Priority: Custom dynamic override > Curated horological description.
     */
    public function getCuratedSubtitleAttribute(): string
    {
        if (! empty($this->custom_subtitle)) {
            return $this->custom_subtitle;
        }

        $slug = strtolower($this->slug ?? Str::slug($this->name));

        return match (true) {
            str_contains($slug, 'women') => 'Refined Elegance, Diamonds & Petite Dials',
            str_contains($slug, 'men') => 'Bold Aesthetics & Precision Engineering',
            str_contains($slug, 'unisex') => 'Timeless Proportions Designed for Every Wrist',
            str_contains($slug, 'strap') => 'Genuine Leather & High-Grade Rubber',
            str_contains($slug, 'metal') || str_contains($slug, 'chain') => 'Solid Stainless Steel & Titanium Bracelets',
            str_contains($slug, 'automatic') => 'Self-Winding Mechanical In-House Calibers',
            str_contains($slug, 'analog') => 'Classic Dial Complications & Swiss Heritage',
            str_contains($slug, 'quartz') => 'High-Frequency Quartz & Solar Precision',
            str_contains($slug, 'digital') => 'Shock-Resistant & Tactical Outdoor Watches',
            default => 'Curated Authentic Horology Collection',
        };
    }
}
