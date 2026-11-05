<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['page', 'section_key', 'title', 'subtitle', 'content', 'image_path', 'sort_order', 'is_active'])]
class PageSection extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Resolved URL for uploaded or default section image.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if (! empty($this->image_path)) {
                if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
                    return $this->image_path;
                }

                $clean = ltrim($this->image_path, '/\\');
                if (file_exists(public_path($clean))) {
                    return asset($clean);
                }
            }

            // Reliable default fallbacks verified to exist in public/
            return match ($this->section_key) {
                'hero_banner' => asset('hero-watch.png'),
                'promo_banner' => asset('images/make-banner.jpg'),
                'seo' => asset('images/timeyar-circle-emblem.png'),
                default => asset('hero-watch.png'),
            };
        });
    }

    /**
     * Scope for a specific page (e.g. 'home', 'shop').
     */
    #[Scope]
    protected function forPage(Builder $query, string $page = 'home'): void
    {
        $query->where('page', $page);
    }

    /**
     * Scope only active visible sections.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope ordered by sort priority.
     */
    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}
