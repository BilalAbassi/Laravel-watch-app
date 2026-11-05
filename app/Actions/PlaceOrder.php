<?php

namespace App\Actions;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PlaceOrder
{
    /**
     * Validate cart items against database prices & stock, calculate totals, and save the order.
     *
     * @param  array<string, mixed>  $validated
     * @param  array<int, array<string, mixed>>  $sessionCart
     *
     * @throws ValidationException
     */
    public function handle(array $validated, ?int $userId = null, array $sessionCart = []): Order
    {
        $requestedLines = $this->extractRequestedLines($validated, $sessionCart);

        return DB::transaction(function () use ($validated, $userId, $requestedLines): Order {
            $productIds = array_values(array_unique(array_column($requestedLines, 'product_id')));

            $products = Product::active()
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $orderItemsPayload = [];
            $quantitiesByProduct = [];
            $subtotal = 0.0;

            foreach ($requestedLines as $line) {
                $product = $products->get($line['product_id']);
                if (! $product) {
                    continue;
                }

                $quantity = max(1, (int) $line['quantity']);
                $quantitiesByProduct[$product->id] = ($quantitiesByProduct[$product->id] ?? 0) + $quantity;

                $unitPrice = $product->current_price;
                $lineTotal = round($unitPrice * $quantity, 2);
                $subtotal = round($subtotal + $lineTotal, 2);

                $orderItemsPayload[] = [
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'dial_variant' => $line['variant'],
                    'line_total' => $lineTotal,
                ];
            }

            if ($orderItemsPayload === []) {
                throw ValidationException::withMessages([
                    'product_id' => 'Please choose an available watch before placing your order.',
                ]);
            }

            foreach ($quantitiesByProduct as $productId => $totalNeeded) {
                /** @var Product $product */
                $product = $products->get($productId);
                if ($product->stock < $totalNeeded) {
                    throw ValidationException::withMessages([
                        'quantity' => "Sorry, {$product->title} currently has {$product->stock} left in stock.",
                    ]);
                }
            }

            foreach ($quantitiesByProduct as $productId => $totalNeeded) {
                $products->get($productId)->decrement('stock', $totalNeeded);
            }

            $shippingMethod = ($validated['shipping_method'] ?? 'armored') === 'concierge' ? 'concierge' : 'armored';
            $shippingCost = $shippingMethod === 'concierge' ? 150.00 : 0.00;

            $rawPromoCode = trim((string) ($validated['promo_code'] ?? ''));
            $coupon = $rawPromoCode !== '' ? Coupon::findActiveByCode($rawPromoCode) : null;
            $discountAmount = $coupon ? $coupon->discountFor($subtotal) : 0.00;
            $appliedCouponCode = ($coupon && $discountAmount > 0) ? $coupon->code : null;

            $totalAmount = round(max(0, $subtotal - $discountAmount + $shippingCost), 2);

            $customerName = trim($validated['first_name'].' '.$validated['last_name']);
            $apartment = trim((string) ($validated['apartment'] ?? ''));
            $deliveryAddress = trim($validated['address']).($apartment !== '' ? ', '.$apartment : '');

            $order = Order::create([
                'order_number' => $this->generateUniqueOrderNumber(),
                'user_id' => $userId,
                'customer_name' => $customerName,
                'customer_email' => $validated['email'],
                'customer_phone' => $validated['phone'],
                'delivery_city' => $validated['city'],
                'delivery_address' => $deliveryAddress,
                'delivery_state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'],
                'country' => ! empty($validated['country']) ? $validated['country'] : 'Pakistan',
                'delivery_notes' => $validated['delivery_notes'] ?? null,
                'payment_method' => PaymentMethod::fromCheckoutValue($validated['payment_method'] ?? 'cod'),
                'shipping_method' => $shippingMethod,
                'coupon_code' => $appliedCouponCode,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'order_status' => OrderStatus::Pending,
                'engraving' => $validated['engraving'] ?? null,
                'wrist_size' => $validated['wrist_size'] ?? null,
            ]);

            $order->items()->createMany($orderItemsPayload);

            return $order->load(['items.product.brand', 'items.product.categories', 'items.product.images']);
        });
    }

    /**
     * Extract normalized cart line requests from JSON cart, session cart, or direct Buy-Now inputs.
     *
     * @param  array<string, mixed>  $validated
     * @param  array<int, array<string, mixed>>  $sessionCart
     * @return list<array{product_id: int, quantity: int, variant: string}>
     *
     * @throws ValidationException
     */
    private function extractRequestedLines(array $validated, array $sessionCart): array
    {
        $lines = [];

        if (! empty($validated['cart_items_json'])) {
            $decoded = json_decode((string) $validated['cart_items_json'], true);
            if (is_array($decoded)) {
                foreach ($decoded as $item) {
                    if (! is_array($item)) {
                        continue;
                    }
                    $id = $item['id'] ?? ($item['watch']['id'] ?? null);
                    if ($id !== null && is_numeric($id)) {
                        $lines[] = [
                            'product_id' => (int) $id,
                            'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
                            'variant' => (string) ($item['variant'] ?? 'Signature Dial'),
                        ];
                    }
                }
            }
        }

        if ($lines === [] && ! empty($validated['product_id'])) {
            $lines[] = [
                'product_id' => (int) $validated['product_id'],
                'quantity' => max(1, (int) ($validated['quantity'] ?? 1)),
                'variant' => (string) ($validated['variant'] ?? 'Signature Dial'),
            ];
        }

        if ($lines === [] && $sessionCart !== []) {
            foreach ($sessionCart as $item) {
                if (! is_array($item)) {
                    continue;
                }
                $id = $item['id'] ?? ($item['watch']['id'] ?? null);
                if ($id !== null && is_numeric($id)) {
                    $lines[] = [
                        'product_id' => (int) $id,
                        'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
                        'variant' => (string) ($item['variant'] ?? 'Signature Dial'),
                    ];
                }
            }
        }

        if ($lines === []) {
            throw ValidationException::withMessages([
                'product_id' => 'Your cart is empty. Please add a watch before placing your order.',
            ]);
        }

        return $lines;
    }

    /**
     * Generate a unique readable order number (for example TY-ORD-2026-A84F29C).
     */
    private function generateUniqueOrderNumber(): string
    {
        do {
            $orderNumber = 'TY-ORD-'.date('Y').'-'.strtoupper(Str::random(7));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
