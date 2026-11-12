<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\PageSection;
use App\Models\User;
use Database\Seeders\PageSectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PageCustomizerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PageSectionSeeder::class);

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->customer = User::factory()->create([
            'is_admin' => false,
        ]);
    }

    public function test_guest_is_redirected_from_customizer(): void
    {
        $response = $this->get(route('admin.customizer.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_customizer(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.customizer.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_view_page_customizer(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.customizer.index'));

        $response->assertOk();
        $response->assertSee('Page Content Customizer');
        $response->assertSee('Hero Header Banner');
        $response->assertSee('Search Engine Optimization');
        $response->assertSee('Section Below Section');
    }

    public function test_admin_can_update_seo_and_hero_banner_content(): void
    {
        $category = Category::factory()->create(['name' => 'Chronograph Watches']);

        $payload = [
            'page' => 'home',
            'seo' => [
                'title' => 'TIMEYRAH Custom Horology — Official Luxury Store Pakistan',
                'subtitle' => 'Exclusive certified Swiss & Japanese watches in Karachi & Lahore.',
                'meta_description' => 'Browse custom luxury watches with Cash on Delivery and official manufacturer warranty.',
                'keywords' => 'pakistan luxury watches, chronograph karachi',
            ],
            'hero' => [
                'badge' => 'EXCLUSIVE PAKISTAN ATELIER',
                'title' => 'TIMEYRAH LUXURY',
                'subtitle' => 'A New Era of Precision Horology',
                'heading_accent_1' => 'SWISS CALIBER',
                'heading_accent_2' => 'LEGACY',
                'description' => 'Hand-inspected automatic and chronograph timepieces delivered across Pakistan.',
                'button_text' => 'Shop Luxury Now',
                'button_url' => '/shop',
            ],
            'shop_by_category' => [
                'badge' => 'PREMIER CATEGORIES',
                'title' => 'CURATED TIMEPIECE SELECTION',
                'subtitle' => 'Explore by movements, complications, and water resistance.',
                'category_ids' => [$category->id],
            ],
            'promo' => [
                'badge' => 'SIGNATURE CRAFTSMANSHIP',
                'accent_badge' => 'LIFETIME WARRANTY AUTHENTICITY',
                'title' => 'ENGINEERED FOR PERFECTION',
                'subtitle' => 'Every single timepiece is calibrated before shipping.',
                'button_text' => 'Explore Atelier',
                'button_url' => '/shop',
            ],
            'section_order' => [
                'hero_banner',
                'promo_banner',
                'featured_watches',
                'shop_by_category',
            ],
            'section_visibility' => [
                'hero_banner' => '1',
                'promo_banner' => '1',
                'featured_watches' => '1',
                'shop_by_category' => '1',
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), $payload);

        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));
        $response->assertSessionHas('success');

        // Verify database persistence
        $this->assertDatabaseHas('page_sections', [
            'page' => 'home',
            'section_key' => 'seo',
            'title' => 'TIMEYRAH Custom Horology — Official Luxury Store Pakistan',
        ]);

        $this->assertDatabaseHas('page_sections', [
            'page' => 'home',
            'section_key' => 'hero_banner',
            'title' => 'TIMEYRAH LUXURY',
        ]);

        // Verify homepage reflects the updated content
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('TIMEYRAH Custom Horology — Official Luxury Store Pakistan');
        $homeResponse->assertSee('EXCLUSIVE PAKISTAN ATELIER');
        $homeResponse->assertSee('SWISS CALIBER');
        $homeResponse->assertSee('LEGACY');
    }

    public function test_admin_can_update_hero_center_image_position_for_all_screen_sizes(): void
    {
        $payload = [
            'page' => 'home',
            'hero' => [
                'title' => 'TIMEYRAH CENTER WATCH TEST',
                'position' => [
                    'desktop_left' => '58%',
                    'desktop_top' => '105%',
                    'desktop_width' => '420px',
                    'tablet_top' => '-70px',
                    'tablet_width' => '330px',
                    'mobile_top' => '-40px',
                    'mobile_width' => '280px',
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), $payload);
        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));

        // Verify homepage renders custom responsive styles
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('left: 58% !important;', false);
        $homeResponse->assertSee('top: 105% !important;', false);
        $homeResponse->assertSee('width: 420px !important;', false);
        $homeResponse->assertSee('margin-top: -70px;', false);
        $homeResponse->assertSee('width: 330px;', false);
        $homeResponse->assertSee('margin-top: -40px;', false);
        $homeResponse->assertSee('width: 280px;', false);
    }

    public function test_hero_center_image_position_uses_defaults_when_empty(): void
    {
        // When position fields are empty or not provided
        $payload = [
            'page' => 'home',
            'hero' => [
                'title' => 'TIMEYRAH DEFAULT POSITION',
                'position' => [
                    'desktop_left' => '',
                    'desktop_top' => '',
                    'desktop_width' => '',
                    'tablet_top' => '',
                    'tablet_width' => '',
                    'mobile_top' => '',
                    'mobile_width' => '',
                ],
            ],
        ];

        $this->actingAs($this->admin)->post(route('admin.customizer.update'), $payload);

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        // Desktop defaults: centered in hero banner so watch never overflows cards below
        $homeResponse->assertSee('left: 55% !important;', false);
        $homeResponse->assertSee('top: 50% !important;', false);
        $homeResponse->assertSee('width: 350px !important;', false);
        // Tablet defaults
        $homeResponse->assertSee('margin-top: -80px;', false);
        $homeResponse->assertSee('width: 310px;', false);
        // Mobile defaults
        $homeResponse->assertSee('margin-top: -48px;', false);
        $homeResponse->assertSee('width: 260px;', false);
    }

    public function test_admin_can_upload_new_center_hero_watch_image(): void
    {
        $file = UploadedFile::fake()->image('custom_watch.png', 500, 700);

        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), [
            'page' => 'home',
            'hero' => [
                'title' => 'TIMEYRAH WITH NEW WATCH',
            ],
            'hero_image' => $file,
        ]);

        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));
        $response->assertSessionHas('success');

        $hero = PageSection::where('page', 'home')->where('section_key', 'hero_banner')->first();
        $this->assertNotNull($hero);
        $this->assertStringContainsString('uploads/pages/hero_home_', $hero->image_path);
        $this->assertFileExists(public_path($hero->image_path));

        // Clean up test file
        if (file_exists(public_path($hero->image_path))) {
            unlink(public_path($hero->image_path));
        }
    }

    public function test_admin_can_reset_hero_watch_image_to_default(): void
    {
        // First set a custom image
        $hero = PageSection::where('page', 'home')->where('section_key', 'hero_banner')->first();
        $hero->image_path = 'uploads/pages/custom_sample.png';
        $hero->save();

        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), [
            'page' => 'home',
            'hero_reset_image' => '1',
        ]);

        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));
        $hero->refresh();
        $this->assertEquals('hero-watch.png', $hero->image_path);
    }

    public function test_admin_can_select_catalog_watch_image_for_hero(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), [
            'page' => 'home',
            'hero_catalog_image' => 'images/make-banner.jpg',
        ]);

        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));
        $hero = PageSection::where('page', 'home')->where('section_key', 'hero_banner')->first();
        $this->assertEquals('images/make-banner.jpg', $hero->image_path);
    }

    public function test_admin_can_customize_dynamic_category_cards(): void
    {
        $category = Category::factory()->create([
            'name' => 'TOURBILLON',
            'slug' => 'tourbillon',
            'is_active' => true,
        ]);

        $payload = [
            'page' => 'home',
            'shop_by_category' => [
                'title' => 'EXCLUSIVE COMPLICATIONS',
                'badge' => 'HAUTE HORLOGERIE',
                'category_ids' => [$category->id],
                'category_meta' => [
                    $category->id => [
                        'subtitle' => 'Handcrafted Flying Tourbillon Cages',
                        'image_source' => 'curated',
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), $payload);
        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('EXCLUSIVE COMPLICATIONS');
        $homeResponse->assertSee('TOURBILLON');
        $homeResponse->assertSee('Handcrafted Flying Tourbillon Cages');
        $homeResponse->assertSee('category-showcase-card');
        $homeResponse->assertSee('category-card-title');
    }

    public function test_admin_can_customize_footer_section_and_main_footer_content(): void
    {
        $payload = [
            'page' => 'home',
            'footer_section' => [
                'badge' => 'CUSTOM HOROLOGY STYLE',
                'title' => 'Curated Caliber Showcase',
                'subtitle' => 'Explore precision engineering across all styles.',
                'show_discount_badge' => 1,
                'discount_text' => 'Save an extra',
                'discount_percent' => '30% OFF',
                'discount_url' => '/shop?discount=vip',
            ],
            'main_footer' => [
                'title' => 'TIMEYRAH ATELIER',
                'subtitle' => 'GENEVE',
                'tagline' => "CRAFTED FOR HOROLOGY CONNOISSEURS.\nSWISS HERITAGE IN PAKISTAN.",
                'copyright' => '© 2026 TimeYrah Atelier. VIP Horology.',
                'contact_email' => 'atelier@timeyrah.vip',
                'social_instagram' => 'https://instagram.com/timeyrah_official',
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), $payload);
        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));

        $footerSec = PageSection::where('page', 'home')->where('section_key', 'footer_section')->first();
        $this->assertEquals('Curated Caliber Showcase', $footerSec->title);
        $this->assertEquals('CUSTOM HOROLOGY STYLE', $footerSec->content['badge']);
        $this->assertEquals('30% OFF', $footerSec->content['discount_percent']);

        $mainFooterSec = PageSection::where('page', 'home')->where('section_key', 'main_footer')->first();
        $this->assertEquals('TIMEYRAH ATELIER', $mainFooterSec->title);
        $this->assertEquals('GENEVE', $mainFooterSec->subtitle);
        $this->assertEquals('atelier@timeyrah.vip', $mainFooterSec->content['contact_email']);

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk()
            ->assertSee('CUSTOM HOROLOGY STYLE')
            ->assertSee('Curated Caliber Showcase')
            ->assertSee('30% OFF')
            ->assertSee('TIMEYRAH ATELIER')
            ->assertSee('GENEVE')
            ->assertSee('atelier@timeyrah.vip')
            ->assertSee('© 2026 TimeYrah Atelier. VIP Horology.');
    }

    public function test_admin_can_toggle_footer_sections_visibility(): void
    {
        $payload = [
            'page' => 'home',
            'section_order' => [
                'hero_banner',
                'featured_watches',
                'shop_by_category',
                'promo_banner',
                'brand_showcase',
                'footer_section',
                'main_footer',
            ],
            'section_visibility' => [
                'hero_banner' => 1,
                'featured_watches' => 1,
                'shop_by_category' => 1,
                'promo_banner' => 1,
                'brand_showcase' => 1,
                // footer_section is unchecked / omitted
                'main_footer' => 1,
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), $payload);
        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));

        $footerSec = PageSection::where('page', 'home')->where('section_key', 'footer_section')->first();
        $this->assertFalse((bool) $footerSec->is_active);

        $mainFooterSec = PageSection::where('page', 'home')->where('section_key', 'main_footer')->first();
        $this->assertTrue((bool) $mainFooterSec->is_active);

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk()
            ->assertDontSee('Find Your Style')
            ->assertDontSee('Save up to')
            ->assertSee('TIMEYRAH');
    }

    public function test_admin_can_customize_and_toggle_ribbon_deal_banner(): void
    {
        // 1. Set custom deal text and percentage
        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), [
            'page' => 'home',
            'footer_section' => [
                'show_discount_badge' => 1,
                'discount_text' => 'Special Ramadan Eid Offer',
                'discount_percent' => 'FLAT 40% OFF',
                'discount_url' => '/shop?deal=eid',
            ],
        ]);
        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));

        $footerSec = PageSection::where('page', 'home')->where('section_key', 'footer_section')->first();
        $this->assertTrue((bool) ($footerSec->content['show_discount_badge'] ?? false));
        $this->assertEquals('Special Ramadan Eid Offer', $footerSec->content['discount_text']);
        $this->assertEquals('FLAT 40% OFF', $footerSec->content['discount_percent']);
        $this->assertEquals('/shop?deal=eid', $footerSec->content['discount_url']);

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk()
            ->assertSee('Special Ramadan Eid Offer')
            ->assertSee('FLAT 40% OFF')
            ->assertSee('/shop?deal=eid');

        // 2. Uncheck the ribbon badge (form sends 0 via hidden input)
        $uncheckResponse = $this->actingAs($this->admin)->post(route('admin.customizer.update'), [
            'page' => 'home',
            'footer_section' => [
                'show_discount_badge' => 0,
                'discount_text' => 'Special Ramadan Eid Offer',
            ],
        ]);
        $uncheckResponse->assertRedirect(route('admin.customizer.index', ['page' => 'home']));

        $footerSec->refresh();
        $this->assertFalse((bool) ($footerSec->content['show_discount_badge'] ?? true));

        $homeResponseAfterUncheck = $this->get(route('home'));
        $homeResponseAfterUncheck->assertOk()
            ->assertDontSee('FLAT 40% OFF');
    }

    public function test_admin_can_customize_footer_category_cards_and_background(): void
    {
        $payload = [
            'page' => 'home',
            'footer_section' => [
                'badge' => 'CURATED HOROLOGY CALIBERS',
                'title' => 'Explore Masterpieces',
                'cards' => [
                    [
                        'title' => 'Tourbillon Masterpieces',
                        'button_text' => 'Discover Tourbillon',
                        'link_url' => '/shop?caliber=tourbillon',
                        'image' => 'images/footer/cat_sports.jpg',
                        'accent_color' => '#d5a48e',
                    ],
                    [
                        'title' => 'Vintage Italian Leather',
                        'button_text' => 'View Straps',
                        'link_url' => '/shop?strap=leather',
                        'image' => 'images/footer/cat_limited.jpg',
                        'accent_color' => '#9eb5b3',
                    ],
                    [
                        'title' => 'Aerospace Grade Titanium',
                        'button_text' => 'Inspect Metal',
                        'link_url' => '/shop?material=titanium',
                        'image' => 'images/footer/cat_mens.jpg',
                        'accent_color' => '#5cb9a5',
                    ],
                    [
                        'title' => 'Deep Space Quartz Dials',
                        'button_text' => 'See Starfields',
                        'link_url' => '/shop?dial=space',
                        'image' => 'images/footer/cat_womens.jpg',
                        'accent_color' => '#e4ab91',
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.customizer.update'), $payload);
        $response->assertRedirect(route('admin.customizer.index', ['page' => 'home']));

        $footerSec = PageSection::where('page', 'home')->where('section_key', 'footer_section')->first();
        $this->assertEquals('Explore Masterpieces', $footerSec->title);
        $this->assertEquals('Tourbillon Masterpieces', $footerSec->content['cards'][0]['title']);
        $this->assertEquals('Vintage Italian Leather', $footerSec->content['cards'][1]['title']);

        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk()
            ->assertSee('CURATED HOROLOGY CALIBERS')
            ->assertSee('Explore Masterpieces')
            ->assertSee('Tourbillon Masterpieces')
            ->assertSee('Discover Tourbillon')
            ->assertSee('/shop?caliber=tourbillon')
            ->assertSee('Vintage Italian Leather')
            ->assertSee('Aerospace Grade Titanium')
            ->assertSee('Deep Space Quartz Dials');
    }
}
