<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Display the shop listing page with dynamic filters and external dummy API watch integration.
     */
    public function index(Request $request): View|JsonResponse
    {
        $selectedBrand = $request->query('brand');
        $selectedCategory = $request->query('category');
        $search = $request->query('search');
        $sort = $request->query('sort', 'featured');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $movement = $request->query('movement');

        // Fetch dummy API watches and merge with brand catalog
        $allWatches = $this->getMergedWatchCatalog();

        // Filter by Brand
        if ($selectedBrand) {
            $allWatches = array_filter($allWatches, function ($watch) use ($selectedBrand) {
                return strcasecmp($watch['brand'], $selectedBrand) === 0 ||
                    stripos($watch['brand'], $selectedBrand) !== false ||
                    stripos($watch['title'], $selectedBrand) !== false;
            });
        }

        // Filter by Category (STRAP, METAL CHAIN, ANALOG, QUARTZ, DIGITAL, AUTOMATIC)
        if ($selectedCategory) {
            $categoryKey = strtoupper(trim($selectedCategory));
            $allWatches = array_filter($allWatches, function ($watch) use ($categoryKey) {
                $cats = is_array($watch['categories']) ? $watch['categories'] : [$watch['categories']];
                foreach ($cats as $cat) {
                    if (strcasecmp($cat, $categoryKey) === 0 || stripos($cat, $categoryKey) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }

        // Filter by Search Query
        if ($search) {
            $search = strtolower(trim($search));
            $allWatches = array_filter($allWatches, function ($watch) use ($search) {
                return stripos(strtolower($watch['title']), $search) !== false ||
                    stripos(strtolower($watch['brand']), $search) !== false ||
                    stripos(strtolower($watch['description']), $search) !== false;
            });
        }

        // Filter by Price
        if ($minPrice !== null && is_numeric($minPrice)) {
            $allWatches = array_filter($allWatches, fn ($w) => $w['price'] >= (float) $minPrice);
        }
        if ($maxPrice !== null && is_numeric($maxPrice)) {
            $allWatches = array_filter($allWatches, fn ($w) => $w['price'] <= (float) $maxPrice);
        }

        // Filter by Movement
        if ($movement) {
            $allWatches = array_filter($allWatches, function ($w) use ($movement) {
                return stripos(strtolower($w['specs']['movement'] ?? ''), strtolower($movement)) !== false;
            });
        }

        // Re-index array values after filtering
        $allWatches = array_values($allWatches);

        // Sorting
        usort($allWatches, function ($a, $b) use ($sort) {
            return match ($sort) {
                'price_asc' => $a['price'] <=> $b['price'],
                'price_desc' => $b['price'] <=> $a['price'],
                'rating' => $b['rating'] <=> $a['rating'],
                'newest' => $b['id'] <=> $a['id'],
                default => ($b['featured'] ?? 0) <=> ($a['featured'] ?? 0),
            };
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'total' => count($allWatches),
                'watches' => $allWatches,
            ]);
        }

        $availableBrands = [
            'Rado', 'Citizen', 'Seiko', 'Casio General', 'Casio Edifice',
            'Casio G Shock', 'Omax General', 'Omax Masterpiece', 'Omax Premium',
            'Mathey Tissot', 'Ferro', 'Crysma', 'Kenneth Cole', 'Fossil',
            'Daniel Klein', 'Swiss Military Hanowa', 'Santa Barbara', 'Rolex', 'Longines', 'IWC'
        ];

        $availableCategories = [
            'STRAP' => 'Strap (Leather & Rubber)',
            'METAL CHAIN' => 'Metal Chain & Titanium',
            'ANALOG' => 'Analog Dials',
            'QUARTZ' => 'Quartz Movements',
            'DIGITAL' => 'Digital Multifunction',
            'AUTOMATIC' => 'Automatic & Mechanical',
        ];

        return view('shop', [
            'watches' => $allWatches,
            'totalCount' => count($allWatches),
            'selectedBrand' => $selectedBrand,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
            'sort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'movement' => $movement,
            'availableBrands' => $availableBrands,
            'availableCategories' => $availableCategories,
        ]);
    }

    /**
     * Display the specified product detail page.
     */
    public function show(int|string $id): View
    {
        $allWatches = $this->getMergedWatchCatalog();

        $watch = null;
        foreach ($allWatches as $w) {
            if ((string) $w['id'] === (string) $id) {
                $watch = $w;
                break;
            }
        }

        if (!$watch) {
            abort(404, 'Timepiece not found in our atelier collection.');
        }

        // Ensure gallery images has multiple angles
        if (empty($watch['images']) || count($watch['images']) < 2) {
            $watch['images'] = [
                $watch['image'],
                'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80',
            ];
        }

        // Find related watches (same brand or overlapping category, excluding current)
        $relatedWatches = array_filter($allWatches, function ($item) use ($watch) {
            if ((string) $item['id'] === (string) $watch['id']) {
                return false;
            }
            if (strcasecmp($item['brand'], $watch['brand']) === 0) {
                return true;
            }
            $currentCats = (array) ($watch['categories'] ?? []);
            $itemCats = (array) ($item['categories'] ?? []);
            return count(array_intersect($currentCats, $itemCats)) > 0;
        });

        $relatedWatches = array_slice(array_values($relatedWatches), 0, 4);

        return view('product-detail', [
            'watch' => $watch,
            'relatedWatches' => $relatedWatches,
        ]);
    }

    /**
     * Display the luxury horology checkout page.
     */
    public function checkout(Request $request): View
    {
        $allWatches = $this->getMergedWatchCatalog();

        $productId = $request->query('product_id');
        $sessionCart = session('cart', []);

        $cartItems = [];

        if ($productId) {
            $quantity = max(1, (int) $request->query('quantity', 1));
            $variant = $request->query('variant', 'Signature Dial');

            $selectedWatch = null;
            foreach ($allWatches as $w) {
                if ((string) $w['id'] === (string) $productId) {
                    $selectedWatch = $w;
                    break;
                }
            }

            if (! $selectedWatch) {
                $selectedWatch = $allWatches[0] ?? null;
            }

            $cartItems[] = [
                'watch' => $selectedWatch,
                'quantity' => $quantity,
                'variant' => $variant,
                'unit_price' => (float) $selectedWatch['price'],
                'line_total' => (float) ($selectedWatch['price'] * $quantity),
            ];
        } elseif (! empty($sessionCart) && is_array($sessionCart)) {
            foreach ($sessionCart as $item) {
                $itemId = $item['id'] ?? null;
                $watch = null;
                if ($itemId !== null) {
                    foreach ($allWatches as $w) {
                        if ((string) $w['id'] === (string) $itemId) {
                            $watch = $w;
                            break;
                        }
                    }
                }

                $rawPrice = (float) ($item['price'] ?? ($watch['price'] ?? 0));
                $qty = max(1, (int) ($item['quantity'] ?? 1));

                if (! $watch) {
                    $watch = [
                        'id' => $itemId ?? 1,
                        'title' => $item['title'] ?? 'Timepiece',
                        'brand' => $item['brand'] ?? 'TIMEYRAH',
                        'price' => $rawPrice,
                        'image' => $item['image'] ?? asset('watches/astra-black.png'),
                    ];
                }

                $cartItems[] = [
                    'watch' => $watch,
                    'quantity' => $qty,
                    'variant' => $item['variant'] ?? 'Signature Dial',
                    'unit_price' => $rawPrice,
                    'line_total' => $rawPrice * $qty,
                ];
            }

            $selectedWatch = $cartItems[0]['watch'] ?? ($allWatches[0] ?? null);
            $quantity = $cartItems[0]['quantity'] ?? 1;
            $variant = $cartItems[0]['variant'] ?? 'Signature Dial';
        } else {
            $selectedWatch = $allWatches[0] ?? null;
            $quantity = 1;
            $variant = 'Signature Dial';

            $cartItems[] = [
                'watch' => $selectedWatch,
                'quantity' => $quantity,
                'variant' => $variant,
                'unit_price' => (float) ($selectedWatch['price'] ?? 0),
                'line_total' => (float) (($selectedWatch['price'] ?? 0) * $quantity),
            ];
        }

        return view('checkout', [
            'cartItems' => $cartItems,
            'selectedWatch' => $selectedWatch,
            'quantity' => $quantity,
            'variant' => $variant,
        ]);
    }

    /**
     * Process checkout submission using the Post/Redirect/Get pattern.
     */
    public function processCheckout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:255',
            'apartment' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'delivery_notes' => 'nullable|string|max:500',
            'shipping_method' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'engraving' => 'nullable|string|max:30',
            'wrist_size' => 'nullable|string|max:20',
            'product_id' => 'nullable',
            'quantity' => 'nullable|integer|min:1',
            'variant' => 'nullable|string',
            'promo_code' => 'nullable|string|max:30',
            'cart_items_json' => 'nullable|string',
        ]);

        $allWatches = $this->getMergedWatchCatalog();
        $cartItemsJson = $request->input('cart_items_json');
        $orderItems = [];
        $subtotal = 0.0;

        if ($cartItemsJson) {
            $decoded = json_decode($cartItemsJson, true);
            if (is_array($decoded) && ! empty($decoded)) {
                foreach ($decoded as $ci) {
                    $uPrice = (float) ($ci['unit_price'] ?? 0);
                    $q = max(1, (int) ($ci['quantity'] ?? 1));
                    $subtotal += ($uPrice * $q);
                    $orderItems[] = $ci;
                }
            }
        }

        if (empty($orderItems)) {
            $productId = $request->input('product_id');
            $quantity = max(1, (int) $request->input('quantity', 1));
            $variant = $request->input('variant', 'Signature Dial');

            $watch = null;
            if ($productId) {
                foreach ($allWatches as $w) {
                    if ((string) $w['id'] === (string) $productId) {
                        $watch = $w;
                        break;
                    }
                }
            }
            if (! $watch) {
                $watch = $allWatches[0];
            }

            $subtotal = (float) ($watch['price'] * $quantity);
            $orderItems[] = [
                'watch' => $watch,
                'quantity' => $quantity,
                'variant' => $variant,
                'unit_price' => (float) $watch['price'],
                'line_total' => $subtotal,
            ];
        }

        $shippingMethod = $request->input('shipping_method', 'armored');
        $shippingCost = $shippingMethod === 'concierge' ? 150.00 : 0.00;

        $promoCode = strtoupper(trim($request->input('promo_code', '')));
        $discount = 0.00;
        if ($promoCode === 'TIMEYRAH10') {
            $discount = round($subtotal * 0.10, 2);
        } elseif ($promoCode === 'VIP2026') {
            $discount = min($subtotal, 200.00);
        }

        $total = max(0, $subtotal - $discount + $shippingCost);
        $orderNumber = 'TY-ORD-' . date('Y') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 7));

        $primaryWatch = $orderItems[0]['watch'] ?? $allWatches[0];
        $primaryQty = $orderItems[0]['quantity'] ?? 1;
        $primaryVariant = $orderItems[0]['variant'] ?? 'Signature Dial';

        $orderData = [
            'order_number' => $orderNumber,
            'customer' => $validated,
            'watch' => $primaryWatch,
            'quantity' => $primaryQty,
            'variant' => $primaryVariant,
            'items' => $orderItems,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'shipping_method' => $shippingMethod,
            'discount' => $discount,
            'promo_code' => $promoCode,
            'total' => $total,
            'payment_method' => $request->input('payment_method', 'card'),
            'card_last_four' => substr(preg_replace('/\D/', '', $request->input('card_number', '4242')), -4) ?: '4242',
            'engraving' => $request->input('engraving'),
            'wrist_size' => $request->input('wrist_size'),
            'placed_at' => now()->format('F j, Y, g:i A'),
            'estimated_delivery' => now()->addDays($shippingMethod === 'concierge' ? 2 : 5)->format('l, F j, Y'),
        ];

        session()->put('last_order', $orderData);
        session()->forget('cart');

        return redirect()->route('order.confirmation', ['orderNumber' => $orderNumber]);
    }

    /**
     * Synchronize the client-side cart items into the server session.
     */
    public function syncCart(Request $request): JsonResponse
    {
        $items = $request->input('items', []);
        session(['cart' => is_array($items) ? $items : []]);

        return response()->json([
            'success' => true,
            'count' => is_array($items) ? count($items) : 0,
        ]);
    }

    /**
     * Retrieve the current session-backed cart contents.
     */
    public function getCart(): JsonResponse
    {
        $items = session('cart', []);

        return response()->json([
            'success' => true,
            'items' => is_array($items) ? $items : [],
        ]);
    }

    /**
     * Clear the current session-backed cart.
     */
    public function clearCart(): JsonResponse
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Display order acquisition confirmation and Certificate of Authenticity.
     */
    public function confirmation(?string $orderNumber = null): View
    {
        $order = session('last_order');

        if (! $order) {
            // Provide a graceful fallback showcase order so the page never 404s or crashes on refresh
            $allWatches = $this->getMergedWatchCatalog();
            $watch = $allWatches[0];
            $order = [
                'order_number' => $orderNumber ?: ('TY-ORD-' . date('Y') . '-8837194'),
                'customer' => [
                    'first_name' => 'Alexander',
                    'last_name' => 'Sterling',
                    'email' => 'alexander.sterling@horology.vip',
                    'phone' => '+1 (555) 928-1120',
                    'address' => '740 Park Avenue, Penthouse 14B',
                    'apartment' => 'Penthouse 14B',
                    'city' => 'New York',
                    'state' => 'NY',
                    'postal_code' => '10021',
                    'country' => 'United States',
                ],
                'watch' => $watch,
                'quantity' => 1,
                'variant' => 'Signature Dial',
                'subtotal' => (float) $watch['price'],
                'shipping_cost' => 0.00,
                'shipping_method' => 'armored',
                'discount' => 0.00,
                'promo_code' => '',
                'total' => (float) $watch['price'],
                'payment_method' => 'card',
                'card_last_four' => '8892',
                'engraving' => 'AS • MMXXVI',
                'wrist_size' => '7.25 inches',
                'placed_at' => now()->format('F j, Y, g:i A'),
                'estimated_delivery' => now()->addDays(4)->format('l, F j, Y'),
            ];
        }

        return view('order-confirmation', [
            'order' => $order,
        ]);
    }

    /**
     * Merge DummyJSON watch endpoint data with curated catalog for all navbar brands.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getMergedWatchCatalog(): array
    {
        // Fetch external dummy API watches with 1-hour cache and timeout safety
        $apiWatches = Cache::remember('external_dummy_watches', 3600, function () {
            $results = [];
            try {
                $mensRes = Http::timeout(3)->get('https://dummyjson.com/products/category/mens-watches');
                if ($mensRes->successful() && isset($mensRes['products'])) {
                    foreach ($mensRes['products'] as $p) {
                        $results[] = $this->transformDummyJsonProduct($p, "Men's");
                    }
                }
            } catch (\Throwable $e) {
                // Graceful fallback if network is restricted
            }

            try {
                $womensRes = Http::timeout(3)->get('https://dummyjson.com/products/category/womens-watches');
                if ($womensRes->successful() && isset($womensRes['products'])) {
                    foreach ($womensRes['products'] as $p) {
                        $results[] = $this->transformDummyJsonProduct($p, "Women's");
                    }
                }
            } catch (\Throwable $e) {
                // Graceful fallback
            }

            return $results;
        });

        // Curated authentic watch catalog covering ALL 17+ navbar brands
        $localCatalog = $this->getLocalBrandCatalog();

        return array_merge($localCatalog, $apiWatches);
    }

    /**
     * Map DummyJSON product structure to our store's watch structure.
     *
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    protected function transformDummyJsonProduct(array $item, string $gender): array
    {
        $isMetal = stripos($item['title'], 'steel') !== false || stripos($item['title'], 'submariner') !== false || stripos($item['title'], 'gold') !== false;
        $categories = ['ANALOG'];
        $categories[] = $isMetal ? 'METAL CHAIN' : 'STRAP';
        $categories[] = stripos($item['title'], 'automatic') !== false || stripos($item['description'], 'automatic') !== false ? 'AUTOMATIC' : 'QUARTZ';

        return [
            'id' => 1000 + (int) $item['id'],
            'title' => $item['title'],
            'brand' => $item['brand'] ?? 'Maison Horology',
            'categories' => $categories,
            'price' => (float) $item['price'],
            'original_price' => round($item['price'] * 1.15, 2),
            'discount' => round($item['discountPercentage'] ?? 15) . '% OFF',
            'badge' => $item['brand'] . ' Certified',
            'rating' => (float) ($item['rating'] ?? 4.8),
            'reviews_count' => count($item['reviews'] ?? []) * 8 + 12,
            'image' => $item['thumbnail'] ?? ($item['images'][0] ?? 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80'),
            'images' => $item['images'] ?? [$item['thumbnail']],
            'description' => $item['description'] ?? 'Authentic certified luxury timepiece precision-crafted for unmatched horological performance.',
            'gender' => $gender,
            'featured' => 1,
            'stock' => (int) ($item['stock'] ?? 10),
            'specs' => [
                'case_diameter' => ($item['dimensions']['width'] ?? 40) . ' mm',
                'case_material' => $isMetal ? '316L Solid Stainless Steel' : 'Stainless Steel with PVD Coating',
                'strap_type' => $isMetal ? 'Stainless Steel Deployant Clasp' : 'Italian Handcrafted Calfskin Leather',
                'movement' => in_array('AUTOMATIC', $categories) ? 'Mechanical Self-Winding Caliber' : 'Swiss-Engineered Quartz',
                'water_resistance' => '100m / 10 ATM Water Resistant',
                'glass' => 'Scratch-Resistant Sapphire Crystal',
                'warranty' => $item['warrantyInformation'] ?? '2 Years International Warranty',
            ],
        ];
    }

    /**
     * Comprehensive catalog covering every brand in the navbar mega-menu.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getLocalBrandCatalog(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Rado Captain Cook High-Tech Ceramic Diver',
                'brand' => 'Rado',
                'categories' => ['AUTOMATIC', 'METAL CHAIN', 'ANALOG'],
                'price' => 3850.00,
                'original_price' => 4200.00,
                'discount' => '8% OFF',
                'badge' => 'High-Tech Ceramic',
                'rating' => 4.95,
                'reviews_count' => 64,
                'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'The Captain Cook High-Tech Ceramic is the culmination of years of research and development. Featuring Rados innovative ceramic monobloc case and an automatic caliber with 80 hours power reserve.',
                'gender' => "Men's",
                'featured' => 1,
                'stock' => 14,
                'specs' => [
                    'case_diameter' => '43.0 mm',
                    'case_material' => 'Monobloc High-Tech Ceramic',
                    'strap_type' => 'Matte Plasma Ceramic Bracelet',
                    'movement' => 'Rado Calibre R763 (80-hour reserve)',
                    'water_resistance' => '300m / 30 BAR Diver',
                    'glass' => 'Domed Sapphire Crystal with double Anti-Reflective coating',
                    'warranty' => '5 Years International Warranty',
                ],
            ],
            [
                'id' => 2,
                'title' => 'Casio G-Shock GA-2100-1A1 Carbon Core "CasiOak"',
                'brand' => 'Casio G Shock',
                'categories' => ['DIGITAL', 'ANALOG', 'QUARTZ', 'STRAP'],
                'price' => 110.00,
                'original_price' => 135.00,
                'discount' => '18% OFF',
                'badge' => 'Best Seller',
                'rating' => 4.9,
                'reviews_count' => 284,
                'image' => 'https://images.unsplash.com/photo-1508057198894-247b23fe5ade?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1508057198894-247b23fe5ade?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'The iconic all-black stealth CasiOak with Carbon Core Guard structure. Slim 11.8mm profile, double LED lighting, 200m water resistance, and unmatched shock-absorbing toughness.',
                'gender' => 'Unisex',
                'featured' => 1,
                'stock' => 45,
                'specs' => [
                    'case_diameter' => '45.4 mm (thin 11.8mm depth)',
                    'case_material' => 'Carbon-Reinforced Fine Resin',
                    'strap_type' => 'Resin Band with Matte Black Finish',
                    'movement' => 'Casio Module 5611 Multifunction Quartz',
                    'water_resistance' => '200m / 20 BAR Shock Resistant',
                    'glass' => 'Mineral Glass',
                    'warranty' => '2 Years Official Casio Warranty',
                ],
            ],
            [
                'id' => 3,
                'title' => 'Seiko Prospex Speedtimer Solar Chronograph SSC813',
                'brand' => 'Seiko',
                'categories' => ['METAL CHAIN', 'ANALOG', 'QUARTZ'],
                'price' => 675.00,
                'original_price' => 750.00,
                'discount' => '10% OFF',
                'badge' => 'Solar Powered',
                'rating' => 4.92,
                'reviews_count' => 119,
                'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'The celebrated "Seitona" Panda dial chronograph. Solar-powered caliber V192 charges from any light source with a 6-month reserve when fully charged.',
                'gender' => "Men's",
                'featured' => 1,
                'stock' => 20,
                'specs' => [
                    'case_diameter' => '39.0 mm',
                    'case_material' => '316L Stainless Steel',
                    'strap_type' => 'Three-Link Steel Bracelet',
                    'movement' => 'Seiko Solar Caliber V192 Chronograph',
                    'water_resistance' => '100m / 10 BAR',
                    'glass' => 'Curved Sapphire Crystal with Anti-Reflective coating',
                    'warranty' => '3 Years International Seiko Warranty',
                ],
            ],
            [
                'id' => 4,
                'title' => 'Citizen Tsuyosa Automatic Sunray Yellow Dial',
                'brand' => 'Citizen',
                'categories' => ['AUTOMATIC', 'METAL CHAIN', 'ANALOG'],
                'price' => 450.00,
                'original_price' => 495.00,
                'discount' => '9% OFF',
                'badge' => 'Integrated Bracelet',
                'rating' => 4.88,
                'reviews_count' => 87,
                'image' => 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'The Citizen Tsuyosa delivers ultra-contemporary sports-chic styling with a vibrant sunray dial, exhibition case back, and integrated stainless steel president-style bracelet.',
                'gender' => "Men's",
                'featured' => 1,
                'stock' => 18,
                'specs' => [
                    'case_diameter' => '40.0 mm',
                    'case_material' => 'Stainless Steel with Crown at 4 oclock',
                    'strap_type' => 'Integrated Stainless Steel Rounded Links',
                    'movement' => 'Citizen Miyota Caliber 8210 Automatic',
                    'water_resistance' => '50m / 5 BAR',
                    'glass' => 'Sapphire Crystal with Date Magnifier',
                    'warranty' => '5 Years Official Citizen Warranty',
                ],
            ],
            [
                'id' => 5,
                'title' => 'Casio Edifice Solar Bluetooth Chronograph ECB-900',
                'brand' => 'Casio Edifice',
                'categories' => ['METAL CHAIN', 'ANALOG', 'DIGITAL', 'QUARTZ'],
                'price' => 220.00,
                'original_price' => 260.00,
                'discount' => '15% OFF',
                'badge' => 'Bluetooth Sync',
                'rating' => 4.82,
                'reviews_count' => 53,
                'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Motorsport-inspired speed and intelligence. Connects seamlessly with your smartphone for automatic time zone correction and lap time measurement down to 1/1000th second.',
                'gender' => "Men's",
                'featured' => 0,
                'stock' => 22,
                'specs' => [
                    'case_diameter' => '48.0 mm',
                    'case_material' => 'Stainless Steel with Tachymeter Bezel',
                    'strap_type' => 'Solid Stainless Steel Band',
                    'movement' => 'Tough Solar Quartz with Bluetooth Link',
                    'water_resistance' => '100m / 10 BAR',
                    'glass' => 'Mineral Crystal Glass',
                    'warranty' => '2 Years Official Warranty',
                ],
            ],
            [
                'id' => 6,
                'title' => 'Casio Vintage Digital Illuminator A168WA',
                'brand' => 'Casio General',
                'categories' => ['DIGITAL', 'METAL CHAIN', 'QUARTZ'],
                'price' => 38.00,
                'original_price' => 45.00,
                'discount' => '15% OFF',
                'badge' => 'Classic Icon',
                'rating' => 4.94,
                'reviews_count' => 512,
                'image' => 'https://images.unsplash.com/photo-1526045431048-f857369baa09?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1526045431048-f857369baa09?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'The timeless 80s retro digital masterpiece. Features Casio electroluminescent backlight, daily alarm, 1/100s stopwatch, and auto calendar.',
                'gender' => 'Unisex',
                'featured' => 0,
                'stock' => 120,
                'specs' => [
                    'case_diameter' => '36.3 mm',
                    'case_material' => 'Chrome Plated Resin Case',
                    'strap_type' => 'Adjustable Stainless Steel Mesh Clasp',
                    'movement' => 'Casio Module 3298 Digital Quartz (7-year battery)',
                    'water_resistance' => 'Daily Splash Resistant',
                    'glass' => 'Resin Glass',
                    'warranty' => '2 Years Official Warranty',
                ],
            ],
            [
                'id' => 7,
                'title' => 'Mathey-Tissot Mathy Vintage Automatic GMT H903ATAN',
                'brand' => 'Mathey Tissot',
                'categories' => ['AUTOMATIC', 'METAL CHAIN', 'ANALOG'],
                'price' => 590.00,
                'original_price' => 690.00,
                'discount' => '14% OFF',
                'badge' => 'Swiss Made',
                'rating' => 4.85,
                'reviews_count' => 42,
                'image' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Swiss horological pedigree since 1886. Robust 42mm diver design with bi-directional 24-hour Pepsi bezel and Swiss automatic caliber with GMT dual time indication.',
                'gender' => "Men's",
                'featured' => 1,
                'stock' => 12,
                'specs' => [
                    'case_diameter' => '42.0 mm',
                    'case_material' => '316L Stainless Steel',
                    'strap_type' => 'Jubilee-style Stainless Steel Bracelet',
                    'movement' => 'Swiss Automatic Caliber with GMT Hand',
                    'water_resistance' => '100m / 10 BAR',
                    'glass' => 'Sapphire Coated Crystal Glass',
                    'warranty' => '3 Years International Warranty',
                ],
            ],
            [
                'id' => 8,
                'title' => 'Fossil Grant Chronograph Luggage Leather FS4813',
                'brand' => 'Fossil',
                'categories' => ['STRAP', 'ANALOG', 'QUARTZ'],
                'price' => 145.00,
                'original_price' => 170.00,
                'discount' => '15% OFF',
                'badge' => 'Italian Leather',
                'rating' => 4.79,
                'reviews_count' => 163,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Classic roman numeral markers with vintage aeronautical chronometer styling on a genuine rich amber brown calfskin leather strap.',
                'gender' => "Men's",
                'featured' => 0,
                'stock' => 30,
                'specs' => [
                    'case_diameter' => '44.0 mm',
                    'case_material' => 'Polished Stainless Steel',
                    'strap_type' => '22mm Genuine Calfskin Leather with Buckle',
                    'movement' => 'Precision Quartz Chronograph (Sub-dials for 24h & stop-second)',
                    'water_resistance' => '50m / 5 BAR',
                    'glass' => 'Mineral Hardened Crystal',
                    'warranty' => '2 Years Official Warranty',
                ],
            ],
            [
                'id' => 9,
                'title' => 'Omax Masterpiece Dual-Tone Luxury Dress Watch',
                'brand' => 'Omax Masterpiece',
                'categories' => ['METAL CHAIN', 'ANALOG', 'QUARTZ'],
                'price' => 85.00,
                'original_price' => 110.00,
                'discount' => '22% OFF',
                'badge' => 'Water Resistant',
                'rating' => 4.72,
                'reviews_count' => 74,
                'image' => 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Swiss design legacy with Japanese precision movement. Features gold-tone fluted bezel, diamond index markers, and day-date display.',
                'gender' => "Men's",
                'featured' => 0,
                'stock' => 25,
                'specs' => [
                    'case_diameter' => '41.0 mm',
                    'case_material' => 'Two-Tone Stainless Steel with PVD Gold Fluted Bezel',
                    'strap_type' => 'Two-Tone Stainless Steel Bracelet',
                    'movement' => 'Japanese Quartz Movement',
                    'water_resistance' => '50m Water Proof',
                    'glass' => 'Hardened Crystal Glass with Cyclops Lens',
                    'warranty' => '2 Years Omax Warranty',
                ],
            ],
            [
                'id' => 10,
                'title' => 'Omax Premium Open-Heart Automatic Skeleton',
                'brand' => 'Omax Premium',
                'categories' => ['AUTOMATIC', 'METAL CHAIN', 'ANALOG'],
                'price' => 175.00,
                'original_price' => 220.00,
                'discount' => '20% OFF',
                'badge' => 'Open Heart Caliber',
                'rating' => 4.81,
                'reviews_count' => 38,
                'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Exquisite open-heart escapement window reveals the mechanical heartbeat. Fitted with an all-black gunmetal case and exhibition back.',
                'gender' => "Men's",
                'featured' => 1,
                'stock' => 16,
                'specs' => [
                    'case_diameter' => '42.5 mm',
                    'case_material' => 'Gunmetal Ion-Plated Stainless Steel',
                    'strap_type' => 'Gunmetal Stainless Steel Deployment',
                    'movement' => 'Automatic 21-Jewel Mechanical Caliber',
                    'water_resistance' => '50m / 5 BAR',
                    'glass' => 'Scratch Resistant Mineral Glass',
                    'warranty' => '2 Years Omax Warranty',
                ],
            ],
            [
                'id' => 11,
                'title' => 'Omax General Crystal Classic Quartz',
                'brand' => 'Omax General',
                'categories' => ['STRAP', 'ANALOG', 'QUARTZ'],
                'price' => 45.00,
                'original_price' => 60.00,
                'discount' => '25% OFF',
                'badge' => 'Daily Essential',
                'rating' => 4.68,
                'reviews_count' => 61,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Reliable, affordable everyday analog timekeeper with clean minimalist dial and genuine textured leather strap.',
                'gender' => 'Unisex',
                'featured' => 0,
                'stock' => 50,
                'specs' => [
                    'case_diameter' => '39.0 mm',
                    'case_material' => 'Polished Alloy Steel',
                    'strap_type' => 'Genuine Black Leather Band',
                    'movement' => 'Seiko Epson Quartz Caliber',
                    'water_resistance' => '30m Water Resistant',
                    'glass' => 'Mineral Crystal',
                    'warranty' => '1 Year Official Warranty',
                ],
            ],
            [
                'id' => 12,
                'title' => 'Swiss Military Hanowa Helvetus Automatic 06-5316',
                'brand' => 'Swiss Military Hanowa',
                'categories' => ['AUTOMATIC', 'STRAP', 'ANALOG'],
                'price' => 520.00,
                'original_price' => 590.00,
                'discount' => '12% OFF',
                'badge' => 'Swiss Made',
                'rating' => 4.89,
                'reviews_count' => 45,
                'image' => 'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1542496658-e33a6d0d50f6?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Officially licensed by the Swiss Confederation. Rugged elegance built with military-grade precision, sapphire crystal, and Swiss automatic movement.',
                'gender' => "Men's",
                'featured' => 1,
                'stock' => 15,
                'specs' => [
                    'case_diameter' => '40.0 mm',
                    'case_material' => '316L Brushed Surgical Steel',
                    'strap_type' => 'Brown Saddle Stitched Swiss Leather',
                    'movement' => 'Swiss Made STP1-11 Automatic (28,800 bph)',
                    'water_resistance' => '100m / 10 BAR',
                    'glass' => 'Anti-Reflective Sapphire Crystal',
                    'warranty' => '3 Years Swiss Military Warranty',
                ],
            ],
            [
                'id' => 13,
                'title' => 'Daniel Klein Exclusive Multi-Function Chrono DK1289',
                'brand' => 'Daniel Klein',
                'categories' => ['METAL CHAIN', 'ANALOG', 'QUARTZ'],
                'price' => 78.00,
                'original_price' => 95.00,
                'discount' => '18% OFF',
                'badge' => 'Modern Fashion',
                'rating' => 4.75,
                'reviews_count' => 92,
                'image' => 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Sleek metropolitan aesthetic featuring dual-time complication, day-night indicator, and solid stainless steel bracelet.',
                'gender' => "Men's",
                'featured' => 0,
                'stock' => 35,
                'specs' => [
                    'case_diameter' => '43.0 mm',
                    'case_material' => 'Stainless Steel with IP Gunmetal Coating',
                    'strap_type' => 'Matching Stainless Steel Mesh Bracelet',
                    'movement' => 'High-Precision Multi-Function Quartz',
                    'water_resistance' => '50m Water Resistant',
                    'glass' => 'Curved Hardened Crystal',
                    'warranty' => '2 Years Official Warranty',
                ],
            ],
            [
                'id' => 14,
                'title' => 'Kenneth Cole Skeleton Automatic Dial Leather Watch',
                'brand' => 'Kenneth Cole',
                'categories' => ['AUTOMATIC', 'STRAP', 'ANALOG'],
                'price' => 195.00,
                'original_price' => 240.00,
                'discount' => '19% OFF',
                'badge' => 'Full Skeleton',
                'rating' => 4.84,
                'reviews_count' => 67,
                'image' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Signature transparent skeleton dial showcasing the balance wheel and intricate mechanical geartrain. Premium alligator-embossed genuine leather strap.',
                'gender' => "Men's",
                'featured' => 1,
                'stock' => 20,
                'specs' => [
                    'case_diameter' => '44.0 mm',
                    'case_material' => 'Polished Stainless Steel with Rose Gold Accents',
                    'strap_type' => 'Black Alligator-Embossed Leather Strap',
                    'movement' => 'Self-Winding 21-Jewel Automatic Mechanical',
                    'water_resistance' => '30m Water Resistant',
                    'glass' => 'Hardened Mineral Crystal',
                    'warranty' => '2 Years Official Warranty',
                ],
            ],
            [
                'id' => 15,
                'title' => 'Santa Barbara Polo & Racquet Club Royal Steel SB-1022',
                'brand' => 'Santa Barbara',
                'categories' => ['METAL CHAIN', 'ANALOG', 'QUARTZ'],
                'price' => 135.00,
                'original_price' => 165.00,
                'discount' => '18% OFF',
                'badge' => 'Equestrian Heritage',
                'rating' => 4.77,
                'reviews_count' => 48,
                'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Inspired by the historic California polo club founded in 1911. Features a rich emerald green sunray dial, gold hands, and solid stainless steel link bracelet.',
                'gender' => "Men's",
                'featured' => 0,
                'stock' => 19,
                'specs' => [
                    'case_diameter' => '42.0 mm',
                    'case_material' => 'Stainless Steel with Polished & Brushed Finish',
                    'strap_type' => 'Solid Steel Link Bracelet with Safety Clasp',
                    'movement' => 'Japanese Quartz Multi-Eye Movement',
                    'water_resistance' => '50m / 5 BAR',
                    'glass' => 'Scratch Resistant Mineral Glass',
                    'warranty' => '2 Years Official Warranty',
                ],
            ],
            [
                'id' => 16,
                'title' => 'Ferro Distinct Single-Hand Retrograde Dial',
                'brand' => 'Ferro',
                'categories' => ['STRAP', 'ANALOG', 'QUARTZ'],
                'price' => 160.00,
                'original_price' => 190.00,
                'discount' => '16% OFF',
                'badge' => 'Single Hand Horology',
                'rating' => 4.83,
                'reviews_count' => 39,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Minimalist single-hand gauge design reminiscent of classic motorsport tachometers. Italian oil-waxed leather strap that patinas beautifully over time.',
                'gender' => 'Unisex',
                'featured' => 0,
                'stock' => 14,
                'specs' => [
                    'case_diameter' => '41.0 mm',
                    'case_material' => 'Satin-Brushed Titanium-Grey PVD Steel',
                    'strap_type' => 'Vintage Italian Waxed Saddle Leather',
                    'movement' => 'Swiss Ronda 515 Quartz Movement',
                    'water_resistance' => '50m / 5 BAR',
                    'glass' => 'Sapphire Coated Crystal',
                    'warranty' => '2 Years International Warranty',
                ],
            ],
            [
                'id' => 17,
                'title' => 'Crysma Elegance Sunray Rose Gold Mesh Watch',
                'brand' => 'Crysma',
                'categories' => ['METAL CHAIN', 'ANALOG', 'QUARTZ'],
                'price' => 95.00,
                'original_price' => 120.00,
                'discount' => '21% OFF',
                'badge' => 'Rose Gold PVD',
                'rating' => 4.76,
                'reviews_count' => 58,
                'image' => 'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=600&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=600&q=80'
                ],
                'description' => 'Ultra-thin modern dress watch with warm rose gold case, minimalist baton hands, and a supple Milanese mesh bracelet.',
                'gender' => "Women's",
                'featured' => 0,
                'stock' => 28,
                'specs' => [
                    'case_diameter' => '36.0 mm (Ultra-slim 6.8mm)',
                    'case_material' => 'Rose Gold PVD Stainless Steel',
                    'strap_type' => 'Rose Gold Milanese Magnetic Mesh Band',
                    'movement' => 'Slim Japanese Quartz Caliber',
                    'water_resistance' => '30m Water Resistant',
                    'glass' => 'Hardened Mineral Crystal',
                    'warranty' => '2 Years Official Warranty',
                ],
            ],
        ];
    }
}
