@php
    use App\Enums\OrderStatus;
    use App\Enums\CouponType;
@endphp

<x-app-layout title="My Dashboard">
    <x-slot name="header">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-[#8e969d] mb-3">
            <a href="{{ route('home') }}" class="hover:text-[#cb8665] transition-colors">Home</a>
            <span>/</span>
            <span class="text-[#cb8665] font-medium">My Dashboard</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-5 h-[1px] bg-[#cb8665]/60"></span>
                    <span class="text-[10.5px] font-['Montserrat',sans-serif] uppercase tracking-[0.26em] text-[#cb8665] font-semibold">
                        Verified Customer Account
                    </span>
                </div>
                <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl lg:text-[42px] font-normal text-white tracking-tight leading-tight">
                    Welcome back, {{ $user->name }}
                </h1>
                <p class="text-xs sm:text-sm text-[#8e969d] mt-1.5 max-w-2xl">
                    Track your watch orders, download official warranty receipts, and manage your account details in one place.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                @if ($user->is_admin)
                    <a href="{{ route('admin.products.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#d4af37] via-[#f3e5ab] to-[#cb8665] text-[#0b0e11] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all shadow-[0_6px_20px_rgba(212,175,55,0.35)]">
                        <svg class="w-4 h-4 text-[#0b0e11]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span>Atelier Admin CMS</span>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                    class="veltra-auth-secondary-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#141b22] border border-[#cb8665]/35 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#f5be9f] hover:border-[#cb8665] hover:text-white transition-all">
                    <svg class="w-4 h-4 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Edit Profile</span>
                </a>

                <a href="{{ route('shop') }}"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all shadow-[0_6px_22px_rgba(203,134,101,0.3)]">
                    <span>Shop Watches</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-12 py-10 lg:py-14 space-y-10"
        x-data="{
            copiedCode: '',
            copyCoupon(code) {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(code);
                }
                this.copiedCode = code;
                setTimeout(() => {
                    if (this.copiedCode === code) this.copiedCode = '';
                }, 2200);
            }
        }">

        @if ($user->is_admin)
            <!-- Admin Atelier Quick-Launch Bar -->
            <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-r from-[#17130e] via-[#1f1710] to-[#12161a] border border-[#cb8665]/40 shadow-[0_15px_35px_rgba(0,0,0,0.55)] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-[#cb8665]/20 border border-[#cb8665]/50 flex items-center justify-center shrink-0">
                        <span class="text-lg">👑</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold text-[#f5be9f]">Atelier Admin CMS</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#cb8665]/20 text-[#e4ab91] border border-[#cb8665]/30">Full Control</span>
                        </div>
                        <p class="text-xs text-[#8e969d] mt-0.5">Manage watch inventory, categories, specifications, and customize homepage content.</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.products.index') }}" class="px-3.5 py-1.5 rounded-lg bg-[#221c17] hover:bg-[#2e241d] border border-[#cb8665]/30 text-xs font-medium text-white transition-all">
                        Watches Catalog
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="px-3.5 py-1.5 rounded-lg bg-[#221c17] hover:bg-[#2e241d] border border-[#cb8665]/30 text-xs font-medium text-white transition-all">
                        Categories
                    </a>
                    <a href="{{ route('admin.specifications.index') }}" class="px-3.5 py-1.5 rounded-lg bg-[#221c17] hover:bg-[#2e241d] border border-[#cb8665]/30 text-xs font-medium text-white transition-all">
                        Specifications
                    </a>
                    <a href="{{ route('admin.customizer.index') }}" class="px-3.5 py-1.5 rounded-lg bg-gradient-to-r from-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-bold hover:brightness-110 transition-all shadow-[0_4px_12px_rgba(203,134,101,0.25)]">
                        Page Customizer ↗
                    </a>
                </div>
            </div>
        @endif

        <!-- Summary Stat Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            <!-- Total Orders -->
            <div class="veltra-dash-card p-6 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_35px_rgba(0,0,0,0.55)] flex items-start justify-between gap-4">
                <div>
                    <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-[0.2em] text-[#8e969d] font-semibold block">
                        Total Orders
                    </span>
                    <span class="font-['Outfit',sans-serif] text-3xl font-bold text-white mt-2 block">
                        {{ $stats['total_orders'] }}
                    </span>
                    <span class="text-xs text-[#8e969d] mt-1 block">
                        All-time watch orders
                    </span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[#cb8665]/15 border border-[#cb8665]/35 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#e4ab91]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
            </div>

            <!-- Active Deliveries -->
            <div class="veltra-dash-card p-6 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_35px_rgba(0,0,0,0.55)] flex items-start justify-between gap-4">
                <div>
                    <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-[0.2em] text-[#8e969d] font-semibold block">
                        Active Orders
                    </span>
                    <span class="font-['Outfit',sans-serif] text-3xl font-bold text-[#e4ab91] mt-2 block">
                        {{ $stats['active_orders'] }}
                    </span>
                    <span class="text-xs text-[#8e969d] mt-1 block">
                        Being packed or on the way
                    </span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[#cb8665]/15 border border-[#cb8665]/35 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#e4ab91]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13" rx="1"></rect>
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                    </svg>
                </div>
            </div>

            <!-- Delivered -->
            <div class="veltra-dash-card p-6 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_35px_rgba(0,0,0,0.55)] flex items-start justify-between gap-4">
                <div>
                    <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-[0.2em] text-[#8e969d] font-semibold block">
                        Delivered
                    </span>
                    <span class="font-['Outfit',sans-serif] text-3xl font-bold text-[#5ea391] mt-2 block">
                        {{ $stats['delivered_orders'] }}
                    </span>
                    <span class="text-xs text-[#8e969d] mt-1 block">
                        Safely delivered to doorstep
                    </span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[#5ea391]/15 border border-[#5ea391]/35 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#5ea391]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Total Spent -->
            <div class="veltra-dash-card p-6 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_35px_rgba(0,0,0,0.55)] flex items-start justify-between gap-4">
                <div>
                    <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-[0.2em] text-[#8e969d] font-semibold block">
                        Total Spent
                    </span>
                    <span class="font-['Outfit',sans-serif] text-3xl font-bold text-white mt-2 block">
                        ${{ number_format($stats['total_spent'], 2) }}
                    </span>
                    <span class="text-xs text-[#8e969d] mt-1 block">
                        2-Year Official Warranty included
                    </span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-[#cb8665]/15 border border-[#cb8665]/35 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#e4ab91]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"></circle>
                        <polyline points="12 7 12 12 15 15"></polyline>
                    </svg>
                </div>
            </div>
        </section>

        <!-- Main Two-Column Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left Column: Order History + Recommended Watches (8 cols) -->
            <div class="lg:col-span-8 space-y-8">

                <!-- Recent Orders Card -->
                <section class="veltra-dash-card rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-white/10">
                        <div>
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl sm:text-3xl font-normal text-white">
                                My Watch Orders
                            </h2>
                            <p class="text-xs text-[#8e969d] mt-0.5">
                                View delivery status and official warranty receipts for your orders.
                            </p>
                        </div>
                        <span class="text-xs font-mono text-[#f5be9f] bg-[#cb8665]/15 px-3 py-1 rounded-full border border-[#cb8665]/30 self-start sm:self-auto">
                            {{ $orders->count() }} {{ $orders->count() === 1 ? 'Order' : 'Orders' }}
                        </span>
                    </div>

                    @if ($orders->isEmpty())
                        <!-- Empty Orders State -->
                        <div class="py-12 px-4 text-center max-w-md mx-auto">
                            <div class="w-16 h-16 rounded-2xl bg-[#080b0e] border border-[#cb8665]/35 flex items-center justify-center mx-auto mb-4 shadow-[0_0_25px_rgba(203,134,101,0.15)]">
                                <svg class="w-8 h-8 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="7"></circle>
                                    <polyline points="12 9 12 12 13.5 13.5"></polyline>
                                    <path d="M16.51 17.35l-.35 3.83a2 2 0 0 1-2 1.82H9.83a2 2 0 0 1-2-1.82l-.35-3.83m.01-10.7.35-3.83A2 2 0 0 1 9.83 1h4.35a2 2 0 0 1 2 1.82l.35 3.83"></path>
                                </svg>
                            </div>
                            <h3 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal">
                                No Watch Orders Yet
                            </h3>
                            <p class="text-xs sm:text-sm text-[#8e969d] mt-2 leading-relaxed">
                                You haven't placed an order yet. Browse our 100% original watch collection with free courier delivery across Pakistan and Cash on Delivery.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('shop') }}"
                                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all shadow-[0_6px_22px_rgba(203,134,101,0.3)]">
                                    <span>Browse Watch Collection</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Order Rows -->
                        <div class="divide-y divide-white/10 mt-2">
                            @foreach ($orders as $order)
                                @php
                                    $primaryItem = $order->items->first();
                                    $primaryCatalog = $primaryItem?->product?->toCatalogArray();
                                    $thumbUrl = $primaryCatalog['image'] ?? asset('watches/astra-black.png');
                                    $brandName = $primaryCatalog['brand'] ?? 'TIMEYRAH';
                                    $extraItemsCount = max(0, $order->items->count() - 1);
                                    $totalQty = max(1, (int) $order->items->sum('quantity'));

                                    $statusClasses = match ($order->order_status) {
                                        OrderStatus::Delivered => 'bg-[#5ea391]/15 text-[#5ea391] border-[#5ea391]/35',
                                        OrderStatus::Processing => 'bg-sky-500/15 text-sky-400 border-sky-500/35',
                                        OrderStatus::Cancelled => 'bg-red-500/15 text-red-400 border-red-500/35',
                                        default => 'bg-[#cb8665]/15 text-[#f5be9f] border-[#cb8665]/35',
                                    };
                                @endphp

                                <div class="py-5 first:pt-4 last:pb-1 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                                    <div class="flex items-center gap-4 min-w-0">
                                        <div class="w-16 h-16 rounded-xl bg-[#080b0e] border border-white/10 p-2 shrink-0 flex items-center justify-center">
                                            <img src="{{ $thumbUrl }}" alt="{{ $primaryItem?->product_title ?? 'Watch' }}"
                                                class="w-full h-full object-contain">
                                        </div>

                                        <div class="min-w-0 space-y-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="text-[11px] font-mono font-bold text-[#e4ab91]">
                                                    {{ $order->order_number }}
                                                </span>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold border {{ $statusClasses }}">
                                                    {{ $order->order_status->value }}
                                                </span>
                                            </div>

                                            <h3 class="text-sm sm:text-base font-medium text-white truncate">
                                                {{ $primaryItem?->product_title ?? 'Original Watch' }}
                                                @if ($extraItemsCount > 0)
                                                    <span class="text-xs text-[#cb8665] font-normal">(+{{ $extraItemsCount }} more)</span>
                                                @endif
                                            </h3>

                                            <p class="text-xs text-[#8e969d]">
                                                {{ $brandName }} • Qty: {{ $totalQty }} • {{ $order->delivery_city }} • {{ $order->payment_method->label() }}
                                            </p>
                                            <p class="text-[11px] text-[#6b737b]">
                                                Placed on {{ $order->created_at?->format('M j, Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-white/5">
                                        <span class="font-['Outfit',sans-serif] text-lg font-bold text-white">
                                            ${{ number_format((float) $order->total_amount, 2) }}
                                        </span>
                                        <a href="{{ route('order.confirmation', ['orderNumber' => $order->order_number]) }}"
                                            class="inline-flex items-center gap-1.5 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#cb8665] hover:text-[#e4ab91] transition-colors">
                                            <span>View Receipt</span>
                                            <span>→</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <!-- Recommended Original Watches -->
                @if (!empty($featuredWatches))
                    <section class="veltra-dash-card rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] p-6 sm:p-8">
                        <div class="flex items-center justify-between gap-4 pb-5 border-b border-white/10">
                            <div>
                                <h2 class="font-['Cormorant_Garamond',serif] text-2xl font-normal text-white">
                                    Featured Original Watches
                                </h2>
                                <p class="text-xs text-[#8e969d] mt-0.5">
                                    Handpicked best-sellers ready for free delivery across Pakistan.
                                </p>
                            </div>
                            <a href="{{ route('shop') }}" class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#cb8665] hover:text-[#e4ab91] transition-colors shrink-0">
                                View All →
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-6">
                            @foreach ($featuredWatches as $watch)
                                <a href="{{ route('product.show', $watch['id']) }}"
                                    class="veltra-dash-subcard group rounded-2xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/50 p-4 transition-all flex flex-col">
                                    <div class="h-36 rounded-xl bg-[#0e1419] border border-white/5 p-3 flex items-center justify-center overflow-hidden mb-3.5">
                                        <img src="{{ $watch['image'] }}" alt="{{ $watch['title'] }}"
                                            class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <span class="text-[10px] font-['Montserrat',sans-serif] uppercase tracking-[0.2em] text-[#cb8665] font-semibold">
                                        {{ $watch['brand'] }}
                                    </span>
                                    <h3 class="text-sm font-medium text-white line-clamp-2 mt-1 group-hover:text-[#e4ab91] transition-colors">
                                        {{ $watch['title'] }}
                                    </h3>
                                    <div class="mt-auto pt-3 flex items-baseline justify-between gap-2">
                                        <span class="font-['Outfit',sans-serif] text-base font-bold text-white">
                                            ${{ number_format((float) $watch['price'], 2) }}
                                        </span>
                                        @if (($watch['original_price'] ?? 0) > $watch['price'])
                                            <span class="text-xs text-[#6b737b] line-through">
                                                ${{ number_format((float) $watch['original_price'], 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

            </div>

            <!-- Right Column: Account Card, Promo Coupons & Store Promise (4 cols) -->
            <aside class="lg:col-span-4 space-y-6">

                <!-- Account Profile Card -->
                <div class="veltra-dash-card rounded-3xl bg-[#0e1419]/90 border border-[#cb8665]/30 shadow-[0_20px_50px_rgba(0,0,0,0.65)] p-6 sm:p-7 space-y-5">
                    <div class="flex items-center gap-4 pb-4 border-b border-white/10">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#cb8665] to-[#8a4d34] flex items-center justify-center text-xl font-['Cormorant_Garamond',serif] font-bold text-white border border-[#f5be9f]/40 shrink-0 shadow-[0_6px_18px_rgba(203,134,101,0.3)]">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base font-semibold text-white truncate">
                                {{ $user->name }}
                            </h3>
                            <p class="text-xs text-[#8e969d] truncate">
                                {{ $user->email }}
                            </p>
                            <span class="inline-flex items-center gap-1 mt-1.5 px-2 py-0.5 rounded text-[10px] font-mono uppercase tracking-wider bg-[#5ea391]/15 text-[#5ea391] border border-[#5ea391]/30">
                                <span>✓</span>
                                <span>Member since {{ $user->created_at?->format('M Y') ?? '2026' }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="space-y-2 text-xs">
                        <a href="{{ route('profile.edit') }}"
                            class="veltra-dash-subcard flex items-center justify-between px-4 py-3 rounded-xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/50 text-[#d1d6da] hover:text-white transition-all">
                            <span class="flex items-center gap-2.5 font-medium">
                                <svg class="w-4 h-4 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M12 20h9"></path>
                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                </svg>
                                <span>Update Name, Email & Password</span>
                            </span>
                            <span class="text-[#cb8665]">→</span>
                        </a>

                        <button type="button" @click="$store.cart && $store.cart.open()"
                            class="veltra-dash-subcard w-full flex items-center justify-between px-4 py-3 rounded-xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/50 text-[#d1d6da] hover:text-white transition-all cursor-pointer">
                            <span class="flex items-center gap-2.5 font-medium">
                                <svg class="w-4 h-4 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                <span>Open Shopping Cart</span>
                            </span>
                            <span class="text-[#cb8665]">→</span>
                        </button>

                        <a href="{{ route('checkout') }}"
                            class="veltra-dash-subcard flex items-center justify-between px-4 py-3 rounded-xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/50 text-[#d1d6da] hover:text-white transition-all">
                            <span class="flex items-center gap-2.5 font-medium">
                                <svg class="w-4 h-4 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span>Proceed to Checkout</span>
                            </span>
                            <span class="text-[#cb8665]">→</span>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t border-white/10">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-500/10 border border-red-500/25 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>

                <!-- Active Promo Coupons Card -->
                @if ($activeCoupons->isNotEmpty())
                    <div class="veltra-dash-card rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] p-6 sm:p-7 space-y-4">
                        <div>
                            <span class="text-[10px] font-['Montserrat',sans-serif] uppercase tracking-[0.22em] text-[#cb8665] font-semibold block">
                                Member Privileges
                            </span>
                            <h3 class="font-['Cormorant_Garamond',serif] text-2xl font-normal text-white mt-0.5">
                                Active Discount Codes
                            </h3>
                            <p class="text-xs text-[#8e969d] mt-0.5">
                                Copy any code below and enter it during checkout.
                            </p>
                        </div>

                        <div class="space-y-3">
                            @foreach ($activeCoupons as $coupon)
                                <div class="veltra-dash-subcard p-3.5 rounded-xl bg-[#080b0e] border border-dashed border-[#cb8665]/45 flex items-center justify-between gap-3">
                                    <div>
                                        <span class="font-mono text-sm font-bold text-[#e4ab91] tracking-wider block">
                                            {{ $coupon->code }}
                                        </span>
                                        <span class="text-[11px] text-[#8e969d]">
                                            @if ($coupon->type === CouponType::Percent)
                                                Save {{ rtrim(rtrim(number_format((float) $coupon->value, 2), '0'), '.') }}% on your order
                                            @else
                                                Save ${{ number_format((float) $coupon->value, 0) }} on your order
                                            @endif
                                        </span>
                                    </div>

                                    <button type="button" @click="copyCoupon('{{ $coupon->code }}')"
                                        class="px-3 py-1.5 rounded-lg bg-[#cb8665]/15 border border-[#cb8665]/35 text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#f5be9f] hover:bg-[#cb8665] hover:text-[#0e1215] transition-colors cursor-pointer shrink-0">
                                        <span x-text="copiedCode === '{{ $coupon->code }}' ? 'Copied!' : 'Copy'">Copy</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- TIMEYRAH Pakistan Customer Promise -->
                <div class="veltra-dash-card rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] p-6 sm:p-7 space-y-4">
                    <h3 class="font-['Cormorant_Garamond',serif] text-2xl font-normal text-white">
                        TIMEYRAH Pakistan Promise
                    </h3>

                    <ul class="space-y-3 text-xs text-[#8e969d]">
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-[#5ea391]/15 border border-[#5ea391]/40 text-[#5ea391] flex items-center justify-center text-[11px] font-bold shrink-0 mt-0.5">✓</span>
                            <span><strong class="text-white">Cash on Delivery Across Pakistan</strong> — Pay comfortably at your doorstep in Karachi, Lahore, Islamabad, and all cities.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-[#5ea391]/15 border border-[#5ea391]/40 text-[#5ea391] flex items-center justify-center text-[11px] font-bold shrink-0 mt-0.5">✓</span>
                            <span><strong class="text-white">100% Original Watches</strong> — Every watch arrives in its official brand box with serial number and 2-Year Warranty Card.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-[#5ea391]/15 border border-[#5ea391]/40 text-[#5ea391] flex items-center justify-center text-[11px] font-bold shrink-0 mt-0.5">✓</span>
                            <span><strong class="text-white">Free Standard Courier Shipping</strong> — Safe, insured bubble-sealed delivery with zero extra shipping fee.</span>
                        </li>
                    </ul>
                </div>

            </aside>

        </div>
    </div>
</x-app-layout>
