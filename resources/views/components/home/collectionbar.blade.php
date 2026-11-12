@props(['section' => null, 'featuredWatches' => null])
@php
    $secBadge = $section?->content['badge'] ?? 'FEATURED COLLECTION';
    $secTitle = $section?->title ?? 'TIMEYRAH: The Collection';

    // Retrieve real database watches (no static hardcoded data)
    $displayWatches = $featuredWatches;
    if (empty($displayWatches) || (is_object($displayWatches) && method_exists($displayWatches, 'isEmpty') && $displayWatches->isEmpty())) {
        $displayWatches = \App\Models\Product::active()
            ->with(['brand', 'images', 'category'])
            ->where('is_featured', true)
            ->latest('id')
            ->take(12)
            ->get();

        if ($displayWatches->isEmpty()) {
            $displayWatches = \App\Models\Product::active()
                ->with(['brand', 'images', 'category'])
                ->latest('id')
                ->take(12)
                ->get();
        }
    }
    $totalWatchesCount = count($displayWatches);
@endphp

<section id="collection" class="relative mx-auto px-4 sm:px-6 lg:px-8 pt-16 sm:pt-24 select-none"
    x-data="{
        current: 0,
        total: {{ $totalWatchesCount }},
        activeCard: null,
        perView: 4,
        touchStartX: 0,
        touchEndX: 0,
        showToast: false,
        toastMessage: '',
        toastTimer: null,
        notify(msg) {
            this.toastMessage = msg;
            this.showToast = true;
            if (this.toastTimer) clearTimeout(this.toastTimer);
            const self = this;
            this.toastTimer = setTimeout(function() {
                self.showToast = false;
            }, 4000);
        },
        updatePerView() {
            if (window.innerWidth >= 1280) this.perView = 4;
            else if (window.innerWidth >= 1024) this.perView = 3;
            else if (window.innerWidth >= 640) this.perView = 2;
            else this.perView = 1;
        },
        init() {
            this.updatePerView();
        },
        get maxIndex() {
            return Math.max(0, this.total - this.perView);
        },
        next() {
            if (this.current < this.maxIndex) {
                this.current++;
            } else {
                this.current = 0;
            }
        },
        prev() {
            if (this.current > 0) {
                this.current--;
            } else {
                this.current = this.maxIndex;
            }
        }
    }"
    @show-cart-toast.window="notify($event.detail.message)"
    @resize.window="updatePerView()"
    @keydown.window.arrow-left="if (!['INPUT', 'TEXTAREA'].includes($event.target.tagName)) prev()"
    @keydown.window.arrow-right="if (!['INPUT', 'TEXTAREA'].includes($event.target.tagName)) next()"
    @touchstart="touchStartX = $event.touches[0].clientX"
    @touchend="touchEndX = $event.changedTouches[0].clientX; if (touchStartX - touchEndX > 50) next(); if (touchEndX - touchStartX > 50) prev();">

    <style>
        /* Unique scoped styling for VELTRA Collection Cards */
        .veltra-collection-card {
            background-color: #0d2124;
            background-image:
                repeating-linear-gradient(90deg,
                    rgba(255, 255, 255, 0.02) 0px,
                    rgba(255, 255, 255, 0.02) 1px,
                    transparent 1px,
                    transparent 3px),
                linear-gradient(180deg,
                    #153237 0%,
                    #0d2225 50%,
                    #071618 100%);
            border: 1px solid rgba(79, 209, 197, 0.22);
            box-shadow:
                0 18px 40px -6px rgba(0, 0, 0, 0.85),
                0 4px 14px -2px rgba(0, 0, 0, 0.5),
                inset 0 1px 1px 0 rgba(255, 255, 255, 0.15),
                inset 0 -1px 1px 0 rgba(0, 0, 0, 0.6);
            transition: all 0.5s cubic-bezier(0.34, 1.35, 0.64, 1);
        }

        .veltra-collection-backdrop {
            background: linear-gradient(135deg, #c99378 0%, #9e6446 50%, #523120 100%);
            box-shadow: 0 10px 24px -4px rgba(0, 0, 0, 0.65);
            transition: all 0.5s cubic-bezier(0.34, 1.35, 0.64, 1);
        }

        /* Hover & Active interactions */
        .veltra-collection-item {
            position: relative;
            cursor: pointer;
            z-index: 10;
            transition: z-index 0.2s ease;
        }

        .veltra-collection-item:hover,
        .veltra-collection-item.is-active {
            z-index: 40;
        }

        .veltra-collection-item:hover .veltra-collection-card,
        .veltra-collection-item.is-active .veltra-collection-card {
            border-color: rgba(79, 209, 197, 0.55);
            box-shadow:
                0 24px 54px -6px rgba(0, 0, 0, 0.95),
                0 0 30px rgba(79, 209, 197, 0.16),
                inset 0 1px 1.5px 0 rgba(255, 255, 255, 0.25),
                inset 0 -1px 1px 0 rgba(0, 0, 0, 0.7);
        }

        .veltra-collection-item:hover .veltra-collection-backdrop,
        .veltra-collection-item.is-active .veltra-collection-backdrop {
            transform: rotate(-4.5deg) scale(1.02);
            opacity: 0.95;
        }

        /* The signature 3D pop-out breakout hover animation for the watch */
        .veltra-watch-img {
            transition: transform 0.5s cubic-bezier(0.34, 1.4, 0.64, 1), filter 0.5s cubic-bezier(0.34, 1.4, 0.64, 1);
            transform-origin: center center;
            filter: drop-shadow(0 14px 20px rgba(0, 0, 0, 0.75));
            will-change: transform, filter;
        }

        .veltra-collection-item:hover .veltra-watch-img,
        .veltra-collection-item.is-active .veltra-watch-img {
            transform: translateY(-38px) translateX(-20px) scale(1.56) rotate(6.5deg);
            filter: drop-shadow(-22px 28px 28px rgba(0, 0, 0, 0.95)) drop-shadow(0 0 25px rgba(79, 209, 197, 0.25));
        }
    </style>

    <!-- Background Technical Horology CAD Blueprint Lines -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden opacity-35 flex items-center justify-end">
        <svg viewBox="0 0 1000 800" fill="none" class="w-[850px] h-[850px] -mr-32 -mt-16 select-none">
            <!-- Concentric Escapement & Dial Calibration Rings -->
            <circle cx="500" cy="400" r="380" stroke="#38bdf8" stroke-width="0.5" stroke-dasharray="3 6"
                opacity="0.35" />
            <circle cx="500" cy="400" r="340" stroke="#4fd1c5" stroke-width="0.8" opacity="0.55" />
            <circle cx="500" cy="400" r="290" stroke="#5ea391" stroke-width="0.5" stroke-dasharray="6 4"
                opacity="0.35" />
            <circle cx="500" cy="400" r="240" stroke="#4fd1c5" stroke-width="0.75" opacity="0.45" />
            <circle cx="500" cy="400" r="180" stroke="#38bdf8" stroke-width="0.5" stroke-dasharray="2 4"
                opacity="0.3" />
            <circle cx="500" cy="400" r="110" stroke="#4fd1c5" stroke-width="0.8" opacity="0.4" />
            <circle cx="500" cy="400" r="45" stroke="#38bdf8" stroke-width="0.5" opacity="0.6" />

            <!-- Technical Degree Calibrations & Radians -->
            <g stroke="#4fd1c5" stroke-width="0.8" opacity="0.55">
                <line x1="500" y1="40" x2="500" y2="70" />
                <line x1="500" y1="730" x2="500" y2="760" />
                <line x1="140" y1="400" x2="170" y2="400" />
                <line x1="830" y1="400" x2="860" y2="400" />
                <line x1="245" y1="145" x2="265" y2="165" />
                <line x1="755" y1="655" x2="735" y2="635" />
                <line x1="755" y1="145" x2="735" y2="165" />
                <line x1="245" y1="655" x2="265" y2="635" />
            </g>

            <!-- Orthogonal Crosshairs -->
            <line x1="50" y1="400" x2="950" y2="400" stroke="#38bdf8" stroke-width="0.5"
                stroke-dasharray="4 8" opacity="0.25" />
            <line x1="500" y1="0" x2="500" y2="800" stroke="#38bdf8" stroke-width="0.5"
                stroke-dasharray="4 8" opacity="0.25" />

            <!-- Escapement Wheel Gear Teeth Simulation -->
            <path
                d="M 500 110 A 290 290 0 0 1 790 400 A 290 290 0 0 1 500 690 A 290 290 0 0 1 210 400 A 290 290 0 0 1 500 110"
                stroke="#4fd1c5" stroke-width="0.6" stroke-dasharray="10 8" opacity="0.3" />
            <!-- Corner Precision Cad Marks -->
            <path d="M 180 80 L 220 80 L 220 120" stroke="#4fd1c5" stroke-width="1.2" opacity="0.4" />
            <path d="M 820 80 L 780 80 L 780 120" stroke="#4fd1c5" stroke-width="1.2" opacity="0.4" />
        </svg>
    </div>

    <!-- Section Header with Title and Arrow Navigation Buttons -->
    <div class="relative z-10 mb-8 sm:mb-12 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <!-- Eyebrow Tag -->
            <div class="flex items-center gap-2 mb-2.5">
                <span class="w-5 h-[1.5px] bg-[#65a396] inline-block"></span>
                <span class="text-[#65a396] uppercase tracking-[0.3em] text-[11px] sm:text-xs font-semibold">
                    {{ $secBadge }}
                </span>
            </div>

            <!-- Section Title -->
            <h2
                class="text-3xl sm:text-4xl lg:text-[44px] font-['Montserrat',sans-serif] font-normal text-white tracking-wide leading-tight">
                {{ $secTitle }}
            </h2>
        </div>

        <!-- Carousel Navigation Controls (Arrow Buttons & Indicators) -->
        <div class="flex items-center gap-4">
            <!-- Counter & Keyboard Hint -->
            <div class="hidden sm:flex flex-col items-end">
                <span class="text-[12px] font-mono tracking-widest text-[#7a9296]">
                    <span class="text-[#d59a7a] font-semibold" x-text="'0' + (current + 1)"></span> / <span x-text="'0' + (maxIndex + 1)"></span>
                </span>
                <span class="inline-flex items-center gap-1 text-[9.5px] uppercase tracking-[0.2em] text-[#65a396]/90 mt-0.5">
                    <kbd class="veltra-collection-kbd px-1 py-0.5 rounded bg-[#102528] border border-[#264b52] text-[#80cbc4] font-mono text-[9px]">&larr;</kbd>
                    <kbd class="veltra-collection-kbd px-1 py-0.5 rounded bg-[#102528] border border-[#264b52] text-[#80cbc4] font-mono text-[9px]">&rarr;</kbd>
                    <span>Keys</span>
                </span>
            </div>

            <!-- Arrow Buttons -->
            <div class="flex items-center gap-2">
                <button @click="prev()" type="button"
                    class="veltra-collection-header-btn w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#d59a7a]/40 bg-[#12272b] hover:bg-[#193a40] hover:border-[#4fd1c5] hover:shadow-[0_0_15px_rgba(79,209,197,0.35)] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 flex items-center justify-center cursor-pointer shadow-lg active:scale-95 group"
                    title="Previous (Left Arrow Key)"
                    aria-label="Previous Watches">
                    <svg class="w-5 h-5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button @click="next()" type="button"
                    class="veltra-collection-header-btn w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#d59a7a]/40 bg-[#12272b] hover:bg-[#193a40] hover:border-[#4fd1c5] hover:shadow-[0_0_15px_rgba(79,209,197,0.35)] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 flex items-center justify-center cursor-pointer shadow-lg active:scale-95 group"
                    title="Next (Right Arrow Key)"
                    aria-label="Next Watches">
                    <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Carousel Viewport & Slider Track -->
    <div class="relative z-10 pt-2 pb-8 overflow-visible">

        <!-- Outer overflow wrapper with padding so hover pop-out doesn't clip -->
        <div class="overflow-x-hidden pt-8 pb-12 -mx-3 px-3">
            <div class="flex transition-transform duration-500 ease-out"
                :style="'transform: translateX(-' + (current * (100 / perView)) + '%)'">

                @foreach ($displayWatches as $watch)
                    @php
                        $watchId = $watch->id;
                        $watchTitle = $watch->title;
                        $watchBrand = $watch->brand?->name ?? 'TIMEYRAH';
                        $watchMovement = $watch->movement ?? ($watch->case_size ? $watch->case_size.' Case' : 'Official Precision');
                        $currentPrice = (float) $watch->current_price;
                        $priceFormatted = '$'.number_format($currentPrice, 2);
                        $isOnSale = $watch->sale_price !== null && $currentPrice < (float) $watch->price;
                        $originalPrice = $isOnSale ? '$'.number_format((float) $watch->price, 2) : null;
                        $watchTag = ! empty($watch->badge) ? $watch->badge : ($watch->is_featured ? 'Best Seller' : ($isOnSale ? 'Special Offer' : 'Official Warranty'));
                        $watchImage = $watch->images->first()?->url ?? asset('hero-watch.png');
                        $productUrl = route('product.show', $watch->slug ?? $watch->id);
                        $watchImages = $watch->images->map(fn ($img) => $img->url)->values()->all();
                        if (empty($watchImages)) {
                            $watchImages = [$watchImage];
                        }
                        $dialVariants = [
                            [
                                'name' => 'Obsidian Black',
                                'bg' => '#14171a',
                                'border' => '#525c66',
                                'image' => $watchImages[0] ?? $watchImage,
                            ],
                            [
                                'name' => 'Steel Gray',
                                'bg' => '#6b7a99',
                                'border' => '#8ba3ce',
                                'image' => $watchImages[1] ?? ($watchImages[0] ?? $watchImage),
                            ],
                            [
                                'name' => 'Rose Bronze',
                                'bg' => '#c99b82',
                                'border' => '#e8baa1',
                                'image' => $watchImages[2] ?? ($watchImages[0] ?? $watchImage),
                            ],
                        ];
                    @endphp

                    <!-- Single Card Slide Wrapper -->
                    <div class="w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 flex-shrink-0 px-3">

                        <!-- Single Watch Card Container -->
                        <div class="veltra-collection-item group"
                            :class="{ 'is-active': activeCard === {{ (int) $watchId }} }"
                            @mouseenter="activeCard = {{ (int) $watchId }}"
                            @mouseleave="activeCard = null"
                            x-data="{
                                watchId: {{ (int) $watchId }},
                                watchTitle: {{ Js::from($watchTitle) }},
                                watchBrand: {{ Js::from($watchBrand) }},
                                watchPrice: {{ (float) $currentPrice }},
                                defaultImage: {{ Js::from($watchImage) }},
                                selectedColor: 0,
                                variants: {{ Js::from($dialVariants) }},
                                get activeVariant() {
                                    return (this.variants && this.variants[this.selectedColor]) || (this.variants && this.variants[0]) || { name: 'Signature Dial', image: this.defaultImage };
                                },
                                get activeImage() {
                                    return (this.activeVariant && this.activeVariant.image) ? this.activeVariant.image : this.defaultImage;
                                },
                                addToCart() {
                                    const domCart = (this.$store && this.$store.cart) ? this.$store.cart : null;
                                    const globalCart = (window.Alpine && window.Alpine.store) ? window.Alpine.store('cart') : null;
                                    const cart = domCart || globalCart;
                                    const variantName = (this.activeVariant && this.activeVariant.name) ? this.activeVariant.name : 'Obsidian Black';
                                    const chosenImage = this.activeImage || this.defaultImage;
                                    if (cart && typeof cart.addItem === 'function') {
                                        cart.addItem({
                                            id: this.watchId,
                                            title: this.watchTitle,
                                            brand: this.watchBrand,
                                            price: this.watchPrice,
                                            image: chosenImage
                                        }, 1, variantName);
                                    }
                                    if (globalCart && domCart && globalCart !== domCart) {
                                        globalCart.items = [...domCart.items];
                                    }
                                    const msg = 'Added 1 × ' + this.watchTitle + ' (' + variantName + ') to your Cart';
                                    if (typeof this.notify === 'function') {
                                        this.notify(msg);
                                    }
                                    this.$dispatch('show-cart-toast', { message: msg });
                                }
                            }">

                            <!-- Layer 1: Bronze Metallic Angled Backplate -->
                            <div
                                class="veltra-collection-backdrop absolute -top-1.5 -left-3 -bottom-2 -right-1 rounded-[28px] opacity-80 -rotate-[2.5deg] scale-[0.98] pointer-events-none">
                            </div>

                            <!-- Layer 2: Main Dark Teal Satin Brushed Metal Card Body -->
                            <div
                                class="veltra-collection-card relative h-[475px] rounded-[26px] p-6 flex flex-col justify-between overflow-visible">

                                <!-- Top Card Bar: Pill/Label & Brand -->
                                <div class="flex items-center justify-between z-20 pointer-events-none">
                                    <span
                                        class="text-[12px] sm:text-[13px] font-medium tracking-wide text-[#d59a7a] transition-colors group-hover:text-[#e4b295]">
                                        {{ $watchTag }}
                                    </span>
                                    <span class="text-[10px] font-mono tracking-widest text-[#65a396] uppercase">
                                        {{ $watchBrand }}
                                    </span>
                                </div>

                                <!-- Center Watch Showcase (With Signature 3D Pop-Out Breakout Effect on Hover) -->
                                <a :href="'{{ $productUrl }}?variant=' + encodeURIComponent(activeVariant.name)" href="{{ $productUrl }}"
                                    class="relative w-full h-[250px] my-1 flex items-center justify-center overflow-visible cursor-pointer block">

                                    <!-- Soft Ambient Radial Glow Behind the Watch on Hover -->
                                    <div
                                        class="absolute w-[200px] h-[200px] rounded-full bg-[#4fd1c5]/0 group-hover:bg-[#4fd1c5]/15 blur-2xl transition-all duration-700 ease-out pointer-events-none">
                                    </div>

                                    <img :src="activeImage" src="{{ $watchImage }}" alt="{{ $watchTitle }}"
                                        onerror="this.src='{{ asset('hero-watch.png') }}'"
                                        class="veltra-watch-img relative z-30 w-auto h-[205px] max-w-none object-contain transition-transform duration-300" />
                                </a>

                                <!-- Bottom Content Area -->
                                <div class="relative z-20 pt-2 flex flex-col justify-end">

                                    <!-- Watch Title -->
                                    <a :href="'{{ $productUrl }}?variant=' + encodeURIComponent(activeVariant.name)" href="{{ $productUrl }}" class="block group/title">
                                        <h3
                                            class="text-white font-medium text-[16px] sm:text-[17px] tracking-wide group-hover/title:text-[#4fd1c5] transition-colors truncate"
                                            title="{{ $watchTitle }}">
                                            {{ $watchTitle }}
                                        </h3>
                                    </a>

                                    <!-- Movement / Dimension Specs -->
                                    <p class="text-[#7c9aa0] text-xs font-light tracking-wide mt-0.5 truncate">
                                        {{ $watchMovement }}
                                    </p>

                                    <!-- Price Tag & Sale Discount -->
                                    <div class="flex items-center gap-2 mt-2.5">
                                        <span class="text-[#d8dee2] text-[15px] sm:text-[16px] font-semibold tracking-tight">
                                            {{ $priceFormatted }}
                                        </span>
                                        @if ($originalPrice)
                                            <span class="text-xs text-[#8e969d] line-through font-normal">
                                                {{ $originalPrice }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Interactive Bottom Controls Bar (Color Swatches & Add-to-Cart) -->
                                    <div class="flex items-center justify-between mt-3.5 pt-1">

                                        <!-- Dial Swatch Selectors -->
                                        <div class="flex items-center gap-2">
                                            <template x-for="(variant, cIndex) in variants" :key="cIndex">
                                                <button type="button" @click.stop="selectedColor = cIndex"
                                                    class="w-3.5 h-3.5 rounded-full transition-all duration-300 relative flex items-center justify-center cursor-pointer"
                                                    :class="selectedColor === cIndex ?
                                                        'ring-2 ring-offset-2 ring-[#4fd1c5] ring-offset-[#0d2124] scale-110' :
                                                        'opacity-70 hover:opacity-100 hover:scale-105'"
                                                    :style="'background-color: ' + variant.bg + '; border: 1px solid ' + variant.border + ';'"
                                                    :title="'Select ' + variant.name + ' dial'">
                                                </button>
                                            </template>
                                        </div>

                                        <!-- Cart Button with Authoritative Database Price & Selected Variant -->
                                        <button type="button"
                                            @click.stop="addToCart()"
                                            class="veltra-collection-cart-btn w-9 h-9 rounded-xl flex items-center justify-center 
                                                       bg-[#14282c] border border-[#2d565e] text-[#65a396] 
                                                       hover:bg-[#65a396] hover:text-[#0b1416] hover:border-[#65a396]
                                                       active:scale-95 transition-all duration-300 shadow-md group/btn cursor-pointer"
                                            :title="'Add ' + (activeVariant ? activeVariant.name : 'Watch') + ' to Cart'">
                                            <svg class="w-4 h-4 transition-transform duration-300 group-hover/btn:scale-110"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="9" cy="21" r="1"></circle>
                                                <circle cx="20" cy="21" r="1"></circle>
                                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                            </svg>
                                        </button>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>
        </div>

        <!-- Floating Side Navigation Arrows -->
        <button @click="prev()" type="button"
            class="veltra-collection-nav-btn flex absolute -left-2 sm:-left-3 lg:-left-5 top-1/2 -translate-y-1/2 z-40 w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#d59a7a]/50 bg-[#0d2124]/95 backdrop-blur-md hover:bg-[#153237] hover:border-[#4fd1c5] hover:shadow-[0_0_20px_rgba(79,209,197,0.4)] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 items-center justify-center cursor-pointer shadow-2xl active:scale-95 group"
            title="Previous Watch"
            aria-label="Previous Watch">
            <svg class="w-5 h-5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button @click="next()" type="button"
            class="veltra-collection-nav-btn flex absolute -right-2 sm:-right-3 lg:-right-5 top-1/2 -translate-y-1/2 z-40 w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#d59a7a]/50 bg-[#0d2124]/95 backdrop-blur-md hover:bg-[#153237] hover:border-[#4fd1c5] hover:shadow-[0_0_20px_rgba(79,209,197,0.4)] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 items-center justify-center cursor-pointer shadow-2xl active:scale-95 group"
            title="Next Watch"
            aria-label="Next Watch">
            <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>

        <!-- Bottom Slide Indicator Pills -->
        <div class="mt-2 flex items-center justify-center gap-2">
            <template x-for="i in (maxIndex + 1)" :key="i">
                <button @click="current = i - 1" type="button"
                    :class="current === (i - 1) ?
                        'w-8 bg-gradient-to-r from-[#65a396] to-[#d59a7a]' :
                        'w-2.5 bg-white/20 hover:bg-white/40'"
                    class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"
                    :aria-label="'Go to slide ' + i">
                </button>
            </template>
        </div>

    </div>

    <!-- Toast Notification for Add to Cart (Matching Product Detail Page) -->
    <div x-show="showToast || ($store.cart && $store.cart.showToast)"
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
            <p class="text-[13px] text-[#e8e4df] font-['Inter',sans-serif] line-clamp-1" x-text="toastMessage || ($store.cart ? $store.cart.toastMessage : '')"></p>
        </div>
        <button type="button" @click="$store.cart.open(); showToast = false; if ($store.cart) $store.cart.showToast = false"
           class="px-3 py-1.5 rounded-lg bg-[#cb8665] text-[#0e1215] text-xs font-['Outfit',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 shrink-0 cursor-pointer">
            View Cart →
        </button>
        <button type="button" @click="showToast = false; if ($store.cart) $store.cart.showToast = false" class="text-[#8e969d] hover:text-white p-1 cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>

</section>
