<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the authenticated customer's dashboard with order history, stats, and featured watches.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $orders = $user->orders()
            ->with(['items.product.brand', 'items.product.categories', 'items.product.images'])
            ->latest()
            ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'active_orders' => $orders->whereIn('order_status', [OrderStatus::Pending, OrderStatus::Processing])->count(),
            'delivered_orders' => $orders->where('order_status', OrderStatus::Delivered)->count(),
            'total_spent' => (float) $orders
                ->reject(fn (Order $order): bool => $order->order_status === OrderStatus::Cancelled)
                ->sum('total_amount'),
        ];

        $activeCoupons = Coupon::query()
            ->active()
            ->orderBy('id')
            ->get();

        $featuredWatches = Product::active()
            ->with(['brand', 'categories', 'images'])
            ->orderByDesc('is_featured')
            ->orderBy('id')
            ->limit(3)
            ->get()
            ->map(fn (Product $product): array => $product->toCatalogArray())
            ->all();

        return view('dashboard', [
            'user' => $user,
            'orders' => $orders,
            'stats' => $stats,
            'activeCoupons' => $activeCoupons,
            'featuredWatches' => $featuredWatches,
        ]);
    }
}
