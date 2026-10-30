<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if ($selectedBrand)
            {{ $selectedBrand }} Timepieces — TIMEYRAH Horology
        @elseif ($selectedCategory)
            {{ $selectedCategory }} Watches — TIMEYRAH Catalog
        @else
            Watch Collections & Certified Timepieces — TIMEYRAH
        @endif
    </title>

    <!-- Google Fonts: Cormorant Garamond, Montserrat, Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('shopCatalog', () => ({
                allWatches: @json($watches),
                quickViewWatch: null,
                toastMessage: '',
                showToast: false,
                mobileFiltersOpen: false,
                openQuickView(id) {
                    this.quickViewWatch = this.allWatches.find(w => Number(w.id) === Number(id)) ||
                        this.allWatches.find(w => String(w.id) === String(id)) ||
                        null;
                },
                addToCart(id) {
                    const watch = this.allWatches.find(w => Number(w.id) === Number(id)) ||
                        this.allWatches.find(w => String(w.id) === String(id));
                    if (!watch) return;
                    Alpine.store('cart').addItem(watch, 1, 'Signature Dial');
                    this.toastMessage = 'Added ' + (watch ? watch.title : 'Timepiece') +
                        ' to your Horology Bag';
                    this.showToast = true;
                    setTimeout(() => this.showToast = false, 4000);
                }
            }));
        });
    </script>
</head>

