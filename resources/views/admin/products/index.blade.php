<x-admin-layout title="Watches Catalog">
    <div class="space-y-8">
        <!-- Header Banner & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-[0.25em] text-[#cb8665] font-semibold block mb-1">
                    Atelier Inventory
                </span>
                <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl font-normal text-white tracking-tight">
                    Watch Catalog Management
                </h1>
                <p class="text-xs sm:text-sm text-[#8e969d] mt-1">
                    Add new watches, update stock counts, edit prices, and manage active store visibility.
                </p>
            </div>

            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all shadow-[0_6px_22px_rgba(203,134,101,0.3)] shrink-0 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Add New Watch</span>
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('admin.products.index') }}"
            class="veltra-dash-card p-4 sm:p-5 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-lg grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 items-center">

            <!-- Search input -->
            <div class="lg:col-span-2">
                <input type="text" name="q" value="{{ $search }}"
                    placeholder="Search by title, SKU, movement..."
                    class="veltra-auth-input w-full px-4 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#555e67] outline-none">
            </div>

            <!-- Brand Filter -->
            <div>
                <select name="brand_id"
                    class="veltra-auth-input w-full px-3 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white outline-none">
                    <option value="">All Brands</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected((string) $brandId === (string) $brand->id)>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category_id"
                    class="veltra-auth-input w-full px-3 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white outline-none">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected((string) $categoryId === (string) $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter & Submit Buttons -->
            <div class="flex items-center gap-2">
                <select name="status"
                    class="veltra-auth-input flex-1 px-3 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white outline-none">
                    <option value="">All Statuses</option>
                    <option value="active" @selected($status === 'active')>Active Only</option>
                    <option value="inactive" @selected($status === 'inactive')>Hidden / Inactive</option>
                    <option value="featured" @selected($status === 'featured')>Featured Only</option>
                    <option value="out_of_stock" @selected($status === 'out_of_stock')>Out of Stock</option>
                </select>

                <button type="submit"
                    class="px-4 py-2.5 rounded-xl bg-[#141b22] border border-[#cb8665]/40 text-[#f5be9f] hover:bg-[#cb8665] hover:text-[#0e1215] text-xs font-semibold transition-colors shrink-0">
                    Filter
                </button>

                @if ($search || $brandId || $categoryId || $status)
                    <a href="{{ route('admin.products.index') }}"
                        class="p-2.5 rounded-xl bg-white/5 text-[#8e969d] hover:text-white transition-colors shrink-0"
                        title="Clear Filters">
                        ✕
                    </a>
                @endif
            </div>
        </form>

        <!-- Products Table Card -->
        <div class="veltra-dash-card rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-white/10 bg-[#080b0e]/70 text-[10.5px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d]">
                            <th class="py-4 px-6">Watch / Title</th>
                            <th class="py-4 px-4">SKU & Brand</th>
                            <th class="py-4 px-4">Price (USD)</th>
                            <th class="py-4 px-4">Stock</th>
                            <th class="py-4 px-4">Visibility</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-[#d1d6da]">
                        @forelse ($products as $product)
                            @php
                                $primaryImg = $product->images->where('is_primary', true)->first()
                                    ?? $product->images->first();
                                $thumbUrl = $primaryImg ? $primaryImg->url : asset('watches/astra-black.png');
                            @endphp
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <!-- Watch Image & Title -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 h-14 rounded-xl bg-[#080b0e] border border-white/10 p-1.5 shrink-0 flex items-center justify-center overflow-hidden">
                                            <img src="{{ $thumbUrl }}" alt="{{ $product->title }}" class="max-h-full max-w-full object-contain">
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                class="font-medium text-white hover:text-[#cb8665] transition-colors line-clamp-1 text-[13px]">
                                                {{ $product->title }}
                                            </a>
                                            <div class="flex items-center gap-1.5 mt-1 text-[11px] text-[#8e969d]">
                                                <span>{{ $product->categories->pluck('name')->join(', ') ?: 'No category' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- SKU & Brand -->
                                <td class="py-4 px-4">
                                    <span class="font-mono text-[11px] text-[#e4ab91] font-semibold block">
                                        {{ $product->sku }}
                                    </span>
                                    <span class="text-xs text-white block mt-0.5">
                                        {{ $product->brand->name }}
                                    </span>
                                </td>

                                <!-- Price -->
                                <td class="py-4 px-4">
                                    <span class="font-['Outfit',sans-serif] text-sm font-bold text-white block">
                                        ${{ number_format($product->current_price, 2) }}
                                    </span>
                                    @if ($product->sale_price !== null && $product->sale_price < $product->price)
                                        <span class="text-[10px] text-[#8e969d] line-through block">
                                            ${{ number_format((float) $product->price, 2) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Stock Status -->
                                <td class="py-4 px-4">
                                    @if ($product->stock <= 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-red-500/15 text-red-400 border border-red-500/30">
                                            Sold Out (0)
                                        </span>
                                    @elseif ($product->stock <= 5)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-amber-500/15 text-amber-300 border border-amber-500/30">
                                            Low: {{ $product->stock }} left
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-[#5ea391]/15 text-[#5ea391] border border-[#5ea391]/30">
                                            {{ $product->stock }} units
                                        </span>
                                    @endif
                                </td>

                                <!-- Status Badges -->
                                <td class="py-4 px-4 space-y-1">
                                    <div>
                                        @if ($product->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-semibold bg-[#5ea391]/15 text-[#5ea391] border border-[#5ea391]/30">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-semibold bg-gray-500/15 text-gray-400 border border-gray-500/30">
                                                Hidden
                                            </span>
                                        @endif
                                    </div>
                                    @if ($product->is_featured)
                                        <div>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9.5px] uppercase tracking-wider font-bold bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/35">
                                                ★ Featured
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- View in Store -->
                                        <a href="{{ route('product.show', $product->slug ?: $product->id) }}" target="_blank"
                                            class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-[#8e969d] hover:text-white transition-colors"
                                            title="View in Customer Store">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="p-2 rounded-lg bg-[#cb8665]/15 hover:bg-[#cb8665] text-[#f5be9f] hover:text-[#0e1215] transition-colors"
                                            title="Edit Watch">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>

                                        <!-- Delete with Confirmation -->
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                            onsubmit="return confirm('Are you sure you want to delete \'{{ addslashes($product->title) }}\'? This will also remove all its gallery photos.');"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white transition-colors cursor-pointer"
                                                title="Delete Watch">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-[#8e969d]">
                                    <div class="max-w-sm mx-auto space-y-2">
                                        <p class="text-base text-white font-medium">No watches match your search criteria.</p>
                                        <p class="text-xs">Try clearing your filters or click "+ Add New Watch" to create one.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if ($products->hasPages())
                <div class="p-4 sm:p-6 border-t border-white/10 bg-[#080b0e]/40">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>