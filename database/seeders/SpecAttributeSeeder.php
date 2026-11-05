<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSpecification;
use App\Models\SpecAttribute;
use App\Models\SpecAttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $attributes = [
                [
                    'name' => 'Movement',
                    'slug' => 'movement',
                    'sort_order' => 1,
                    'values' => [
                        'Swiss Automatic',
                        'Japanese Automatic',
                        'Quartz Chronograph',
                        'Solar Powered',
                        'Hand-Winding Mechanical',
                        'Kinetic Quartz',
                        'Digital Multifunction',
                    ],
                ],
                [
                    'name' => 'Case Material',
                    'slug' => 'case-material',
                    'sort_order' => 2,
                    'values' => [
                        '316L Stainless Steel',
                        'High-Tech Ceramic',
                        'Titanium Grade 5',
                        'Bronze Alloy',
                        'Gold PVD Steel',
                        'Carbon Composite',
                        'Matte Shock-Resistant Resin',
                    ],
                ],
                [
                    'name' => 'Case Size',
                    'slug' => 'case-size',
                    'sort_order' => 3,
                    'values' => [
                        '36.0 mm',
                        '38.0 mm',
                        '39.0 mm',
                        '40.0 mm',
                        '41.0 mm',
                        '42.0 mm',
                        '43.0 mm',
                        '44.0 mm',
                        '45.0 mm',
                        '48.5 mm',
                    ],
                ],
                [
                    'name' => 'Crystal Glass',
                    'slug' => 'crystal-glass',
                    'sort_order' => 4,
                    'values' => [
                        'Scratch-Resistant Sapphire Crystal',
                        'Anti-Reflective Sapphire Crystal',
                        'Curved Hardlex Crystal',
                        'Mineral Crystal',
                        'Domed Acrylic / Hesalite',
                    ],
                ],
                [
                    'name' => 'Water Resistance',
                    'slug' => 'water-resistance',
                    'sort_order' => 5,
                    'values' => [
                        '30m / 3 BAR (Splash-Proof)',
                        '50m / 5 BAR (Swimming)',
                        '100m / 10 BAR (Snorkeling)',
                        '200m / 20 BAR (Scuba Diver)',
                        '300m / 30 BAR (Deep Diver)',
                        '600m / 60 BAR (Saturation Diver)',
                    ],
                ],
                [
                    'name' => 'Strap Type',
                    'slug' => 'strap-type',
                    'sort_order' => 6,
                    'values' => [
                        'Solid Stainless Steel Link Bracelet',
                        'Handcrafted Calfskin Leather Strap',
                        'High-Grade FKM Rubber Strap',
                        'NATO Tactical Fabric Strap',
                        'Titanium Link Bracelet',
                        'Ceramic Integrated Bracelet',
                    ],
                ],
                [
                    'name' => 'Warranty',
                    'slug' => 'warranty',
                    'sort_order' => 7,
                    'values' => [
                        '2 Years Official Brand Warranty',
                        '3 Years Manufacturer Warranty',
                        '5 Years International Warranty',
                        '1 Year Atelier Luxury Warranty',
                    ],
                ],
                [
                    'name' => 'Power Reserve',
                    'slug' => 'power-reserve',
                    'sort_order' => 8,
                    'values' => [
                        '38 Hours',
                        '42 Hours',
                        '70 Hours',
                        '80 Hours (Powermatic 80)',
                        '120 Hours (5 Days)',
                    ],
                ],
                [
                    'name' => 'Bezel Material',
                    'slug' => 'bezel-material',
                    'sort_order' => 9,
                    'values' => [
                        'Ceramic Unidirectional Rotating',
                        'Stainless Steel Tachymeter',
                        'Fixed Fluted Bezel',
                        'Bi-directional Aviation Slide Rule',
                    ],
                ],
                [
                    'name' => 'Lug Width',
                    'slug' => 'lug-width',
                    'sort_order' => 10,
                    'values' => [
                        '18 mm',
                        '19 mm',
                        '20 mm',
                        '21 mm',
                        '22 mm',
                    ],
                ],
            ];

            foreach ($attributes as $attrData) {
                $attribute = SpecAttribute::updateOrCreate(
                    ['slug' => $attrData['slug']],
                    [
                        'name' => $attrData['name'],
                        'sort_order' => $attrData['sort_order'],
                        'is_active' => true,
                    ]
                );

                foreach ($attrData['values'] as $vIndex => $val) {
                    SpecAttributeValue::updateOrCreate(
                        [
                            'spec_attribute_id' => $attribute->id,
                            'value' => $val,
                        ],
                        [
                            'sort_order' => $vIndex + 1,
                        ]
                    );
                }
            }

            // Migrate existing product column specifications into product_specifications
            $columnMapping = [
                'movement' => 'movement',
                'case_size' => 'case-size',
                'case_material' => 'case-material',
                'strap_type' => 'strap-type',
                'glass' => 'crystal-glass',
                'water_resistance' => 'water-resistance',
                'warranty' => 'warranty',
            ];

            $attributesBySlug = SpecAttribute::all()->keyBy('slug');

            Product::cursor()->each(function (Product $product) use ($columnMapping, $attributesBySlug): void {
                foreach ($columnMapping as $col => $slug) {
                    $val = $product->{$col};
                    if (! empty($val) && isset($attributesBySlug[$slug])) {
                        ProductSpecification::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'spec_attribute_id' => $attributesBySlug[$slug]->id,
                            ],
                            [
                                'value' => (string) $val,
                            ]
                        );
                    }
                }
            });
        });
    }
}