<body class="bg-[#0b0f12] text-[#d1d6da] min-h-screen selection:bg-[#cb8665]/30 selection:text-white"
    x-data="shopCatalog">

    <!-- Top Navigation Component with Mega Menu -->
    <x-navbar />

    <!-- Toast Notification for Add to Cart -->
    <div x-show="showToast" x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-4 opacity-0 scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
        x-transition:leave-end="translate-y-4 opacity-0 scale-95"
        class="fixed bottom-6 right-6 z-[100] max-w-md p-4 rounded-xl bg-[#141b22]/95 border border-[#cb8665]/50 shadow-[0_15px_40px_rgba(0,0,0,0.9),0_0_25px_rgba(203,152,126,0.2)] backdrop-blur-xl flex items-center gap-3.5"
        style="display: none;">
        <div
            class="w-9 h-9 rounded-full bg-[#cb8665]/20 border border-[#cb8665]/40 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 stroke-[#e4ab91]" viewBox="0 0 24 24" fill="none" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#e4ab91] font-semibold">
                Horology Bag Updated</h4>
            <p class="text-[13px] text-[#e8e4df] font-['Inter',sans-serif] line-clamp-1" x-text="toastMessage"></p>
        </div>
        <button type="button" @click="$store.cart.open(); showToast = false"
            class="px-3 py-1.5 rounded-lg bg-[#cb8665] text-[#0e1215] text-xs font-['Outfit',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 shrink-0 cursor-pointer">
            View Bag →
        </button>
        <button @click="showToast = false" class="text-[#8e969d] hover:text-white p-1">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Shop Page Main Header / Breadcrumb Banner -->
    <header
        class="relative border-b border-white/5 bg-gradient-to-b from-[#141a21]/50 to-transparent pt-8 pb-10 px-4 sm:px-6 lg:px-12">
        <div class="max-w-[1600px] mx-auto">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-xs font-['Inter',sans-serif] text-[#8e969d] mb-4">
                <a href="{{ route('home') }}" class="hover:text-[#cb8665] transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('shop') }}"
                    class="hover:text-[#cb8665] transition-colors {{ !$selectedBrand && !$selectedCategory ? 'text-[#dd9c7c]' : '' }}">Shop
                    Catalog</a>
                @if ($selectedCategory)
                    <span>/</span>
                    <span class="text-[#e8e4df]">{{ $selectedCategory }}</span>
                @endif
                @if ($selectedBrand)
                    <span>/</span>
                    <span class="text-[#dd9c7c] font-medium">{{ $selectedBrand }}</span>
                @endif
            </nav>

            <!-- Dynamic Header Title -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#cb8665]/15 border border-[#cb8665]/35 text-[11px] font-['Montserrat',sans-serif] font-semibold tracking-[0.25em] text-[#e4ab91] uppercase mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#cb8665] animate-ping"></span>
                        TIMEYRAH ATELIER DIRECTORY
                    </span>
                    <h1
                        class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl lg:text-5xl font-light text-[#f0ece7] tracking-tight">
                        @if ($selectedBrand)
                            {{ $selectedBrand }} <span class="italic font-normal text-[#dd9c7c]">Timepieces</span>
                        @elseif ($selectedCategory)
                            {{ $selectedCategory }} <span class="italic font-normal text-[#dd9c7c]">Collection</span>
                        @else
                            The Horology <span class="italic font-normal text-[#dd9c7c]">Collection</span>
                        @endif
                    </h1>
                    <p class="text-sm font-['Inter',sans-serif] text-[#8e969d] mt-2 max-w-2xl font-light">
                        Certified authentic wristwatches curated by mechanical caliber, precious metal bracelets, and
                        master Japanese & Swiss watchmakers.
                    </p>
                </div>

                <!-- Active Filter Badges & Clear Button -->
                @if ($selectedBrand || $selectedCategory || $search || $minPrice || $maxPrice || $movement)
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($selectedBrand)
                            <a href="{{ route('shop', array_merge(request()->except('brand'))) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#1e252d] border border-[#cb8665]/40 text-xs text-[#f5be9f] hover:bg-[#252e38] transition-colors">
                                <span>Brand: {{ $selectedBrand }}</span>
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </a>
                        @endif
                        @if ($selectedCategory)
                            <a href="{{ route('shop', array_merge(request()->except('category'))) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#1e252d] border border-[#cb8665]/40 text-xs text-[#f5be9f] hover:bg-[#252e38] transition-colors">
                                <span>Type: {{ $selectedCategory }}</span>
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </a>
                        @endif
                        @if ($search)
                            <a href="{{ route('shop', array_merge(request()->except('search'))) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#1e252d] border border-white/20 text-xs text-white hover:bg-[#252e38] transition-colors">
                                <span>Search: "{{ $search }}"</span>
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('shop') }}"
                            class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#cb8665] hover:text-[#f5be9f] underline underline-offset-4 ml-1">
                            Reset Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content: Sidebar Filters + Products Grid -->
    <main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-12 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 xl:gap-12">

            <!-- Mobile Filter Toggle Button -->
            <div class="lg:hidden flex items-center justify-between pb-4 border-b border-white/5">
                <button type="button" @click="mobileFiltersOpen = !mobileFiltersOpen"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#141b22] border border-white/10 text-xs font-['Montserrat',sans-serif] tracking-wider uppercase text-[#e8e4df]">
                    <svg class="w-4 h-4 stroke-[#cb8665]" viewBox="0 0 24 24" fill="none" stroke-width="2">
                        <line x1="4" y1="21" x2="4" y2="14"></line>
                        <line x1="4" y1="10" x2="4" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12" y2="3"></line>
                        <line x1="20" y1="21" x2="20" y2="16"></line>
                        <line x1="20" y1="12" x2="20" y2="3"></line>
                        <line x1="1" y1="14" x2="7" y2="14"></line>
                        <line x1="9" y1="8" x2="15" y2="8"></line>
                        <line x1="17" y1="16" x2="23" y2="16"></line>
                    </svg>
                    <span>Filter & Refine</span>
                </button>
                <span class="text-xs text-[#8e969d]">{{ $totalCount }} watches available</span>
            </div>

            <!-- Left Sidebar Filters -->
            <aside :class="mobileFiltersOpen ? 'block' : 'hidden lg:block'" class="space-y-8 pr-2">

                <!-- Live Search Box -->
                <div class="rounded-xl p-4 bg-[#11171d]/90 border border-white/10">
                    <h3
                        class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.2em] font-semibold text-[#dd9c7c] mb-3">
                        Model / Keyword
                    </h3>
                    <form action="{{ route('shop') }}" method="GET" class="relative">
                        @if ($selectedBrand)
                            <input type="hidden" name="brand" value="{{ $selectedBrand }}">
                        @endif
                        @if ($selectedCategory)
                            <input type="hidden" name="category" value="{{ $selectedCategory }}">
                        @endif
                        <input type="text" name="search" value="{{ $search }}"
                            placeholder="e.g. Submariner, Chrono, Diver..."
                            class="w-full pl-3.5 pr-8 py-2 text-xs bg-[#090d10] border border-white/10 rounded-lg text-white placeholder-[#6b737b] focus:outline-none focus:border-[#cb8665]/60 focus:ring-1 focus:ring-[#cb8665]/40 transition-all font-['Inter',sans-serif]" />
                        <button type="submit" class="absolute right-2.5 top-2.5 text-[#8e969d] hover:text-white">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Category Filters (STRAP, METAL CHAIN, etc.) -->
                <div class="rounded-xl p-5 bg-[#11171d]/90 border border-white/10">
                    <h3
                        class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.2em] font-semibold text-[#dd9c7c] mb-3 pb-2 border-b border-white/5">
                        Category Architecture
                    </h3>
                    <ul class="space-y-1.5 text-xs font-['Inter',sans-serif]">
                        <li>
                            <a href="{{ route('shop', request()->except('category')) }}"
                                class="flex items-center justify-between py-1.5 px-2.5 rounded-lg transition-colors {{ !$selectedCategory ? 'bg-[#cb8665]/20 text-[#f5be9f] font-medium' : 'text-[#8e969d] hover:text-white hover:bg-white/5' }}">
                                <span>All Watch Types</span>
                                <span class="text-[10px] opacity-70">★</span>
                            </a>
                        </li>
                        @foreach ($availableCategories as $key => $label)
                            <li>
                                <a href="{{ route('shop', array_merge(request()->query(), ['category' => $key])) }}"
                                    class="flex items-center justify-between py-1.5 px-2.5 rounded-lg transition-colors {{ strtoupper($selectedCategory) === $key ? 'bg-[#cb8665]/20 text-[#f5be9f] font-medium' : 'text-[#8e969d] hover:text-white hover:bg-white/5' }}">
                                    <span>{{ $label }}</span>
                                    <span
                                        class="text-[10px] font-['Montserrat',sans-serif] text-[#cb8665] opacity-60">{{ $key }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Brand Filter List -->
                <div class="rounded-xl p-5 bg-[#11171d]/90 border border-white/10 max-h-[420px] overflow-y-auto scrollbar-thin"
                    x-data="{ brandFilterQuery: '' }">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b border-white/5">
                        <h3
                            class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.2em] font-semibold text-[#dd9c7c]">
                            Maison Brands
                        </h3>
                        <span class="text-[10px] text-[#6b737b] uppercase font-['Montserrat',sans-serif]">A–Z</span>
                    </div>

                    <input type="text" x-model="brandFilterQuery" placeholder="Filter brand list..."
                        class="w-full px-3 py-1.5 mb-3 text-xs bg-[#090d10] border border-white/10 rounded-lg text-white placeholder-[#6b737b] focus:outline-none focus:border-[#cb8665]/60" />

                    <ul class="space-y-1 text-xs font-['Inter',sans-serif]">
                        @foreach ($availableBrands as $b)
                            <li
                                x-show="!brandFilterQuery || '{{ strtolower($b) }}'.includes(brandFilterQuery.toLowerCase())">
                                <a href="{{ route('shop', array_merge(request()->query(), ['brand' => $b])) }}"
                                    class="flex items-center justify-between py-1 px-2 rounded hover:bg-white/5 transition-colors {{ strcasecmp($selectedBrand, $b) === 0 ? 'text-[#f5be9f] font-semibold bg-[#cb8665]/15' : 'text-[#8e969d] hover:text-white' }}">
                                    <span>{{ $b }}</span>
                                    @if (strcasecmp($selectedBrand, $b) === 0)
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#cb8665]"></span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Price Range Filter -->
                <div class="rounded-xl p-5 bg-[#11171d]/90 border border-white/10">
                    <h3
                        class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.2em] font-semibold text-[#dd9c7c] mb-3 pb-2 border-b border-white/5">
                        Price Tiers
                    </h3>
                    <ul class="space-y-1.5 text-xs font-['Inter',sans-serif]">
                        <li>
                            <a href="{{ route('shop', array_merge(request()->except(['min_price', 'max_price']))) }}"
                                class="block py-1 px-2 rounded hover:bg-white/5 {{ !$minPrice && !$maxPrice ? 'text-[#f5be9f] font-medium' : 'text-[#8e969d]' }}">
                                Any Price Range
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('shop', array_merge(request()->query(), ['min_price' => 0, 'max_price' => 150])) }}"
                                class="block py-1 px-2 rounded hover:bg-white/5 {{ $maxPrice == 150 ? 'text-[#f5be9f] font-medium bg-[#cb8665]/15' : 'text-[#8e969d]' }}">
                                Under $150 (Everyday Essentials)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('shop', array_merge(request()->query(), ['min_price' => 150, 'max_price' => 500])) }}"
                                class="block py-1 px-2 rounded hover:bg-white/5 {{ $minPrice == 150 && $maxPrice == 500 ? 'text-[#f5be9f] font-medium bg-[#cb8665]/15' : 'text-[#8e969d]' }}">
                                $150 – $500 (Heritage & Chronos)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('shop', array_merge(request()->query(), ['min_price' => 500, 'max_price' => 2000])) }}"
                                class="block py-1 px-2 rounded hover:bg-white/5 {{ $minPrice == 500 && $maxPrice == 2000 ? 'text-[#f5be9f] font-medium bg-[#cb8665]/15' : 'text-[#8e969d]' }}">
                                $500 – $2,000 (Premium Mechanicals)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('shop', array_merge(request()->query(), ['min_price' => 2000])) }}"
                                class="block py-1 px-2 rounded hover:bg-white/5 {{ $minPrice == 2000 ? 'text-[#f5be9f] font-medium bg-[#cb8665]/15' : 'text-[#8e969d]' }}">
                                $2,000+ (Haute Horlogerie)
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Horological Certification Sidebar Card -->
                <div
                    class="rounded-xl p-5 bg-gradient-to-b from-[#1a130f] to-[#0d1216] border border-[#cb8665]/30 shadow-lg">
                    <div class="flex items-center gap-2 mb-2 text-[#dd9c7c]">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                        <h4 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold">TIMEYRAH
                            Pledge</h4>
                    </div>
                    <p class="text-[12px] font-['Inter',sans-serif] text-[#8e969d] leading-relaxed">
                        Every timepiece arrives with factory warranty documentation, verified serial number engraving,
                        and tamper-evident authentication seals.
                    </p>
                </div>

            </aside>

            <!-- Right Main Area: Products Grid & Toolbar -->
            <div>

                <!-- Products Toolbar -->
                <div
                    class="flex flex-wrap items-center justify-between gap-4 p-4 mb-6 rounded-xl bg-[#11171d]/90 border border-white/10">
                    <div class="text-xs text-[#8e969d] font-['Inter',sans-serif]">
                        Showing <span class="font-semibold text-white">{{ $totalCount }}</span> certified timepieces
                    </div>

                    <!-- Sort Controls -->
                    <div class="flex items-center gap-3">
                        <label for="sortSelector"
                            class="text-xs text-[#8e969d] font-['Inter',sans-serif] hidden sm:inline">Sort By:</label>
                        <select id="sortSelector" onchange="window.location.href = this.value"
                            class="px-3 py-1.5 text-xs bg-[#090d10] border border-white/10 rounded-lg text-white focus:outline-none focus:border-[#cb8665]/60 font-['Inter',sans-serif]">
                            <option
                                value="{{ route('shop', array_merge(request()->query(), ['sort' => 'featured'])) }}"
                                {{ $sort === 'featured' ? 'selected' : '' }}>Featured & Curated</option>
                            <option
                                value="{{ route('shop', array_merge(request()->query(), ['sort' => 'price_asc'])) }}"
                                {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option
                                value="{{ route('shop', array_merge(request()->query(), ['sort' => 'price_desc'])) }}"
                                {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'rating'])) }}"
                                {{ $sort === 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'newest'])) }}"
                                {{ $sort === 'newest' ? 'selected' : '' }}>Newest Additions</option>
                        </select>
                    </div>
                </div>

                <!-- Watches Cards Grid -->
                @if (count($watches) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($watches as $watch)
                            <article
                                class="group relative flex flex-col rounded-2xl overflow-hidden bg-[#0e1419]/90 border border-white/10 hover:border-[#cb8665]/60 transition-all duration-300 hover:shadow-[0_20px_50px_rgba(0,0,0,0.85),0_0_30px_rgba(203,152,126,0.15)]">

                                <!-- Top Badges & Wishlist -->
                                <div
                                    class="absolute top-3 left-3 right-3 z-20 flex items-center justify-between pointer-events-none">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10.5px] font-['Montserrat',sans-serif] font-bold tracking-wider uppercase bg-[#141b22]/90 backdrop-blur-md text-[#e4ab91] border border-[#cb8665]/40">
                                        {{ $watch['badge'] }}
                                    </span>
                                    @if ($watch['discount'])
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#ca8666] text-white">
                                            {{ $watch['discount'] }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Watch Image Container with Hover Zoom -->
                                <div class="relative w-full h-64 sm:h-72 overflow-hidden bg-[#080b0e] flex items-center justify-center p-4">
                                    <a href="{{ route('product.show', $watch['id']) }}" class="w-full h-full flex items-center justify-center">
                                        <img src="{{ $watch['image'] }}" alt="{{ $watch['title'] }}" loading="lazy"
                                            class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500 rounded-lg" />
                                    </a>

                                    <!-- Hover Quick Actions Bar -->
                                    <div class="absolute inset-x-0 bottom-0 py-2.5 px-3 bg-[#0e1317]/95 backdrop-blur-md border-t border-white/10 flex items-center justify-between opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <a href="{{ route('product.show', $watch['id']) }}"
                                            class="text-[11.5px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#e4ab91] hover:text-white font-semibold transition-colors">
                                            View Piece Details →
                                        </a>
                                        <button type="button" @click="openQuickView({{ $watch['id'] }})"
                                            class="text-[11.5px] font-['Inter',sans-serif] text-[#8e969d] hover:text-[#cb8665] transition-colors flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/></svg>
                                            <span>Quick View</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="flex-1 flex flex-col p-5">
                                    <!-- Brand & Rating -->
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span
                                            class="text-[11.5px] font-['Montserrat',sans-serif] uppercase tracking-[0.2em] font-semibold text-[#dd9c7c]">
                                            {{ $watch['brand'] }}
                                        </span>
                                        <div class="flex items-center gap-1 text-[11px] text-[#f5be9f]">
                                            <span>★</span>
                                            <span class="text-[#8e969d] font-medium">{{ $watch['rating'] }}</span>
                                            <span class="text-[#555f69]">({{ $watch['reviews_count'] }})</span>
                                        </div>
                                    </div>

                                    <!-- Model Title -->
                                    <h3 class="font-['Outfit',sans-serif] text-[16px] font-medium text-[#e8e4df] group-hover:text-white line-clamp-1">
                                        <a href="{{ route('product.show', $watch['id']) }}" class="hover:text-[#cb8665] transition-colors">
                                            {{ $watch['title'] }}
                                        </a>
                                    </h3>

                                    <!-- Quick Technical Specs -->
                                    <p class="text-[12px] font-['Inter',sans-serif] text-[#8e969d] mt-1 line-clamp-1">
                                        {{ $watch['specs']['case_diameter'] }} • {{ $watch['specs']['movement'] }}
                                    </p>

                                    <!-- Price & CTA Footer -->
                                    <div
                                        class="mt-auto pt-4 border-t border-white/5 flex items-center justify-between gap-3">
                                        <div>
                                            <div class="text-lg font-bold text-white font-['Montserrat',sans-serif]">
                                                ${{ number_format($watch['price'], 2) }}
                                            </div>
                                            @if ($watch['original_price'] > $watch['price'])
                                                <div
                                                    class="text-xs text-[#6b737b] line-through font-['Inter',sans-serif]">
                                                    ${{ number_format($watch['original_price'], 2) }}
                                                </div>
                                            @endif
                                        </div>

                                        <button type="button" @click.stop="addToCart({{ $watch['id'] }})"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg font-['Outfit',sans-serif] text-xs font-semibold tracking-wider uppercase text-[#0e1215] bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] hover:brightness-110 shadow-[0_4px_14px_rgba(203,152,126,0.3)] transition-all">
                                            <span class="text-white">Add To Bag</span>
                                        </button>
                                    </div>
                                </div>

                            </article>
                        @endforeach
                    </div>
                @else
                    <!-- Empty Results State -->
                    <div
                        class="rounded-2xl p-12 text-center bg-[#11171d]/60 border border-white/10 max-w-xl mx-auto my-12">
                        <div
                            class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#cb8665]/10 border border-[#cb8665]/30 flex items-center justify-center">
                            <svg class="w-8 h-8 stroke-[#dd9c7c]" viewBox="0 0 24 24" fill="none"
                                stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <h3 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal">No Timepieces
                            Match Your Filter</h3>
                        <p class="text-sm font-['Inter',sans-serif] text-[#8e969d] mt-2 mb-6">
                            We could not find any models matching your selected brand, category or price specifications.
                        </p>
                        <a href="{{ route('shop') }}"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#cb8665] text-white font-['Outfit',sans-serif] text-xs font-semibold tracking-wider uppercase hover:bg-[#dd9c7c] transition-colors">
                            <span>Browse All Timepieces</span>
                        </a>
                    </div>
                @endif

            </div>

        </div>
    </main>

    <!-- Quick View Horology Modal -->
    <div x-show="quickViewWatch" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-black/85 backdrop-blur-md"
        style="display: none;" @keydown.escape.window="quickViewWatch = null">

        <div class="relative w-full max-w-3xl rounded-2xl overflow-hidden bg-[#0f141a] border border-[#cb8665]/40 shadow-[0_25px_80px_rgba(0,0,0,0.95)]"
            @click.outside="quickViewWatch = null">

            <!-- Close Button -->
            <button @click="quickViewWatch = null"
                class="absolute top-4 right-4 z-30 p-2 rounded-full bg-[#182028] text-[#8e969d] hover:text-white transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2 max-h-[85vh] overflow-y-auto">
                <!-- Modal Left: Product Image -->
                <div
                    class="bg-[#090d10] p-6 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-white/5">
                    <img :src="quickViewWatch?.image" :alt="quickViewWatch?.title"
                        class="max-h-80 w-auto object-contain rounded-xl shadow-lg" />
                    <div class="mt-4 text-center">
                        <span
                            class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.2em] text-[#dd9c7c] font-semibold"
                            x-text="quickViewWatch?.brand"></span>
                    </div>
                </div>

                <!-- Modal Right: Specs & Purchasing -->
                <div class="p-6 md:p-8 flex flex-col">
                    <span class="text-xs font-['Montserrat',sans-serif] tracking-wider uppercase text-[#cb8665]"
                        x-text="quickViewWatch?.badge"></span>
                    <h2 class="font-['Outfit',sans-serif] text-xl font-semibold text-white mt-1"
                        x-text="quickViewWatch?.title"></h2>

                    <div class="flex items-center gap-3 mt-3">
                        <span class="text-2xl font-bold font-['Montserrat',sans-serif] text-[#f5be9f]"
                            x-text="'$' + Number(quickViewWatch?.price).toFixed(2)"></span>
                        <template x-if="quickViewWatch?.original_price > quickViewWatch?.price">
                            <span class="text-sm text-[#6b737b] line-through"
                                x-text="'$' + Number(quickViewWatch?.original_price).toFixed(2)"></span>
                        </template>
                    </div>

                    <p class="text-xs text-[#8e969d] font-['Inter',sans-serif] mt-3 leading-relaxed"
                        x-text="quickViewWatch?.description"></p>

                    <!-- Technical Specifications Table -->
                    <div class="mt-6 pt-4 border-t border-white/10 space-y-2 text-xs font-['Inter',sans-serif]">
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Case Diameter</span>
                            <span class="text-white font-medium" x-text="quickViewWatch?.specs?.case_diameter"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Movement</span>
                            <span class="text-white font-medium" x-text="quickViewWatch?.specs?.movement"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Water Resistance</span>
                            <span class="text-white font-medium"
                                x-text="quickViewWatch?.specs?.water_resistance"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Strap Material</span>
                            <span class="text-white font-medium" x-text="quickViewWatch?.specs?.strap_type"></span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-[#8e969d]">Warranty</span>
                            <span class="text-[#5ea391] font-medium" x-text="quickViewWatch?.specs?.warranty"></span>
                        </div>
                    </div>

                    <!-- Add to Bag CTA & Full Details Link inside Modal -->
                    <div class="mt-8 pt-4 border-t border-white/10 flex flex-col sm:flex-row items-center gap-3">
                        <button type="button" @click="addToCart(quickViewWatch?.id); quickViewWatch = null"
                            class="w-full sm:flex-1 py-3 px-6 rounded-xl font-['Outfit',sans-serif] text-xs font-semibold tracking-wider uppercase text-[#0e1215] bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] hover:brightness-110 shadow-lg transition-all text-center">
                            Add To Horology Bag
                        </button>
                        <a :href="'/product/' + quickViewWatch?.id"
                            class="w-full sm:w-auto py-3 px-5 rounded-xl font-['Outfit',sans-serif] text-xs font-semibold tracking-wider uppercase text-white bg-[#141b22] border border-white/15 hover:border-[#cb8665] hover:bg-[#1c242d] transition-all text-center flex items-center justify-center gap-1.5">
                            <span>Full Details</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Website Footer -->
    <x-footer />

</body>

</html>
