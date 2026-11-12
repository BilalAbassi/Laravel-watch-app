<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Brands shown in the shop menu, in menu order.
     *
     * @var list<string>
     */
    private const BRANDS = [
        'Rado', 'Citizen', 'Seiko', 'Casio General', 'Casio Edifice',
        'Casio G Shock', 'Omax General', 'Omax Masterpiece', 'Omax Premium',
        'Mathey Tissot', 'Ferro', 'Crysma', 'Kenneth Cole', 'Fossil',
        'Daniel Klein', 'Swiss Military Hanowa', 'Santa Barbara', 'Rolex', 'Longines', 'IWC',
    ];

    /**
     * Shop filter categories.
     *
     * @var list<string>
     */
    private const CATEGORIES = [
        'STRAP', 'METAL CHAIN', 'ANALOG', 'QUARTZ', 'DIGITAL', 'AUTOMATIC',
        "MEN'S", "WOMEN'S", 'UNISEX',
    ];

    /**
     * Run the database seeds. Safe to run many times (it updates existing rows).
     */
    public function run(): void
    {
        $brandIds = [];
        foreach (self::BRANDS as $name) {
            $brandIds[$name] = Brand::updateOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'is_active' => true]
            )->id;
        }

        $categoryIds = [];
        foreach (self::CATEGORIES as $name) {
            $categoryIds[$name] = Category::updateOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name), 'is_active' => true]
            )->id;
        }

        foreach ($this->watches() as $watch) {
            $watchCategoryNames = $watch['categories'];
            if (! empty($watch['gender'])) {
                $genderCat = match ($watch['gender']) {
                    "Men's" => "MEN'S",
                    "Women's" => "WOMEN'S",
                    'Unisex' => 'UNISEX',
                    default => null,
                };
                if ($genderCat !== null && ! in_array($genderCat, $watchCategoryNames, true)) {
                    $watchCategoryNames[] = $genderCat;
                }
            }

            $watchCategoryIds = array_map(fn (string $name): int => $categoryIds[$name], $watchCategoryNames);

            // Keep the same ids as the old catalog so links like /product/2 and saved carts still work.
            $product = Product::find($watch['id']) ?? (new Product)->forceFill(['id' => $watch['id']]);
            $product->fill([
                ...Arr::except($watch, ['id', 'brand', 'categories', 'images']),
                'slug' => Str::slug($watch['title']),
                'sku' => 'TY-'.strtoupper(substr($watch['brand'], 0, 3)).'-'.$watch['id'],
                'brand_id' => $brandIds[$watch['brand']],
                'category_id' => $watchCategoryIds[0],
                'is_active' => true,
            ])->save();

            $product->categories()->sync($watchCategoryIds);

            $product->images()->delete();
            foreach ($watch['images'] as $position => $path) {
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $position === 0,
                    'sort_order' => $position,
                ]);
            }
        }
    }

    /**
     * The curated watch catalog (prices are in USD).
     *
     * @return list<array<string, mixed>>
     */
    private function watches(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Rado Captain Cook High-Tech Ceramic Diver',
                'brand' => 'Rado',
                'categories' => [
                    'AUTOMATIC',
                    'METAL CHAIN',
                    'ANALOG',
                ],
                'price' => 4200.00,
                'sale_price' => 3850.00,
                'stock' => 14,
                'movement' => 'Rado Calibre R763 (80-hour reserve)',
                'case_size' => '43.0 mm',
                'case_material' => 'Monobloc High-Tech Ceramic',
                'strap_type' => 'Matte Plasma Ceramic Bracelet',
                'glass' => 'Domed Sapphire Crystal with double Anti-Reflective coating',
                'water_resistance' => '300m / 30 BAR Diver',
                'warranty' => '5 Years International Warranty',
                'gender' => 'Men\'s',
                'badge' => 'High-Tech Ceramic',
                'rating' => 4.95,
                'reviews_count' => 64,
                'description' => 'The Captain Cook High-Tech Ceramic is the culmination of years of research and development. Featuring Rados innovative ceramic monobloc case and an automatic caliber with 80 hours power reserve.',
                'is_featured' => true,
                'images' => [
                    'watches/titlon-dive.png',
                    'watches/astra-black.png',
                    'watches/nicva-shin.png',
                    'watches/saisae-moonphase.png',
                ],
            ],
            [
                'id' => 2,
                'title' => 'Casio G-Shock GA-2100-1A1 Carbon Core "CasiOak"',
                'brand' => 'Casio G Shock',
                'categories' => [
                    'DIGITAL',
                    'ANALOG',
                    'QUARTZ',
                    'STRAP',
                ],
                'price' => 135.00,
                'sale_price' => 110.00,
                'stock' => 45,
                'movement' => 'Casio Module 5611 Multifunction Quartz',
                'case_size' => '45.4 mm (thin 11.8mm depth)',
                'case_material' => 'Carbon-Reinforced Fine Resin',
                'strap_type' => 'Resin Band with Matte Black Finish',
                'glass' => 'Mineral Glass',
                'water_resistance' => '200m / 20 BAR Shock Resistant',
                'warranty' => '2 Years Official Casio Warranty',
                'gender' => 'Unisex',
                'badge' => 'Best Seller',
                'rating' => 4.90,
                'reviews_count' => 284,
                'description' => 'The iconic all-black stealth CasiOak with Carbon Core Guard structure. Slim 11.8mm profile, double LED lighting, 200m water resistance, and unmatched shock-absorbing toughness.',
                'is_featured' => true,
                'images' => [
                    'images/footer/cat_sports.jpg',
                    'watches/astra-black.png',
                    'watches/titlon-dive.png',
                    'watches/saisae-moonphase.png',
                ],
            ],
            [
                'id' => 3,
                'title' => 'Seiko Prospex Speedtimer Solar Chronograph SSC813',
                'brand' => 'Seiko',
                'categories' => [
                    'METAL CHAIN',
                    'ANALOG',
                    'QUARTZ',
                ],
                'price' => 750.00,
                'sale_price' => 675.00,
                'stock' => 20,
                'movement' => 'Seiko Solar Caliber V192 Chronograph',
                'case_size' => '39.0 mm',
                'case_material' => '316L Stainless Steel',
                'strap_type' => 'Three-Link Steel Bracelet',
                'glass' => 'Curved Sapphire Crystal with Anti-Reflective coating',
                'water_resistance' => '100m / 10 BAR',
                'warranty' => '3 Years International Seiko Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Solar Powered',
                'rating' => 4.92,
                'reviews_count' => 119,
                'description' => 'The celebrated "Seitona" Panda dial chronograph. Solar-powered caliber V192 charges from any light source with a 6-month reserve when fully charged.',
                'is_featured' => true,
                'images' => [
                    'watches/nicva-shin.png',
                    'watches/astra-black.png',
                    'watches/titlon-dive.png',
                    'watches/saisae-moonphase.png',
                ],
            ],
            [
                'id' => 4,
                'title' => 'Citizen Tsuyosa Automatic Sunray Yellow Dial',
                'brand' => 'Citizen',
                'categories' => [
                    'AUTOMATIC',
                    'METAL CHAIN',
                    'ANALOG',
                ],
                'price' => 495.00,
                'sale_price' => 450.00,
                'stock' => 18,
                'movement' => 'Citizen Miyota Caliber 8210 Automatic',
                'case_size' => '40.0 mm',
                'case_material' => 'Stainless Steel with Crown at 4 oclock',
                'strap_type' => 'Integrated Stainless Steel Rounded Links',
                'glass' => 'Sapphire Crystal with Date Magnifier',
                'water_resistance' => '50m / 5 BAR',
                'warranty' => '5 Years Official Citizen Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Integrated Bracelet',
                'rating' => 4.88,
                'reviews_count' => 87,
                'description' => 'The Citizen Tsuyosa delivers ultra-contemporary sports-chic styling with a vibrant sunray dial, exhibition case back, and integrated stainless steel president-style bracelet.',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 5,
                'title' => 'Casio Edifice Solar Bluetooth Chronograph ECB-900',
                'brand' => 'Casio Edifice',
                'categories' => [
                    'METAL CHAIN',
                    'ANALOG',
                    'DIGITAL',
                    'QUARTZ',
                ],
                'price' => 260.00,
                'sale_price' => 220.00,
                'stock' => 22,
                'movement' => 'Tough Solar Quartz with Bluetooth Link',
                'case_size' => '48.0 mm',
                'case_material' => 'Stainless Steel with Tachymeter Bezel',
                'strap_type' => 'Solid Stainless Steel Band',
                'glass' => 'Mineral Crystal Glass',
                'water_resistance' => '100m / 10 BAR',
                'warranty' => '2 Years Official Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Bluetooth Sync',
                'rating' => 4.82,
                'reviews_count' => 53,
                'description' => 'Motorsport-inspired speed and intelligence. Connects seamlessly with your smartphone for automatic time zone correction and lap time measurement down to 1/1000th second.',
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 6,
                'title' => 'Casio Vintage Digital Illuminator A168WA',
                'brand' => 'Casio General',
                'categories' => [
                    'DIGITAL',
                    'METAL CHAIN',
                    'QUARTZ',
                ],
                'price' => 45.00,
                'sale_price' => 38.00,
                'stock' => 120,
                'movement' => 'Casio Module 3298 Digital Quartz (7-year battery)',
                'case_size' => '36.3 mm',
                'case_material' => 'Chrome Plated Resin Case',
                'strap_type' => 'Adjustable Stainless Steel Mesh Clasp',
                'glass' => 'Resin Glass',
                'water_resistance' => 'Daily Splash Resistant',
                'warranty' => '2 Years Official Warranty',
                'gender' => 'Unisex',
                'badge' => 'Classic Icon',
                'rating' => 4.94,
                'reviews_count' => 512,
                'description' => 'The timeless 80s retro digital masterpiece. Features Casio electroluminescent backlight, daily alarm, 1/100s stopwatch, and auto calendar.',
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1526045431048-f857369baa09?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 7,
                'title' => 'Mathey-Tissot Mathy Vintage Automatic GMT H903ATAN',
                'brand' => 'Mathey Tissot',
                'categories' => [
                    'AUTOMATIC',
                    'METAL CHAIN',
                    'ANALOG',
                ],
                'price' => 690.00,
                'sale_price' => 590.00,
                'stock' => 12,
                'movement' => 'Swiss Automatic Caliber with GMT Hand',
                'case_size' => '42.0 mm',
                'case_material' => '316L Stainless Steel',
                'strap_type' => 'Jubilee-style Stainless Steel Bracelet',
                'glass' => 'Sapphire Coated Crystal Glass',
                'water_resistance' => '100m / 10 BAR',
                'warranty' => '3 Years International Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Swiss Made',
                'rating' => 4.85,
                'reviews_count' => 42,
                'description' => 'Swiss horological pedigree since 1886. Robust 42mm diver design with bi-directional 24-hour Pepsi bezel and Swiss automatic caliber with GMT dual time indication.',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 8,
                'title' => 'Fossil Grant Chronograph Luggage Leather FS4813',
                'brand' => 'Fossil',
                'categories' => [
                    'STRAP',
                    'ANALOG',
                    'QUARTZ',
                ],
                'price' => 170.00,
                'sale_price' => 145.00,
                'stock' => 30,
                'movement' => 'Precision Quartz Chronograph (Sub-dials for 24h & stop-second)',
                'case_size' => '44.0 mm',
                'case_material' => 'Polished Stainless Steel',
                'strap_type' => '22mm Genuine Calfskin Leather with Buckle',
                'glass' => 'Mineral Hardened Crystal',
                'water_resistance' => '50m / 5 BAR',
                'warranty' => '2 Years Official Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Italian Leather',
                'rating' => 4.79,
                'reviews_count' => 163,
                'description' => 'Classic roman numeral markers with vintage aeronautical chronometer styling on a genuine rich amber brown calfskin leather strap.',
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 9,
                'title' => 'Omax Masterpiece Dual-Tone Luxury Dress Watch',
                'brand' => 'Omax Masterpiece',
                'categories' => [
                    'METAL CHAIN',
                    'ANALOG',
                    'QUARTZ',
                ],
                'price' => 110.00,
                'sale_price' => 85.00,
                'stock' => 25,
                'movement' => 'Japanese Quartz Movement',
                'case_size' => '41.0 mm',
                'case_material' => 'Two-Tone Stainless Steel with PVD Gold Fluted Bezel',
                'strap_type' => 'Two-Tone Stainless Steel Bracelet',
                'glass' => 'Hardened Crystal Glass with Cyclops Lens',
                'water_resistance' => '50m Water Proof',
                'warranty' => '2 Years Omax Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Water Resistant',
                'rating' => 4.72,
                'reviews_count' => 74,
                'description' => 'Swiss design legacy with Japanese precision movement. Features gold-tone fluted bezel, diamond index markers, and day-date display.',
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 10,
                'title' => 'Omax Premium Open-Heart Automatic Skeleton',
                'brand' => 'Omax Premium',
                'categories' => [
                    'AUTOMATIC',
                    'METAL CHAIN',
                    'ANALOG',
                ],
                'price' => 220.00,
                'sale_price' => 175.00,
                'stock' => 16,
                'movement' => 'Automatic 21-Jewel Mechanical Caliber',
                'case_size' => '42.5 mm',
                'case_material' => 'Gunmetal Ion-Plated Stainless Steel',
                'strap_type' => 'Gunmetal Stainless Steel Deployment',
                'glass' => 'Scratch Resistant Mineral Glass',
                'water_resistance' => '50m / 5 BAR',
                'warranty' => '2 Years Omax Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Open Heart Caliber',
                'rating' => 4.81,
                'reviews_count' => 38,
                'description' => 'Exquisite open-heart escapement window reveals the mechanical heartbeat. Fitted with an all-black gunmetal case and exhibition back.',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 11,
                'title' => 'Omax General Crystal Classic Quartz',
                'brand' => 'Omax General',
                'categories' => [
                    'STRAP',
                    'ANALOG',
                    'QUARTZ',
                ],
                'price' => 60.00,
                'sale_price' => 45.00,
                'stock' => 50,
                'movement' => 'Seiko Epson Quartz Caliber',
                'case_size' => '39.0 mm',
                'case_material' => 'Polished Alloy Steel',
                'strap_type' => 'Genuine Black Leather Band',
                'glass' => 'Mineral Crystal',
                'water_resistance' => '30m Water Resistant',
                'warranty' => '1 Year Official Warranty',
                'gender' => 'Unisex',
                'badge' => 'Daily Essential',
                'rating' => 4.68,
                'reviews_count' => 61,
                'description' => 'Reliable, affordable everyday analog timekeeper with clean minimalist dial and genuine textured leather strap.',
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 12,
                'title' => 'Swiss Military Hanowa Helvetus Automatic 06-5316',
                'brand' => 'Swiss Military Hanowa',
                'categories' => [
                    'AUTOMATIC',
                    'STRAP',
                    'ANALOG',
                ],
                'price' => 590.00,
                'sale_price' => 520.00,
                'stock' => 15,
                'movement' => 'Swiss Made STP1-11 Automatic (28,800 bph)',
                'case_size' => '40.0 mm',
                'case_material' => '316L Brushed Surgical Steel',
                'strap_type' => 'Brown Saddle Stitched Swiss Leather',
                'glass' => 'Anti-Reflective Sapphire Crystal',
                'water_resistance' => '100m / 10 BAR',
                'warranty' => '3 Years Swiss Military Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Swiss Made',
                'rating' => 4.89,
                'reviews_count' => 45,
                'description' => 'Officially licensed by the Swiss Confederation. Rugged elegance built with military-grade precision, sapphire crystal, and Swiss automatic movement.',
                'is_featured' => true,
                'images' => [
                    'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 13,
                'title' => 'Daniel Klein Exclusive Multi-Function Chrono DK1289',
                'brand' => 'Daniel Klein',
                'categories' => [
                    'METAL CHAIN',
                    'ANALOG',
                    'QUARTZ',
                ],
                'price' => 95.00,
                'sale_price' => 78.00,
                'stock' => 35,
                'movement' => 'High-Precision Multi-Function Quartz',
                'case_size' => '43.0 mm',
                'case_material' => 'Stainless Steel with IP Gunmetal Coating',
                'strap_type' => 'Matching Stainless Steel Mesh Bracelet',
                'glass' => 'Curved Hardened Crystal',
                'water_resistance' => '50m Water Resistant',
                'warranty' => '2 Years Official Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Modern Fashion',
                'rating' => 4.75,
                'reviews_count' => 92,
                'description' => 'Sleek metropolitan aesthetic featuring dual-time complication, day-night indicator, and solid stainless steel bracelet.',
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 14,
                'title' => 'Kenneth Cole Skeleton Automatic Dial Leather Watch',
                'brand' => 'Kenneth Cole',
                'categories' => [
                    'AUTOMATIC',
                    'STRAP',
                    'ANALOG',
                ],
                'price' => 240.00,
                'sale_price' => 195.00,
                'stock' => 20,
                'movement' => 'Self-Winding 21-Jewel Automatic Mechanical',
                'case_size' => '44.0 mm',
                'case_material' => 'Polished Stainless Steel with Rose Gold Accents',
                'strap_type' => 'Black Alligator-Embossed Leather Strap',
                'glass' => 'Hardened Mineral Crystal',
                'water_resistance' => '30m Water Resistant',
                'warranty' => '2 Years Official Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Full Skeleton',
                'rating' => 4.84,
                'reviews_count' => 67,
                'description' => 'Signature transparent skeleton dial showcasing the balance wheel and intricate mechanical geartrain. Premium alligator-embossed genuine leather strap.',
                'is_featured' => true,
                'images' => [
                    'watches/saisae-moonphase.png',
                    'watches/astra-black.png',
                    'watches/titlon-dive.png',
                    'watches/nicva-shin.png',
                ],
            ],
            [
                'id' => 15,
                'title' => 'Santa Barbara Polo & Racquet Club Royal Steel SB-1022',
                'brand' => 'Santa Barbara',
                'categories' => [
                    'METAL CHAIN',
                    'ANALOG',
                    'QUARTZ',
                ],
                'price' => 165.00,
                'sale_price' => 135.00,
                'stock' => 19,
                'movement' => 'Japanese Quartz Multi-Eye Movement',
                'case_size' => '42.0 mm',
                'case_material' => 'Stainless Steel with Polished & Brushed Finish',
                'strap_type' => 'Solid Steel Link Bracelet with Safety Clasp',
                'glass' => 'Scratch Resistant Mineral Glass',
                'water_resistance' => '50m / 5 BAR',
                'warranty' => '2 Years Official Warranty',
                'gender' => 'Men\'s',
                'badge' => 'Equestrian Heritage',
                'rating' => 4.77,
                'reviews_count' => 48,
                'description' => 'Inspired by the historic California polo club founded in 1911. Features a rich emerald green sunray dial, gold hands, and solid stainless steel link bracelet.',
                'is_featured' => false,
                'images' => [
                    'watches/nicva-shin.png',
                    'watches/astra-black.png',
                    'watches/titlon-dive.png',
                    'watches/saisae-moonphase.png',
                ],
            ],
            [
                'id' => 16,
                'title' => 'Ferro Distinct Single-Hand Retrograde Dial',
                'brand' => 'Ferro',
                'categories' => [
                    'STRAP',
                    'ANALOG',
                    'QUARTZ',
                ],
                'price' => 190.00,
                'sale_price' => 160.00,
                'stock' => 14,
                'movement' => 'Swiss Ronda 515 Quartz Movement',
                'case_size' => '41.0 mm',
                'case_material' => 'Satin-Brushed Titanium-Grey PVD Steel',
                'strap_type' => 'Vintage Italian Waxed Saddle Leather',
                'glass' => 'Sapphire Coated Crystal',
                'water_resistance' => '50m / 5 BAR',
                'warranty' => '2 Years International Warranty',
                'gender' => 'Unisex',
                'badge' => 'Single Hand Horology',
                'rating' => 4.83,
                'reviews_count' => 39,
                'description' => 'Minimalist single-hand gauge design reminiscent of classic motorsport tachometers. Italian oil-waxed leather strap that patinas beautifully over time.',
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                ],
            ],
            [
                'id' => 17,
                'title' => 'Crysma Elegance Sunray Rose Gold Mesh Watch',
                'brand' => 'Crysma',
                'categories' => [
                    'METAL CHAIN',
                    'ANALOG',
                    'QUARTZ',
                ],
                'price' => 120.00,
                'sale_price' => 95.00,
                'stock' => 28,
                'movement' => 'Slim Japanese Quartz Caliber',
                'case_size' => '36.0 mm (Ultra-slim 6.8mm)',
                'case_material' => 'Rose Gold PVD Stainless Steel',
                'strap_type' => 'Rose Gold Milanese Magnetic Mesh Band',
                'glass' => 'Hardened Mineral Crystal',
                'water_resistance' => '30m Water Resistant',
                'warranty' => '2 Years Official Warranty',
                'gender' => 'Women\'s',
                'badge' => 'Rose Gold PVD',
                'rating' => 4.76,
                'reviews_count' => 58,
                'description' => 'Ultra-thin modern dress watch with warm rose gold case, minimalist baton hands, and a supple Milanese mesh bracelet.',
                'is_featured' => false,
                'images' => [
                    'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=600&q=80',
                ],
            ],
        ];
    }
}
