<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\PageSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $catIds = Category::pluck('id')->all();

            $sections = [
                [
                    'page' => 'home',
                    'section_key' => 'seo',
                    'title' => 'TIMEYRAH — Precision in Motion | Luxury & Everyday Watches Pakistan',
                    'subtitle' => 'Official online boutique for authentic Swiss, Japanese, and international horology in Pakistan.',
                    'content' => [
                        'meta_description' => 'Shop 100% original watches in Pakistan. Featuring Rado, Citizen, Seiko, Casio G-Shock, and Tissot with official brand warranty and Cash on Delivery across Karachi, Lahore, Islamabad, and nationwide.',
                        'keywords' => 'watches pakistan, luxury watches karachi, original watches lahore, casio g shock pakistan, seiko watches, rado captain cook, cash on delivery watches pakistan',
                    ],
                    'image_path' => 'images/timeyar-circle-emblem.png',
                    'sort_order' => 0,
                    'is_active' => true,
                ],
                [
                    'page' => 'home',
                    'section_key' => 'hero_banner',
                    'title' => 'TIMEYRAH.',
                    'subtitle' => 'More than Just a Watch. Experience the Caliber.',
                    'content' => [
                        'badge' => 'TIME-REDEFINED • 100% ORIGINAL',
                        'heading_accent_1' => 'PRECISION IN',
                        'heading_accent_2' => 'MOTION',
                        'description' => 'At TIMEYRAH, we believe a watch is more than an accessory — it is a symbol of style, premium quality, and timeless craftsmanship with nationwide delivery across Pakistan.',
                        'button_text' => 'Explore The Collection',
                        'button_url' => '#collection',
                        'secondary_button_text' => 'Shop All Watches',
                        'secondary_button_url' => '/shop',
                    ],
                    'image_path' => 'hero-watch.png',
                    'sort_order' => 1,
                    'is_active' => true,
                ],
                [
                    'page' => 'home',
                    'section_key' => 'shop_by_category',
                    'title' => 'THE EXCLUSIVE HOROLOGY COLLECTION',
                    'subtitle' => 'Explore iconic timepieces curated across luxury categories.',
                    'content' => [
                        'badge' => 'SHOP BY CATEGORY',
                        'category_ids' => $catIds,
                    ],
                    'image_path' => null,
                    'sort_order' => 2,
                    'is_active' => true,
                ],
                [
                    'page' => 'home',
                    'section_key' => 'featured_watches',
                    'title' => 'CURATED ATELIER TIMEPIECES',
                    'subtitle' => 'Handpicked precision movements and iconic complications available with nationwide Cash on Delivery.',
                    'content' => [
                        'badge' => 'FEATURED RELEASES',
                        'limit' => 8,
                    ],
                    'image_path' => null,
                    'sort_order' => 3,
                    'is_active' => true,
                ],
                [
                    'page' => 'home',
                    'section_key' => 'promo_banner',
                    'title' => 'MAKE YOUR MARK WITH TIMEYRAH',
                    'subtitle' => 'Every second tells a story. Craft yours with an authentic timepiece delivered to your doorstep.',
                    'content' => [
                        'badge' => 'CRAFTSMANSHIP & HERITAGE',
                        'button_text' => 'Discover The Atelier',
                        'button_url' => '/shop',
                        'accent_badge' => 'OFFICIAL WARRANTY INCLUDED',
                    ],
                    'image_path' => 'watches/astra-black.png',
                    'sort_order' => 4,
                    'is_active' => true,
                ],
                [
                    'page' => 'home',
                    'section_key' => 'brand_showcase',
                    'title' => 'ICONIC WATCHMAKERS & MANUFACTURES',
                    'subtitle' => 'Explore timepieces from Rado, Citizen, Seiko, Casio, Fossil, and premium ateliers.',
                    'content' => [
                        'badge' => 'OFFICIAL BRANDS',
                    ],
                    'image_path' => null,
                    'sort_order' => 5,
                    'is_active' => true,
                ],
                [
                    'page' => 'home',
                    'section_key' => 'footer_section',
                    'title' => 'Find Your Style',
                    'subtitle' => 'Curated horological collections across luxury materials and movements.',
                    'content' => [
                        'badge' => 'SHOP BY CATEGORY',
                        'show_discount_badge' => true,
                        'discount_text' => 'Save up to',
                        'discount_percent' => '50%',
                        'discount_url' => '/shop',
                        'category_ids' => [],
                        'cards' => [
                            [
                                'title' => 'Automatic Watches',
                                'button_text' => 'Shop Now',
                                'link_url' => '/shop?category=automatic',
                                'image' => 'images/footer/cat_sports.jpg',
                                'accent_color' => '#9eb5b3',
                            ],
                            [
                                'title' => 'Leather & Strap',
                                'button_text' => 'Shop Now',
                                'link_url' => '/shop?category=strap',
                                'image' => 'images/footer/cat_limited.jpg',
                                'accent_color' => '#d5a48e',
                            ],
                            [
                                'title' => 'Steel & Titanium',
                                'button_text' => 'Shop Now',
                                'link_url' => '/shop?category=metal-chain',
                                'image' => 'images/footer/cat_mens.jpg',
                                'accent_color' => '#5cb9a5',
                            ],
                            [
                                'title' => 'Precision Quartz',
                                'button_text' => 'Shop Now',
                                'link_url' => '/shop?category=quartz',
                                'image' => 'images/footer/cat_womens.jpg',
                                'accent_color' => '#e4ab91',
                            ],
                        ],
                    ],
                    'image_path' => null,
                    'sort_order' => 6,
                    'is_active' => true,
                ],
                [
                    'page' => 'home',
                    'section_key' => 'main_footer',
                    'title' => 'TIMEYRAH',
                    'subtitle' => 'SWISS',
                    'content' => [
                        'tagline' => "PRECISION CHRONOGRAPHS.\nBORN OF OBSIDIAN TEAL AND BRONZE MASTERY.",
                        'copyright' => '© 2026 TimeYrah. All rights reserved.',
                        'contact_email' => 'concierge@timeyrah.com',
                        'social_instagram' => 'https://instagram.com',
                        'social_facebook' => 'https://facebook.com',
                        'social_twitter' => 'https://x.com',
                        'social_youtube' => 'https://youtube.com',
                        'social_linkedin' => 'https://linkedin.com',
                        'show_collections' => true,
                        'show_company' => true,
                        'show_support' => true,
                        'show_connect' => true,
                    ],
                    'image_path' => null,
                    'sort_order' => 7,
                    'is_active' => true,
                ],
            ];

            foreach ($sections as $sData) {
                PageSection::updateOrCreate(
                    [
                        'page' => $sData['page'],
                        'section_key' => $sData['section_key'],
                    ],
                    $sData
                );
            }
        });
    }
}
