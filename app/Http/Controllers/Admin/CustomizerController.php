<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\PageSection;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomizerController extends Controller
{
    /**
     * Show the interactive Page Builder & Section Customizer with Side Panel Form.
     */
    public function index(Request $request): View
    {
        $page = (string) $request->query('page', 'home');

        $sections = PageSection::where('page', $page)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('section_key');

        $categories = Category::active()->orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();
        $featuredWatches = Product::active()->latest('id')->take(8)->get();
        $availableWatches = Product::active()->with('primaryImage', 'brand')->orderBy('title')->get();

        return view('admin.customizer.index', [
            'page' => $page,
            'sections' => $sections,
            'categories' => $categories,
            'brands' => $brands,
            'featuredWatches' => $featuredWatches,
            'availableWatches' => $availableWatches,
        ]);
    }

    /**
     * Update page content, banners, images, category section, and section order from side panel.
     */
    public function update(Request $request): RedirectResponse
    {
        $page = (string) $request->input('page', 'home');

        $validated = $request->validate([
            'page' => ['required', 'string', 'in:home,shop'],
            'seo.title' => ['nullable', 'string', 'max:255'],
            'seo.subtitle' => ['nullable', 'string', 'max:500'],
            'seo.meta_description' => ['nullable', 'string', 'max:500'],
            'seo.keywords' => ['nullable', 'string', 'max:500'],
            'seo_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif,avif,svg', 'max:20480'],

            'hero.badge' => ['nullable', 'string', 'max:100'],
            'hero.title' => ['nullable', 'string', 'max:255'],
            'hero.subtitle' => ['nullable', 'string', 'max:255'],
            'hero.heading_accent_1' => ['nullable', 'string', 'max:100'],
            'hero.heading_accent_2' => ['nullable', 'string', 'max:100'],
            'hero.description' => ['nullable', 'string', 'max:1000'],
            'hero.button_text' => ['nullable', 'string', 'max:100'],
            'hero.button_url' => ['nullable', 'string', 'max:255'],
            'hero.secondary_button_text' => ['nullable', 'string', 'max:100'],
            'hero.secondary_button_url' => ['nullable', 'string', 'max:255'],
            'hero.position.desktop_left' => ['nullable', 'string', 'max:50'],
            'hero.position.desktop_top' => ['nullable', 'string', 'max:50'],
            'hero.position.desktop_width' => ['nullable', 'string', 'max:50'],
            'hero.position.tablet_top' => ['nullable', 'string', 'max:50'],
            'hero.position.tablet_width' => ['nullable', 'string', 'max:50'],
            'hero.position.mobile_top' => ['nullable', 'string', 'max:50'],
            'hero.position.mobile_width' => ['nullable', 'string', 'max:50'],
            'hero_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif,avif,svg', 'max:20480'],
            'hero_catalog_image' => ['nullable', 'string', 'max:500'],
            'hero_reset_image' => ['nullable', 'boolean'],

            'shop_by_category.title' => ['nullable', 'string', 'max:255'],
            'shop_by_category.subtitle' => ['nullable', 'string', 'max:500'],
            'shop_by_category.badge' => ['nullable', 'string', 'max:100'],
            'shop_by_category.category_ids' => ['nullable', 'array'],
            'shop_by_category.category_ids.*' => ['integer', 'exists:categories,id'],
            'shop_by_category.category_meta' => ['nullable', 'array'],
            'shop_by_category.category_meta.*.subtitle' => ['nullable', 'string', 'max:255'],
            'shop_by_category.category_meta.*.image_source' => ['nullable', 'string', 'in:curated,watch,custom'],
            'shop_by_category.category_meta.*.image' => ['nullable', 'string', 'max:500'],
            'category_images.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif,avif,svg', 'max:20480'],

            'featured_watches.title' => ['nullable', 'string', 'max:255'],
            'featured_watches.subtitle' => ['nullable', 'string', 'max:500'],
            'featured_watches.badge' => ['nullable', 'string', 'max:100'],
            'featured_watches.limit' => ['nullable', 'integer', 'min:1', 'max:24'],

            'promo.title' => ['nullable', 'string', 'max:255'],
            'promo.subtitle' => ['nullable', 'string', 'max:500'],
            'promo.badge' => ['nullable', 'string', 'max:100'],
            'promo.accent_badge' => ['nullable', 'string', 'max:100'],
            'promo.button_text' => ['nullable', 'string', 'max:100'],
            'promo.button_url' => ['nullable', 'string', 'max:255'],
            'promo_image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,gif,avif,svg', 'max:20480'],

            'brand_showcase.title' => ['nullable', 'string', 'max:255'],
            'brand_showcase.subtitle' => ['nullable', 'string', 'max:500'],
            'brand_showcase.badge' => ['nullable', 'string', 'max:100'],

            'section_order' => ['nullable', 'array'],
            'section_order.*' => ['string'],
            'section_visibility' => ['nullable', 'array'],
            'section_visibility.*' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $page, $validated): void {
            $uploadDir = public_path('uploads/pages');
            File::ensureDirectoryExists($uploadDir);

            // 1. Update SEO Section
            $seoSection = PageSection::firstOrNew(['page' => $page, 'section_key' => 'seo']);
            $seoSection->title = $validated['seo']['title'] ?? $seoSection->title;
            $seoSection->subtitle = $validated['seo']['subtitle'] ?? $seoSection->subtitle;
            $seoContent = (array) ($seoSection->content ?? []);
            $seoContent['meta_description'] = $validated['seo']['meta_description'] ?? ($seoContent['meta_description'] ?? '');
            $seoContent['keywords'] = $validated['seo']['keywords'] ?? ($seoContent['keywords'] ?? '');

            if ($request->hasFile('seo_image')) {
                $file = $request->file('seo_image');
                $ext = $file->getClientOriginalExtension() ?: 'png';
                $name = 'seo_'.$page.'_'.time().'_'.Str::random(4).'.'.$ext;
                $file->move($uploadDir, $name);
                $seoSection->image_path = 'uploads/pages/'.$name;
            }

            $seoSection->content = $seoContent;
            $seoSection->save();

            // 2. Update Hero Banner Section
            $hero = PageSection::firstOrNew(['page' => $page, 'section_key' => 'hero_banner']);
            $hero->title = $validated['hero']['title'] ?? $hero->title;
            $hero->subtitle = $validated['hero']['subtitle'] ?? $hero->subtitle;
            $heroContent = (array) ($hero->content ?? []);
            foreach (['badge', 'heading_accent_1', 'heading_accent_2', 'description', 'button_text', 'button_url', 'secondary_button_text', 'secondary_button_url'] as $key) {
                if (isset($validated['hero'][$key])) {
                    $heroContent[$key] = $validated['hero'][$key];
                }
            }

            if (isset($validated['hero']['position']) && is_array($validated['hero']['position'])) {
                $heroContent['position'] = array_merge($heroContent['position'] ?? [], $validated['hero']['position']);
            }

            // Hero Watch Image Processing (Upload, Pick Catalog Watch, or Reset Default)
            if ($request->boolean('hero_reset_image') || $request->input('hero_catalog_image') === 'RESET_DEFAULT') {
                $hero->image_path = 'hero-watch.png';
            } elseif ($request->hasFile('hero_image')) {
                $file = $request->file('hero_image');
                $ext = $file->getClientOriginalExtension() ?: 'png';
                $name = 'hero_'.$page.'_'.time().'_'.Str::random(4).'.'.$ext;
                $file->move($uploadDir, $name);
                $hero->image_path = 'uploads/pages/'.$name;
            } elseif ($request->filled('hero_catalog_image')) {
                $catalogImg = (string) $request->input('hero_catalog_image');
                $cleanPath = ltrim(parse_url($catalogImg, PHP_URL_PATH) ?? '', '/\\');
                if (! empty($cleanPath) && file_exists(public_path($cleanPath))) {
                    $hero->image_path = $cleanPath;
                }
            }

            $hero->content = $heroContent;
            $hero->save();

            // 3. Update Shop By Category Section
            $catSec = PageSection::firstOrNew(['page' => $page, 'section_key' => 'shop_by_category']);
            $catSec->title = $validated['shop_by_category']['title'] ?? $catSec->title;
            $catSec->subtitle = $validated['shop_by_category']['subtitle'] ?? $catSec->subtitle;
            $catContent = (array) ($catSec->content ?? []);
            $catContent['badge'] = $validated['shop_by_category']['badge'] ?? ($catContent['badge'] ?? 'SHOP BY CATEGORY');
            $catContent['category_ids'] = $validated['shop_by_category']['category_ids'] ?? ($catContent['category_ids'] ?? Category::pluck('id')->all());

            $existingMeta = (array) ($catContent['category_meta'] ?? []);
            $newMeta = (array) ($validated['shop_by_category']['category_meta'] ?? []);

            // Handle per-category uploaded images if provided
            if ($request->hasFile('category_images')) {
                foreach ($request->file('category_images') as $catId => $catFile) {
                    if ($catFile) {
                        $ext = $catFile->getClientOriginalExtension() ?: 'png';
                        $name = 'cat_'.$catId.'_'.time().'_'.Str::random(4).'.'.$ext;
                        $catFile->move($uploadDir, $name);
                        $newMeta[$catId]['image'] = 'uploads/pages/'.$name;
                        $newMeta[$catId]['image_source'] = 'custom';
                    }
                }
            }

            // Retain existing image if user didn't re-upload
            foreach ($newMeta as $catId => $meta) {
                if (empty($meta['image']) && ! empty($existingMeta[$catId]['image'])) {
                    $newMeta[$catId]['image'] = $existingMeta[$catId]['image'];
                }
            }

            $catContent['category_meta'] = array_replace_recursive($existingMeta, $newMeta);
            $catSec->content = $catContent;
            $catSec->save();

            // 4. Update Featured Watches Section
            $featSec = PageSection::firstOrNew(['page' => $page, 'section_key' => 'featured_watches']);
            $featSec->title = $validated['featured_watches']['title'] ?? $featSec->title;
            $featSec->subtitle = $validated['featured_watches']['subtitle'] ?? $featSec->subtitle;
            $featContent = (array) ($featSec->content ?? []);
            $featContent['badge'] = $validated['featured_watches']['badge'] ?? ($featContent['badge'] ?? 'FEATURED RELEASES');
            $featContent['limit'] = (int) ($validated['featured_watches']['limit'] ?? 8);
            $featSec->content = $featContent;
            $featSec->save();

            // 5. Update Promo Story Banner
            $promo = PageSection::firstOrNew(['page' => $page, 'section_key' => 'promo_banner']);
            $promo->title = $validated['promo']['title'] ?? $promo->title;
            $promo->subtitle = $validated['promo']['subtitle'] ?? $promo->subtitle;
            $promoContent = (array) ($promo->content ?? []);
            foreach (['badge', 'accent_badge', 'button_text', 'button_url'] as $key) {
                if (isset($validated['promo'][$key])) {
                    $promoContent[$key] = $validated['promo'][$key];
                }
            }

            if ($request->hasFile('promo_image')) {
                $file = $request->file('promo_image');
                $ext = $file->getClientOriginalExtension() ?: 'png';
                $name = 'promo_'.$page.'_'.time().'_'.Str::random(4).'.'.$ext;
                $file->move($uploadDir, $name);
                $promo->image_path = 'uploads/pages/'.$name;
            }

            $promo->content = $promoContent;
            $promo->save();

            // 6. Update Brand Showcase Section
            $brandSec = PageSection::firstOrNew(['page' => $page, 'section_key' => 'brand_showcase']);
            $brandSec->title = $validated['brand_showcase']['title'] ?? $brandSec->title;
            $brandSec->subtitle = $validated['brand_showcase']['subtitle'] ?? $brandSec->subtitle;
            $brandContent = (array) ($brandSec->content ?? []);
            $brandContent['badge'] = $validated['brand_showcase']['badge'] ?? ($brandContent['badge'] ?? 'OFFICIAL BRANDS');
            $brandSec->content = $brandContent;
            $brandSec->save();

            // 7. Update Section Below Section (Order & Visibility)
            $orderList = $request->input('section_order', []);
            $visibilityList = $request->input('section_visibility', []);

            if (is_array($orderList)) {
                foreach ($orderList as $index => $secKey) {
                    $sec = PageSection::where('page', $page)->where('section_key', $secKey)->first();
                    if ($sec) {
                        $sec->sort_order = $index + 1;
                        $sec->is_active = isset($visibilityList[$secKey]);
                        $sec->save();
                    }
                }
            }
        });

        return redirect()
            ->route('admin.customizer.index', ['page' => $page])
            ->with('success', 'Page content, SEO, banners, and sections have been published live successfully!');
    }
}
