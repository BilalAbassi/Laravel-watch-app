<?php

namespace App\Http\Controllers;

use App\Actions\PlaceOrder;
use App\Enums\PaymentMethod;
use App\Http\Requests\CheckoutRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Friendly display labels for standard watch categories in the filter sidebar.
     *
     * @var array<string, string>
     */
    private const CATEGORY_LABELS = [
        'STRAP' => 'Strap (Leather & Rubber)',
        'METAL CHAIN' => 'Metal Chain & Titanium',
        'ANALOG' => 'Analog Dials',
        'QUARTZ' => 'Quartz Movements',
        'DIGITAL' => 'Digital Multifunction',
        'AUTOMATIC' => 'Automatic & Mechanical',
        "MEN'S" => "Men's Watches",
        "WOMEN'S" => "Women's Watches",
        'UNISEX' => 'Unisex Timepieces',
    ];

    /**
     * Display the shop listing page with database-driven filtering, search, sorting, and pagination.
     */
    public function index(Request $request): View|JsonResponse
    {
        $selectedBrand = $request->query('brand');
        $selectedCategory = $request->query('category');
        $selectedGender = $request->query('gender');
        $search = $request->query('search');
        $sort = (string) $request->query('sort', 'featured');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $movement = $request->query('movement');

        $query = Product::active()->with(['brand', 'categories', 'images']);

        if (is_string($selectedBrand) && trim($selectedBrand) !== '') {
            $brandTerm = '%'.trim($selectedBrand).'%';
            $query->where(function (Builder $q) use ($brandTerm): void {
                $q->whereHas('brand', function (Builder $brandQuery) use ($brandTerm): void {
                    $brandQuery->where('name', 'like', $brandTerm)
                        ->orWhere('slug', 'like', $brandTerm);
                })->orWhere('title', 'like', $brandTerm);
            });
        }

        if (is_string($selectedCategory) && trim($selectedCategory) !== '') {
            $categoryTerm = '%'.trim($selectedCategory).'%';
            $query->where(function (Builder $q) use ($categoryTerm): void {
                $q->whereHas('categories', function (Builder $catQuery) use ($categoryTerm): void {
                    $catQuery->where('name', 'like', $categoryTerm)
                        ->orWhere('slug', 'like', $categoryTerm);
                })->orWhereHas('category', function (Builder $primaryCatQuery) use ($categoryTerm): void {
                    $primaryCatQuery->where('name', 'like', $categoryTerm)
                        ->orWhere('slug', 'like', $categoryTerm);
                });
            });
        }

        if (is_string($selectedGender) && trim($selectedGender) !== '') {
            $normalizedGender = match (strtolower(trim($selectedGender))) {
                'men', 'mens', "men's" => "Men's",
                'women', 'womens', "women's" => "Women's",
                'unisex' => 'Unisex',
                default => trim($selectedGender),
            };

            $query->where(function (Builder $q) use ($normalizedGender): void {
                $q->where('gender', $normalizedGender)
                    ->orWhereHas('categories', function (Builder $catQuery) use ($normalizedGender): void {
                        $catQuery->where('name', strtoupper($normalizedGender))
                            ->orWhere('slug', Str::slug($normalizedGender));
                    });
            });
        }

        if (is_string($search) && trim($search) !== '') {
            $searchTerm = '%'.trim($search).'%';
            $query->where(function (Builder $q) use ($searchTerm): void {
                $q->where('title', 'like', $searchTerm)
                    ->orWhere('sku', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm)
                    ->orWhere('movement', 'like', $searchTerm)
                    ->orWhereHas('brand', function (Builder $brandQuery) use ($searchTerm): void {
                        $brandQuery->where('name', 'like', $searchTerm);
                    });
            });
        }

        if ($minPrice !== null && $minPrice !== '' && is_numeric($minPrice)) {
            $min = (float) $minPrice;
            $query->where(function (Builder $q) use ($min): void {
                $q->where(function (Builder $saleQuery) use ($min): void {
                    $saleQuery->whereNotNull('sale_price')->where('sale_price', '>=', $min);
                })->orWhere(function (Builder $regularQuery) use ($min): void {
                    $regularQuery->whereNull('sale_price')->where('price', '>=', $min);
                });
            });
        }

        if ($maxPrice !== null && $maxPrice !== '' && is_numeric($maxPrice)) {
            $max = (float) $maxPrice;
            $query->where(function (Builder $q) use ($max): void {
                $q->where(function (Builder $saleQuery) use ($max): void {
                    $saleQuery->whereNotNull('sale_price')->where('sale_price', '<=', $max);
                })->orWhere(function (Builder $regularQuery) use ($max): void {
                    $regularQuery->whereNull('sale_price')->where('price', '<=', $max);
                });
            });
        }

        if (is_string($movement) && trim($movement) !== '') {
            $query->where('movement', 'like', '%'.trim($movement).'%');
        }

        match ($sort) {
            'price_asc' => $query->orderByRaw('CAST(COALESCE(sale_price, price) AS DECIMAL(10, 2)) ASC')->orderBy('id'),
            'price_desc' => $query->orderByRaw('CAST(COALESCE(sale_price, price) AS DECIMAL(10, 2)) DESC')->orderBy('id'),
            'rating' => $query->orderByDesc('rating')->orderBy('id'),
            'newest' => $query->orderByDesc('id'),
            default => $query->orderByDesc('is_featured')->orderBy('id'),
        };

        $perPage = (int) $request->query('per_page', 9);
        if ($perPage < 1 || $perPage > 48) {
            $perPage = 9;
        }

        $paginatedWatches = $query->paginate($perPage)->withQueryString();
        $paginatedWatches->through(fn (Product $product): array => $product->toCatalogArray());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'total' => $paginatedWatches->total(),
                'current_page' => $paginatedWatches->currentPage(),
                'last_page' => $paginatedWatches->lastPage(),
                'per_page' => $paginatedWatches->perPage(),
                'watches' => $paginatedWatches->items(),
            ]);
        }

        $availableBrands = Brand::active()->orderBy('id')->pluck('name')->all();

        $availableCategories = Category::active()
            ->orderBy('id')
            ->get()
            ->mapWithKeys(fn (Category $category): array => [
                $category->name => self::CATEGORY_LABELS[$category->name] ?? Str::title($category->name),
            ])
            ->all();

        if ($availableCategories === []) {
            $availableCategories = self::CATEGORY_LABELS;
        }

        return view('shop', [
            'watches' => $paginatedWatches,
            'totalCount' => $paginatedWatches->total(),
            'selectedBrand' => $selectedBrand,
            'selectedCategory' => $selectedCategory,
            'selectedGender' => $selectedGender,
            'search' => $search,
            'sort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'movement' => $movement,
            'availableBrands' => $availableBrands,
            'availableCategories' => $availableCategories,
            'availableGenders' => ["Men's", "Women's", 'Unisex'],
        ]);
    }

    /**
     * Display the product detail page for a watch by ID or slug.
     */
    public function show(int|string $id): View
    {
        $product = Product::active()
            ->with(['brand', 'categories', 'images'])
            ->where(function (Builder $query) use ($id): void {
                if (is_numeric($id)) {
                    $query->where('id', (int) $id)->orWhere('slug', (string) $id);
                } else {
                    $query->where('slug', (string) $id);
                }
            })
            ->firstOrFail();

        $categoryIds = $product->categories->pluck('id')->all();

        $relatedWatches = Product::active()
            ->with(['brand', 'categories', 'images'])
            ->whereKeyNot($product->id)
            ->where(function (Builder $query) use ($product, $categoryIds): void {
                $query->where('brand_id', $product->brand_id);
                if ($categoryIds !== []) {
                    $query->orWhereHas('categories', function (Builder $catQuery) use ($categoryIds): void {
                        $catQuery->whereIn('categories.id', $categoryIds);
                    });
                }
            })
            ->orderByRaw('CASE WHEN brand_id = ? THEN 0 ELSE 1 END', [$product->brand_id])
            ->orderByDesc('is_featured')
            ->orderBy('id')
            ->limit(4)
            ->get()
            ->map(fn (Product $related): array => $related->toCatalogArray())
            ->all();

        return view('product-detail', [
            'watch' => $product->toCatalogArray(),
            'relatedWatches' => $relatedWatches,
        ]);
    }

    /**
     * Display the checkout page with database-verified prices.
     */
    public function checkout(Request $request): View
    {
        $productId = $request->query('product_id');
        $sessionCart = session('cart', []);

        $cartItems = [];
        $selectedWatch = null;
        $quantity = 1;
        $variant = 'Signature Dial';

        if ($productId !== null && is_numeric($productId)) {
            $quantity = max(1, (int) $request->query('quantity', 1));
            $variant = (string) $request->query('variant', 'Signature Dial');

            $product = Product::active()
                ->with(['brand', 'categories', 'images'])
                ->find((int) $productId)
                ?? $this->firstFeaturedProduct();

            if ($product) {
                $selectedWatch = $product->toCatalogArray();
                $cartItems[] = [
                    'watch' => $selectedWatch,
                    'quantity' => $quantity,
                    'variant' => $variant,
                    'unit_price' => (float) $selectedWatch['price'],
                    'line_total' => round((float) $selectedWatch['price'] * $quantity, 2),
                ];
            }
        } elseif (is_array($sessionCart) && $sessionCart !== []) {
            $cartProductIds = collect($sessionCart)
                ->map(fn (mixed $item): ?int => is_array($item) && isset($item['id']) && is_numeric($item['id']) ? (int) $item['id'] : null)
                ->filter()
                ->unique()
                ->values()
                ->all();

            $productsById = Product::active()
                ->with(['brand', 'categories', 'images'])
                ->whereIn('id', $cartProductIds)
                ->get()
                ->keyBy('id');

            foreach ($sessionCart as $item) {
                if (! is_array($item)) {
                    continue;
                }

                $itemId = isset($item['id']) && is_numeric($item['id']) ? (int) $item['id'] : null;
                $product = $itemId !== null ? $productsById->get($itemId) : null;

                if (! $product) {
                    continue;
                }

                $watchArray = $product->toCatalogArray();
                $qty = max(1, (int) ($item['quantity'] ?? 1));
                $unitPrice = (float) $watchArray['price'];

                $cartItems[] = [
                    'watch' => $watchArray,
                    'quantity' => $qty,
                    'variant' => (string) ($item['variant'] ?? 'Signature Dial'),
                    'unit_price' => $unitPrice,
                    'line_total' => round($unitPrice * $qty, 2),
                ];
            }

            if ($cartItems !== []) {
                $selectedWatch = $cartItems[0]['watch'];
                $quantity = $cartItems[0]['quantity'];
                $variant = $cartItems[0]['variant'];
            }
        }

        if ($cartItems === []) {
            $fallbackProduct = $this->firstFeaturedProduct();
            if ($fallbackProduct) {
                $selectedWatch = $fallbackProduct->toCatalogArray();
                $cartItems[] = [
                    'watch' => $selectedWatch,
                    'quantity' => 1,
                    'variant' => 'Signature Dial',
                    'unit_price' => (float) $selectedWatch['price'],
                    'line_total' => (float) $selectedWatch['price'],
                ];
            }
        }

        return view('checkout', [
            'cartItems' => $cartItems,
            'selectedWatch' => $selectedWatch,
            'quantity' => $quantity,
            'variant' => $variant,
        ]);
    }

    /**
     * Validate customer details, create the Order and OrderItems in the database, and redirect to confirmation.
     */
    public function processCheckout(CheckoutRequest $request, PlaceOrder $placeOrder): RedirectResponse
    {
        $sessionCart = session('cart', []);

        $order = $placeOrder->handle(
            $request->validated(),
            $request->user()?->id,
            is_array($sessionCart) ? $sessionCart : []
        );

        if ($order->payment_method === PaymentMethod::Card) {
            $digits = preg_replace('/\D/', '', (string) $request->input('card_number', '')) ?? '';
            $cardLastFour = substr($digits, -4);
            session()->put('order_card_last_four.'.$order->order_number, $cardLastFour !== '' ? $cardLastFour : '4242');
        }

        session()->put('last_order_number', $order->order_number);
        session()->forget('cart');

        return redirect()->route('order.confirmation', ['orderNumber' => $order->order_number]);
    }

    /**
     * Synchronize client-side cart items into the server session.
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
     * Display the order receipt and warranty confirmation from the database.
     */
    public function confirmation(?string $orderNumber = null): View
    {
        $lookupNumber = $orderNumber ?? session('last_order_number');
        $orderModel = null;

        if (is_string($lookupNumber) && $lookupNumber !== '') {
            $orderQuery = Order::with(['items.product.brand', 'items.product.categories', 'items.product.images'])
                ->where('order_number', $lookupNumber);

            $orderModel = $orderNumber !== null
                ? $orderQuery->firstOrFail()
                : $orderQuery->first();
        }

        $order = $orderModel
            ? $this->formatOrderForView($orderModel)
            : $this->fallbackOrderForView();

        return view('order-confirmation', [
            'order' => $order,
        ]);
    }

    /**
     * Fetch the first active featured watch for default checkout or receipt previews.
     */
    private function firstFeaturedProduct(): ?Product
    {
        return Product::active()
            ->with(['brand', 'categories', 'images'])
            ->orderByDesc('is_featured')
            ->orderBy('id')
            ->first();
    }

    /**
     * Transform a saved Order model into the presentation array used by order-confirmation.blade.php.
     *
     * @return array<string, mixed>
     */
    private function formatOrderForView(Order $order): array
    {
        $nameParts = explode(' ', trim($order->customer_name), 2);
        $firstName = $nameParts[0] !== '' ? $nameParts[0] : 'Customer';
        $lastName = $nameParts[1] ?? '';

        $primaryItem = $order->items->first();
        $primaryProductCatalog = $primaryItem?->product?->toCatalogArray();

        $watchTitle = $primaryItem?->product_title ?? 'Original Watch';
        if ($order->items->count() > 1) {
            $extraCount = $order->items->count() - 1;
            $watchTitle .= ' (+'.$extraCount.' more)';
        }

        $placedAt = $order->created_at ?? now();
        $deliveryDays = $order->shipping_method === 'concierge' ? 2 : 5;

        return [
            'order_number' => $order->order_number,
            'order_status' => $order->order_status->value,
            'customer' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'address' => $order->delivery_address,
                'apartment' => '',
                'city' => $order->delivery_city,
                'state' => $order->delivery_state ?? '',
                'postal_code' => $order->postal_code ?? '',
                'country' => $order->country,
            ],
            'watch' => [
                'id' => $primaryItem?->product_id ?? 1,
                'title' => $watchTitle,
                'brand' => $primaryProductCatalog['brand'] ?? 'TIMEYRAH',
                'image' => $primaryProductCatalog['image'] ?? asset('watches/astra-black.png'),
                'price' => (float) ($primaryItem?->unit_price ?? $order->subtotal),
            ],
            'quantity' => max(1, (int) $order->items->sum('quantity')),
            'variant' => $primaryItem?->dial_variant ?? 'Signature Dial',
            'subtotal' => (float) $order->subtotal,
            'shipping_cost' => (float) $order->shipping_cost,
            'shipping_method' => $order->shipping_method ?? 'armored',
            'discount' => (float) $order->discount_amount,
            'promo_code' => $order->coupon_code ?? '',
            'total' => (float) $order->total_amount,
            'payment_method' => $order->payment_method->label(),
            'card_last_four' => $order->payment_method === PaymentMethod::Card
                ? (string) session('order_card_last_four.'.$order->order_number, '4242')
                : '',
            'engraving' => $order->engraving,
            'wrist_size' => $order->wrist_size,
            'placed_at' => $placedAt->format('F j, Y, g:i A'),
            'estimated_delivery' => $placedAt->copy()->addDays($deliveryDays)->format('l, F j, Y'),
        ];
    }

    /**
     * Provide a Pakistani-localized sample receipt when visiting /order-confirmation directly without an order.
     *
     * @return array<string, mixed>
     */
    private function fallbackOrderForView(): array
    {
        $product = $this->firstFeaturedProduct();
        $watch = $product ? $product->toCatalogArray() : [
            'id' => 1,
            'title' => 'Rado Captain Cook High-Tech Ceramic Diver',
            'brand' => 'Rado',
            'price' => 3850.00,
            'image' => asset('watches/titlon-dive.png'),
        ];

        return [
            'order_number' => 'TY-ORD-'.date('Y').'-PK10001',
            'order_status' => 'Pending',
            'customer' => [
                'first_name' => 'Ali',
                'last_name' => 'Khan',
                'email' => 'ali.khan@example.com',
                'phone' => '+92 300 1234567',
                'address' => 'House 14, Street 6, DHA Phase 6',
                'apartment' => '',
                'city' => 'Karachi',
                'state' => 'Sindh',
                'postal_code' => '75500',
                'country' => 'Pakistan',
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
            'payment_method' => PaymentMethod::Cod->label(),
            'card_last_four' => '',
            'engraving' => null,
            'wrist_size' => 'factory',
            'placed_at' => now()->format('F j, Y, g:i A'),
            'estimated_delivery' => now()->addDays(5)->format('l, F j, Y'),
        ];
    }
}
