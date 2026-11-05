<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\PageSection;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the dynamic TIMEYRAH homepage with customized sections, banners, and SEO.
     */
    public function __invoke(): View
    {
        try {
            $sections = PageSection::forPage('home')
                ->ordered()
                ->get()
                ->keyBy('section_key');

            $activeSections = PageSection::forPage('home')
                ->active()
                ->where('section_key', '!=', 'seo')
                ->ordered()
                ->get();
        } catch (\Throwable $e) {
            $sections = collect();
            $activeSections = collect();
        }

        $shopByCategorySec = $sections['shop_by_category'] ?? null;
        $categoryMeta = $shopByCategorySec->content['category_meta'] ?? [];
        $featuredCategoryIds = $shopByCategorySec->content['category_ids'] ?? [];

        $categoriesQuery = Category::active()
            ->withCount('products')
            ->with(['products' => fn ($q) => $q->active()->with('images')->latest('id')]);

        if (! empty($featuredCategoryIds)) {
            $categoriesQuery->whereIn('id', $featuredCategoryIds);
        }

        $categories = $categoriesQuery->get();

        // Respect custom arrangement order if saved, or sort by name
        if (! empty($featuredCategoryIds)) {
            $orderMap = array_flip(array_values($featuredCategoryIds));
            $categories = $categories->sortBy(fn ($cat) => $orderMap[$cat->id] ?? 999)->values();
        } else {
            $categories = $categories->sortBy('name')->values();
        }

        // Apply dynamic customization per category card (custom subtitle, custom image, or dynamic watch photo)
        foreach ($categories as $cat) {
            $meta = $categoryMeta[$cat->id] ?? [];
            if (! empty($meta['subtitle'])) {
                $cat->custom_subtitle = $meta['subtitle'];
            }

            if (! empty($meta['image'])) {
                $cat->custom_cover_image = str_starts_with($meta['image'], 'http') ? $meta['image'] : asset($meta['image']);
            } elseif (($meta['image_source'] ?? '') === 'watch') {
                $watchImage = $cat->products->first(fn ($p) => ! empty($p->primary_image_url))?->primary_image_url;
                if ($watchImage) {
                    $cat->custom_cover_image = $watchImage;
                }
            }
        }

        $featuredLimit = (int) ($sections['featured_watches']->content['limit'] ?? 8);
        $featuredWatches = Product::active()
            ->where('is_featured', true)
            ->latest('id')
            ->take($featuredLimit)
            ->get();

        if ($featuredWatches->isEmpty()) {
            $featuredWatches = Product::active()->latest('id')->take($featuredLimit)->get();
        }

        $brands = Brand::active()->orderBy('name')->get();

        return view('home', [
            'sections' => $sections,
            'activeSections' => $activeSections,
            'categories' => $categories,
            'featuredWatches' => $featuredWatches,
            'brands' => $brands,
        ]);
    }
}
