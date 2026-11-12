<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $watch['title'] }} — TIMEYRAH Watches</title>

    <!-- Immediate Theme Initialization Script (Prevents Theme Flash) -->
    <script>
        (function() {
            try {
                var theme = localStorage.getItem('timeyrah_theme') || 'dark';
                if (theme === 'light') {
                    document.documentElement.classList.add('theme-light');
                    document.documentElement.setAttribute('data-theme', 'light');
                } else {
                    document.documentElement.classList.remove('theme-light');
                    document.documentElement.setAttribute('data-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>
    <meta name="description" content="{{ Str::limit($watch['description'], 160) }}">

    <!-- Google Fonts: Cormorant Garamond, Montserrat, Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js Product Detail Logic Handler -->
    <script>
        function productDetailHandler() {
            return {
                galleryImages: {!! json_encode($watch['images'] ?? [$watch['image']]) !!},
                selectedImage: {!! json_encode($watch['images'][0] ?? $watch['image']) !!},
                activeTab: 'specs',
                quantity: 1,
                selectedVariant: 0,
                variants: ['Obsidian Black', 'Steel Gray', 'Rose Bronze'],
                showToast: false,
                toastMessage: '',
                init() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const vParam = urlParams.get('variant');
                    if (vParam) {
                        const found = this.variants.findIndex(v => v.toLowerCase() === vParam.toLowerCase());
                        if (found > -1) {
                            this.chooseVariant(found);
                        }
                    }
                },
                chooseVariant(index) {
                    this.selectedVariant = index;
                    if (this.galleryImages && this.galleryImages[index]) {
                        this.selectedImage = this.galleryImages[index];
                    }
                },
                selectImage(url) {
                    this.selectedImage = url;
                },
                addToCart() {
                    const chosenVariant = this.variants[this.selectedVariant] || 'Obsidian Black';
                    const domCart = (this.$store && this.$store.cart) ? this.$store.cart : null;
                    const globalCart = (window.Alpine && window.Alpine.store) ? window.Alpine.store('cart') : null;
                    const cart = domCart || globalCart;
                    if (cart && typeof cart.addItem === 'function') {
                        cart.addItem({
                            id: {{ (int) $watch['id'] }},
                            title: {!! json_encode($watch['title']) !!},
                            brand: {!! json_encode($watch['brand']) !!},
                            price: {{ (float) $watch['price'] }},
                            image: this.selectedImage || {!! json_encode($watch['image'] ?? ($watch['images'][0] ?? asset('watches/astra-black.png'))) !!},
                        }, this.quantity, chosenVariant);
                    }
                    if (globalCart && domCart && globalCart !== domCart) {
                        globalCart.items = [...domCart.items];
                    }
                    this.toastMessage = 'Added ' + this.quantity + ' × ' + {!! json_encode($watch['title']) !!} + ' (' + chosenVariant + ') to your Cart';
                    this.showToast = true;
                    const self = this;
                    setTimeout(function() {
                        self.showToast = false;
                    }, 4000);
                }
            };
        }

        if (typeof window !== 'undefined') {
            window.productDetailHandler = productDetailHandler;
            document.addEventListener('alpine:init', function() {
                if (window.Alpine && window.Alpine.data) {
                    Alpine.data('productDetailHandler', productDetailHandler);
                }
            });
        }
    </script>
</head>

<body class="bg-[#0b0f12] text-[#d1d6da] min-h-screen selection:bg-[#cb8665]/30 selection:text-white"
    x-data="productDetailHandler()">

    <!-- Top Navigation Component with Mega Menu -->
    <x-navbar />

    <!-- Toast Notification for Add to Cart -->
    <div x-show="showToast"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-4 opacity-0 scale-95"
        x-transition:enter-end="translate-y-0 opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100 scale-100"
        x-transition:leave-end="translate-y-4 opacity-0 scale-95"
        class="veltra-shop-toast fixed bottom-6 right-6 z-[100] max-w-md p-4 rounded-xl bg-[#141b22]/95 border border-[#cb8665]/50 shadow-[0_15px_40px_rgba(0,0,0,0.9),0_0_25px_rgba(203,152,126,0.2)] backdrop-blur-xl flex items-center gap-3.5"
        style="display: none;">
        <div class="w-9 h-9 rounded-full bg-[#cb8665]/20 border border-[#cb8665]/40 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 stroke-[#e4ab91]" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#e4ab91] font-semibold">Shopping Cart Updated</h4>
            <p class="text-[13px] text-[#e8e4df] font-['Inter',sans-serif] line-clamp-1" x-text="toastMessage"></p>
        </div>
        <button type="button" @click="$store.cart.open(); showToast = false"
           class="px-3 py-1.5 rounded-lg bg-[#cb8665] text-[#0e1215] text-xs font-['Outfit',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 shrink-0 cursor-pointer">
            View Cart →
        </button>
        <button @click="showToast = false" class="text-[#8e969d] hover:text-white p-1">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>

    <!-- Breadcrumbs Navigation -->
    <section class="border-b border-white/5 bg-[#0e1317]/60 py-3.5 px-4 sm:px-6 lg:px-12">
        <div class="max-w-[1600px] mx-auto">
            <nav class="flex items-center flex-wrap gap-2 text-xs font-['Inter',sans-serif] text-[#8e969d]">
                <a href="{{ route('home') }}" class="hover:text-[#cb8665] transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('shop') }}" class="hover:text-[#cb8665] transition-colors">Shop Catalog</a>
                <span>/</span>
                <a href="{{ route('shop', ['brand' => $watch['brand']]) }}" class="hover:text-[#cb8665] transition-colors">{{ $watch['brand'] }}</a>
                <span>/</span>
                <span class="text-[#e8e4df] font-medium truncate max-w-xs sm:max-w-md">{{ $watch['title'] }}</span>
            </nav>
        </div>
    </section>

    <!-- Main Product Showcase Section -->
    <main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-12 py-10 lg:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-16 items-start">

            <!-- Left: Multi-Angle Interactive Gallery (5 Cols on LG, 6 on XL) -->
            <div class="lg:col-span-6 xl:col-span-6 space-y-4">
                
                <!-- Main Viewer Card -->
                <div class="relative w-full aspect-square sm:aspect-[4/3] lg:aspect-square rounded-2xl overflow-hidden bg-[#090d10] border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.85)] flex items-center justify-center p-6 sm:p-10 group">
                    
                    <!-- Badges Overlay -->
                    <div class="absolute top-4 left-4 z-20 flex flex-col gap-2 pointer-events-none">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-['Montserrat',sans-serif] font-bold tracking-wider uppercase bg-[#141b22]/90 backdrop-blur-md text-[#e4ab91] border border-[#cb8665]/40 shadow-sm">
                            {{ $watch['badge'] }}
                        </span>
                        @if (!empty($watch['discount']))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10.5px] font-bold bg-[#ca8666] text-white shadow-sm w-max">
                                {{ $watch['discount'] }}
                            </span>
                        @endif
                    </div>

                    <!-- Certified Atelier Watermark Emblem -->
                    <div class="absolute bottom-4 right-4 z-20 pointer-events-none opacity-40">
                        <span class="text-[10px] font-['Montserrat',sans-serif] tracking-[0.25em] text-[#8e969d] uppercase">TIMEYRAH PRECISION</span>
                    </div>

                    <!-- Main Watch Image with Smooth Scale Zoom -->
                    <img :src="selectedImage"
                         src="{{ $watch['images'][0] ?? $watch['image'] }}"
                         onerror="this.onerror=null; this.src='{{ asset('watches/astra-black.png') }}';"
                         alt="{{ $watch['title'] }}"
                         class="w-full h-full object-contain max-h-[480px] transition-transform duration-500 group-hover:scale-105 cursor-zoom-in" />
                </div>

                <!-- Thumbnails Carousel Strip -->
                @if (count($watch['images']) > 1)
                    <div class="grid grid-cols-4 sm:grid-cols-4 gap-3 sm:gap-4 pt-1">
                        @foreach ($watch['images'] as $imgIndex => $imgUrl)
                            <button type="button"
                                    @click="selectImage('{{ $imgUrl }}')"
                                    class="relative aspect-square rounded-xl overflow-hidden bg-[#090d10] border p-2 transition-all duration-200 cursor-pointer"
                                    :class="selectedImage === '{{ $imgUrl }}' ? 'border-[#cb8665] ring-2 ring-[#cb8665]/40 scale-[1.02]' : 'border-white/10 opacity-70 hover:opacity-100 hover:border-white/20'">
                                <img src="{{ $imgUrl }}"
                                     onerror="this.onerror=null; this.src='{{ asset('watches/astra-black.png') }}';"
                                     alt="Angle {{ $imgIndex + 1 }}"
                                     class="w-full h-full object-cover object-center rounded-lg" />
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Horology Guarantees Trio Bar -->
                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-white/5 text-center">
                    <div class="p-3 rounded-xl bg-[#11171d]/60 border border-white/5">
                        <svg class="w-5 h-5 mx-auto mb-1.5 stroke-[#5ea391]" viewBox="0 0 24 24" fill="none" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#e8e4df] block font-semibold">100% Authentic</span>
                        <span class="text-[10px] text-[#8e969d] block">Brand Verified</span>
                    </div>
                    <div class="p-3 rounded-xl bg-[#11171d]/60 border border-white/5">
                        <svg class="w-5 h-5 mx-auto mb-1.5 stroke-[#cb8665]" viewBox="0 0 24 24" fill="none" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="m4.93 4.93 4.24 4.24"/><path d="m14.83 9.17 4.24-4.24"/><path d="m14.83 14.83 4.24 4.24"/><path d="m9.17 14.83-4.24 4.24"/><circle cx="12" cy="12" r="4"/></svg>
                        <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#e8e4df] block font-semibold">Official Warranty</span>
                        <span class="text-[10px] text-[#8e969d] block">With Warranty Card</span>
                    </div>
                    <div class="p-3 rounded-xl bg-[#11171d]/60 border border-white/5">
                        <svg class="w-5 h-5 mx-auto mb-1.5 stroke-[#38bdf8]" viewBox="0 0 24 24" fill="none" stroke-width="1.8"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#e8e4df] block font-semibold">Free Delivery</span>
                        <span class="text-[10px] text-[#8e969d] block">All Across Pakistan</span>
                    </div>
                </div>

            </div>

            <!-- Right: Product Information & Purchase Controls (6 Cols on LG, 6 on XL) -->
            <div class="lg:col-span-6 xl:col-span-6 flex flex-col space-y-6">

                <!-- Header Title & Brand -->
                <div>
                    <!-- Brand & Reference -->
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <a href="{{ route('shop', ['brand' => $watch['brand']]) }}"
                           class="inline-block text-xs font-['Montserrat',sans-serif] font-bold tracking-[0.25em] text-[#dd9c7c] uppercase hover:text-[#f5be9f] transition-colors">
                            {{ $watch['brand'] }}
                        </a>
                        <span class="text-[11px] font-mono text-[#6b737b]">
                            REF: TY-{{ strtoupper(substr($watch['brand'], 0, 3)) }}-{{ $watch['id'] }}
                        </span>
                    </div>

                    <!-- Watch Title -->
                    <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl lg:text-4xl font-normal text-white tracking-tight leading-tight">
                        {{ $watch['title'] }}
                    </h1>

                    <!-- Rating & Reviews -->
                    <div class="flex items-center gap-3 mt-3">
                        <div class="flex items-center text-[#cb8665] text-sm">
                            <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                        </div>
                        <span class="text-xs font-medium text-white">{{ $watch['rating'] }}</span>
                        <span class="text-xs text-[#8e969d]">({{ $watch['reviews_count'] }} Verified Collector Reviews)</span>
                        <span class="text-xs text-[#4fd1c5] font-medium">• Certified In-Stock</span>
                    </div>
                </div>

                <!-- Price Block -->
                <div class="p-5 rounded-2xl bg-[#11171d]/90 border border-white/10 flex flex-wrap items-baseline justify-between gap-4">
                    <div>
                        <div class="flex items-baseline gap-3">
                            <span class="font-['Montserrat',sans-serif] text-3xl sm:text-4xl font-bold text-white tracking-tight">
                                ${{ number_format($watch['price'], 2) }}
                            </span>
                            @if ($watch['original_price'] > $watch['price'])
                                <span class="text-base text-[#6b737b] line-through font-['Inter',sans-serif]">
                                    ${{ number_format($watch['original_price'], 2) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-[#8e969d] mt-1 font-['Inter',sans-serif]">
                            Taxes and insured duties calculated at checkout. Flexible installment options available.
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#5ea391]/15 border border-[#5ea391]/30 text-xs text-[#5ea391]">
                        <span class="w-2 h-2 rounded-full bg-[#5ea391] animate-pulse"></span>
                        <span>Ready For Dispatch</span>
                    </div>
                </div>

                <!-- Watch Description Snippet -->
                <p class="text-sm font-['Inter',sans-serif] text-[#9ba1a7] leading-relaxed font-light">
                    {{ $watch['description'] }}
                </p>

                <!-- Key Quick Highlights Pill Bar -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 py-1 text-xs font-['Inter',sans-serif]">
                    <div class="p-2.5 rounded-lg bg-[#090d10] border border-white/5">
                        <span class="text-[#6b737b] block text-[10.5px]">Case Diameter</span>
                        <span class="text-white font-medium">{{ $watch['specs']['case_diameter'] }}</span>
                    </div>
                    <div class="p-2.5 rounded-lg bg-[#090d10] border border-white/5">
                        <span class="text-[#6b737b] block text-[10.5px]">Movement</span>
                        <span class="text-white font-medium truncate block">{{ $watch['specs']['movement'] }}</span>
                    </div>
                    <div class="p-2.5 rounded-lg bg-[#090d10] border border-white/5 col-span-2 sm:col-span-1">
                        <span class="text-[#6b737b] block text-[10.5px]">Water Resistance</span>
                        <span class="text-white font-medium">{{ $watch['specs']['water_resistance'] }}</span>
                    </div>
                </div>

                <!-- Variant Selection Swatches -->
                <div>
                    <label class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-2.5 font-semibold">
                        Dial Finishing & Architecture: <span class="text-[#e4ab91] normal-case" x-text="variants[selectedVariant]"></span>
                    </label>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="chooseVariant(0)"
                                class="px-3.5 py-1.5 rounded-lg text-xs font-['Inter',sans-serif] transition-all flex items-center gap-2 border cursor-pointer"
                                :class="selectedVariant === 0 ? 'border-[#cb8665] bg-[#cb8665]/15 text-[#f5be9f]' : 'border-white/10 text-[#8e969d] hover:border-white/25'">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#14171a] border border-[#525c66]"></span>
                            <span>Obsidian Black</span>
                        </button>
                        <button type="button" @click="chooseVariant(1)"
                                class="px-3.5 py-1.5 rounded-lg text-xs font-['Inter',sans-serif] transition-all flex items-center gap-2 border cursor-pointer"
                                :class="selectedVariant === 1 ? 'border-[#cb8665] bg-[#cb8665]/15 text-[#f5be9f]' : 'border-white/10 text-[#8e969d] hover:border-white/25'">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#6b7a99] border border-[#8ba3ce]"></span>
                            <span>Steel Gray</span>
                        </button>
                        <button type="button" @click="chooseVariant(2)"
                                class="px-3.5 py-1.5 rounded-lg text-xs font-['Inter',sans-serif] transition-all flex items-center gap-2 border cursor-pointer"
                                :class="selectedVariant === 2 ? 'border-[#cb8665] bg-[#cb8665]/15 text-[#f5be9f]' : 'border-white/10 text-[#8e969d] hover:border-white/25'">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#c99b82] border border-[#e8baa1]"></span>
                            <span>Rose Bronze</span>
                        </button>
                    </div>
                </div>

                <!-- Quantity & Add to Cart Controls -->
                <div class="pt-2 flex flex-col sm:flex-row items-stretch gap-4">
                    <!-- Quantity Stepper -->
                    <div class="flex items-center justify-between border border-white/15 rounded-xl bg-[#090d10] px-3 py-2 w-full sm:w-36">
                        <button type="button"
                                @click="quantity = Math.max(1, quantity - 1)"
                                class="w-8 h-8 flex items-center justify-center text-lg text-[#8e969d] hover:text-white transition-colors">
                            −
                        </button>
                        <span class="font-['Montserrat',sans-serif] font-semibold text-white text-sm" x-text="quantity"></span>
                        <button type="button"
                                @click="quantity++"
                                class="w-8 h-8 flex items-center justify-center text-lg text-[#8e969d] hover:text-white transition-colors">
                            +
                        </button>
                    </div>

                    <!-- Add to Bag Primary CTA Button -->
                    <button type="button"
                            @click="addToCart()"
                            class="flex-1 py-3.5 px-8 rounded-xl font-['Outfit',sans-serif] text-sm font-semibold tracking-wider uppercase text-[#0e1215] bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] hover:brightness-110 shadow-[0_4px_25px_rgba(203,152,126,0.35)] transition-all flex items-center justify-center gap-3">
                        <svg class="w-4 h-4 text-[#0e1215]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        <span>ADD TO CART</span>
                    </button>
                </div>

                <!-- Express Purchase Option -->
                <button type="button"
                        @click="window.location.href = '{{ route('checkout') }}?product_id={{ $watch['id'] }}&quantity=' + quantity + '&variant=' + encodeURIComponent(variants[selectedVariant] || 'Obsidian Black')"
                        class="w-full py-3 px-6 rounded-xl font-['Outfit',sans-serif] text-xs font-semibold tracking-widest uppercase text-white bg-[#141b22] border border-white/10 hover:border-[#cb8665]/60 hover:bg-[#1b242e] transition-all flex items-center justify-center gap-2 cursor-pointer shadow-md">
                    <span>BUY NOW • INSTANT ORDER →</span>
                </button>

                <!-- Customer Care Accordion Strip -->
                <div class="pt-4 border-t border-white/10 text-xs font-['Inter',sans-serif] text-[#8e969d] space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="text-[#cb8665]">✓</span>
                        <span>Official Brand Warranty: <strong>{{ $watch['specs']['warranty'] }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[#cb8665]">✓</span>
                        <span>Free Delivery Across Pakistan (Cash on Delivery Available)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[#cb8665]">✓</span>
                        <span>7-Day Easy Return & Exchange Guarantee</span>
                    </div>
                </div>

            </div>

        </div>

        <!-- Comprehensive Horology Tabs Section -->
        <section class="mt-16 lg:mt-24 pt-10 border-t border-white/10">
            <!-- Tabs Navigation -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-4 border-b border-white/10 pb-4">
                <button type="button"
                        @click="activeTab = 'specs'"
                        class="px-5 py-2.5 rounded-xl font-['Montserrat',sans-serif] text-xs font-semibold tracking-wider uppercase transition-all"
                        :class="activeTab === 'specs' ? 'bg-[#cb8665] text-[#0e1215] shadow-lg' : 'text-[#8e969d] hover:text-white hover:bg-white/5'">
                    Technical Specifications
                </button>
                <button type="button"
                        @click="activeTab = 'craft'"
                        class="px-5 py-2.5 rounded-xl font-['Montserrat',sans-serif] text-xs font-semibold tracking-wider uppercase transition-all"
                        :class="activeTab === 'craft' ? 'bg-[#cb8665] text-[#0e1215] shadow-lg' : 'text-[#8e969d] hover:text-white hover:bg-white/5'">
                    Heritage & Master Craft
                </button>
                <button type="button"
                        @click="activeTab = 'warranty'"
                        class="px-5 py-2.5 rounded-xl font-['Montserrat',sans-serif] text-xs font-semibold tracking-wider uppercase transition-all"
                        :class="activeTab === 'warranty' ? 'bg-[#cb8665] text-[#0e1215] shadow-lg' : 'text-[#8e969d] hover:text-white hover:bg-white/5'">
                    Warranty & Delivery
                </button>
                <button type="button"
                        @click="activeTab = 'reviews'"
                        class="px-5 py-2.5 rounded-xl font-['Montserrat',sans-serif] text-xs font-semibold tracking-wider uppercase transition-all"
                        :class="activeTab === 'reviews' ? 'bg-[#cb8665] text-[#0e1215] shadow-lg' : 'text-[#8e969d] hover:text-white hover:bg-white/5'">
                    Collector Reviews ({{ $watch['reviews_count'] }})
                </button>
            </div>

            <!-- Tab 1: Technical Specifications Table -->
            <div x-show="activeTab === 'specs'" class="py-8" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
                    <div class="p-6 rounded-2xl bg-[#0e1419]/90 border border-white/10 space-y-4 text-xs font-['Inter',sans-serif]">
                        <h3 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.2em] font-semibold text-[#dd9c7c] pb-2 border-b border-white/5">
                            Case Architecture & Crystal
                        </h3>
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Case Diameter</span>
                            <span class="text-white font-medium">{{ $watch['specs']['case_diameter'] }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Case Material</span>
                            <span class="text-white font-medium">{{ $watch['specs']['case_material'] }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Dial Glass</span>
                            <span class="text-white font-medium">{{ $watch['specs']['glass'] }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-[#8e969d]">Water Resistance</span>
                            <span class="text-white font-medium">{{ $watch['specs']['water_resistance'] }}</span>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-[#0e1419]/90 border border-white/10 space-y-4 text-xs font-['Inter',sans-serif]">
                        <h3 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.2em] font-semibold text-[#dd9c7c] pb-2 border-b border-white/5">
                            Movement Caliber & Strap
                        </h3>
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Caliber Movement</span>
                            <span class="text-white font-medium">{{ $watch['specs']['movement'] }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Strap Material</span>
                            <span class="text-white font-medium">{{ $watch['specs']['strap_type'] }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-white/5">
                            <span class="text-[#8e969d]">Category Classification</span>
                            <span class="text-[#cb8665] font-medium">{{ implode(' • ', (array) $watch['categories']) }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-[#8e969d]">International Warranty</span>
                            <span class="text-[#5ea391] font-medium">{{ $watch['specs']['warranty'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Heritage & Master Craft -->
            <div x-show="activeTab === 'craft'" class="py-8 max-w-3xl space-y-4 text-sm font-['Inter',sans-serif] text-[#9ba1a7] leading-relaxed font-light" style="display: none;">
                <h3 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal">
                    Quality & Craftsmanship by {{ $watch['brand'] }}
                </h3>
                <p>
                    Every detail of the {{ $watch['title'] }} represents high standards of design and durability. Each watch is thoroughly inspected and tested before delivery to guarantee accuracy, scratch-resistance, and long-lasting performance.
                </p>
                <p>
                    Whether powered by an automatic mechanical movement or a reliable high-precision quartz movement, this watch is built to withstand everyday wear while keeping its brilliant luxury shine.
                </p>
            </div>

            <!-- Tab 3: Warranty & Delivery -->
            <div x-show="activeTab === 'warranty'" class="py-8 max-w-3xl space-y-6 text-xs font-['Inter',sans-serif] text-[#9ba1a7]" style="display: none;">
                <div class="p-6 rounded-2xl bg-[#0e1419]/90 border border-white/10 space-y-3">
                    <h4 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold text-[#dd9c7c]">Official Brand Warranty</h4>
                    <p class="leading-relaxed">
                        This watch includes official brand warranty documentation valid for <strong>{{ $watch['specs']['warranty'] }}</strong>. The internal movement, dial parts, and water resistance seals are covered according to official warranty guidelines.
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-[#0e1419]/90 border border-white/10 space-y-3">
                    <h4 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold text-[#dd9c7c]">Safe Packaging & Fast Delivery</h4>
                    <p class="leading-relaxed">
                        All watches are securely packed in original brand boxes with bubble cushioning. We provide free doorstep courier delivery across Pakistan with Cash on Delivery (COD) option so you can pay with complete confidence.
                    </p>
                </div>
            </div>

            <!-- Tab 4: Reviews -->
            <div x-show="activeTab === 'reviews'" class="py-8 max-w-4xl space-y-4" style="display: none;">
                <div class="p-6 rounded-2xl bg-[#0e1419]/90 border border-white/10 mb-6 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <div class="text-3xl font-bold font-['Montserrat',sans-serif] text-white">{{ $watch['rating'] }} <span class="text-sm font-normal text-[#8e969d]">/ 5.0</span></div>
                        <div class="flex text-[#cb8665] text-sm mt-1">★★★★★</div>
                        <p class="text-xs text-[#8e969d] mt-1">Based on {{ $watch['reviews_count'] }} verified customer reviews</p>
                    </div>
                    <button type="button" class="px-5 py-2.5 rounded-xl bg-[#141b22] border border-white/15 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-white hover:border-[#cb8665] transition-colors">
                        Write a Review
                    </button>
                </div>

                <!-- Review Sample 1 -->
                <div class="p-5 rounded-xl bg-[#0e1419]/60 border border-white/5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-white font-['Montserrat',sans-serif]">Jonathan H. — Verified Buyer</span>
                        <span class="text-[11px] text-[#6b737b]">2 weeks ago</span>
                    </div>
                    <div class="text-[#cb8665] text-xs">★★★★★</div>
                    <p class="text-xs text-[#9ba1a7] leading-relaxed">
                        "The craftsmanship exceeded all expectations. Dial finishing is mesmerizing in natural sunlight, and the timekeeping precision is within +2 seconds per day. The packaging was top tier."
                    </p>
                </div>

                <!-- Review Sample 2 -->
                <div class="p-5 rounded-xl bg-[#0e1419]/60 border border-white/5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-white font-['Montserrat',sans-serif]">Elena R. — Verified Buyer</span>
                        <span class="text-[11px] text-[#6b737b]">1 month ago</span>
                    </div>
                    <div class="text-[#cb8665] text-xs">★★★★★</div>
                    <p class="text-xs text-[#9ba1a7] leading-relaxed">
                        "Stunning weight and wrist presence. Shipped internationally and arrived in flawless condition with all warranty documentation stamped."
                    </p>
                </div>
            </div>
        </section>

        <!-- Related Timepieces Section ("You May Also Admire") -->
        @if (!empty($relatedWatches) && count($relatedWatches) > 0)
            <section class="mt-20 pt-10 border-t border-white/10">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                    <div>
                        <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.25em] text-[#dd9c7c] font-semibold">
                            Curated Complements
                        </span>
                        <h2 class="font-['Cormorant_Garamond',serif] text-3xl font-normal text-white mt-1">
                            You May Also <span class="italic text-[#dd9c7c]">Admire</span>
                        </h2>
                    </div>
                    <a href="{{ route('shop', ['brand' => $watch['brand']]) }}"
                       class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#cb8665] hover:text-[#f5be9f] flex items-center gap-1.5 transition-colors">
                        <span>More by {{ $watch['brand'] }}</span>
                        <span>→</span>
                    </a>
                </div>

                <!-- Related Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedWatches as $rel)
                        <article class="group relative flex flex-col rounded-2xl overflow-hidden bg-[#0e1419]/90 border border-white/10 hover:border-[#cb8665]/60 transition-all duration-300 hover:shadow-[0_20px_40px_rgba(0,0,0,0.85)]">
                            <a href="{{ route('product.show', $rel['id']) }}" class="relative w-full h-56 bg-[#080b0e] flex items-center justify-center p-4 overflow-hidden block">
                                <img src="{{ $rel['image'] }}"
                                     onerror="this.onerror=null; this.src='{{ asset('watches/astra-black.png') }}';"
                                     alt="{{ $rel['title'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 rounded-lg" />
                                <span class="absolute top-3 left-3 px-2 py-0.5 rounded-full text-[9.5px] font-bold uppercase bg-[#141b22]/90 text-[#e4ab91] border border-[#cb8665]/40">
                                    {{ $rel['badge'] }}
                                </span>
                            </a>
                            <div class="p-4 flex flex-col flex-1">
                                <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#dd9c7c] font-semibold">
                                    {{ $rel['brand'] }}
                                </span>
                                <h3 class="font-['Outfit',sans-serif] text-sm text-[#e8e4df] group-hover:text-white line-clamp-1 mt-1">
                                    <a href="{{ route('product.show', $rel['id']) }}">{{ $rel['title'] }}</a>
                                </h3>
                                <div class="mt-auto pt-3 flex items-center justify-between border-t border-white/5">
                                    <span class="text-sm font-bold text-white font-['Montserrat',sans-serif]">
                                        ${{ number_format($rel['price'], 2) }}
                                    </span>
                                    <a href="{{ route('product.show', $rel['id']) }}"
                                       class="text-xs font-['Outfit',sans-serif] text-[#cb8665] hover:text-[#f5be9f] font-semibold uppercase tracking-wider">
                                        View →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    <!-- Global Website Footer -->
    <x-footer />

</body>

</html>
