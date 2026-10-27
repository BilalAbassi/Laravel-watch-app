<section id="collection" class="relative mx-auto px-4 sm:px-6 lg:px-8 pt-16 sm:pt-24 select-none"
    x-data="{
        current: 0,
        total: 8,
        activeCard: null,
        perView: 4,
        touchStartX: 0,
        touchEndX: 0,
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
                    FEATURED COLLECTION
                </span>
            </div>

            <!-- Section Title -->
            <h2
                class="text-3xl sm:text-4xl lg:text-[44px] font-['Montserrat',sans-serif] font-normal text-white tracking-wide leading-tight">
                TIMEYRAH: <span
                    class="font-['Cormorant_Garamond',serif] italic font-normal text-[#d59a7a] tracking-wider text-4xl sm:text-5xl lg:text-[48px]">The
                    Collection</span>
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
                    <kbd class="px-1 py-0.5 rounded bg-[#102528] border border-[#264b52] text-[#80cbc4] font-mono text-[9px]">&larr;</kbd>
                    <kbd class="px-1 py-0.5 rounded bg-[#102528] border border-[#264b52] text-[#80cbc4] font-mono text-[9px]">&rarr;</kbd>
                    <span>Keys</span>
                </span>
            </div>

            <!-- Arrow Buttons -->
            <div class="flex items-center gap-2">
                <button @click="prev()" type="button"
                    class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#d59a7a]/40 bg-[#12272b] hover:bg-[#193a40] hover:border-[#4fd1c5] hover:shadow-[0_0_15px_rgba(79,209,197,0.35)] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 flex items-center justify-center cursor-pointer shadow-lg active:scale-95 group"
                    title="Previous (Left Arrow Key)"
                    aria-label="Previous Watches">
                    <svg class="w-5 h-5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button @click="next()" type="button"
                    class="w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#d59a7a]/40 bg-[#12272b] hover:bg-[#193a40] hover:border-[#4fd1c5] hover:shadow-[0_0_15px_rgba(79,209,197,0.35)] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 flex items-center justify-center cursor-pointer shadow-lg active:scale-95 group"
                    title="Next (Right Arrow Key)"
                    aria-label="Next Watches">
                    <svg class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @php
        $watches = [
            [
                'id' => 1,
                'name' => 'Astra Black',
                'subtitle' => 'Automatic — 500m',
                'price' => 'Rs. 99,999',
                'tag' => 'Best Seller',
                'image' => asset('watches/astra-black.png'),
                'colors' => [
                    ['bg' => '#6b7a99', 'border' => '#8ba3ce'],
                    ['bg' => '#2a2e34', 'border' => '#444c56'],
                    ['bg' => '#14171a', 'border' => '#32373e'],
                ],
            ],
            [
                'id' => 2,
                'name' => 'Saisae Moonphase',
                'subtitle' => 'Automatic — Swiss',
                'price' => 'Rs. 119,990',
                'tag' => 'Best Seller',
                'image' => asset('watches/saisae-moonphase.png'),
                'colors' => [
                    ['bg' => '#c99b82', 'border' => '#e8baa1'],
                    ['bg' => '#5e3825', 'border' => '#855137'],
                    ['bg' => '#2b3033', 'border' => '#475055'],
                ],
            ],
            [
                'id' => 3,
                'name' => 'Titlon Dive',
                'subtitle' => 'Automatic — 300m',
                'price' => 'Rs. 99,999',
                'tag' => 'Best Seller',
                'image' => asset('watches/titlon-dive.png'),
                'colors' => [
                    ['bg' => '#4fd1c5', 'border' => '#7fe8de'],
                    ['bg' => '#63988e', 'border' => '#8dc4b9'],
                    ['bg' => '#1b2a2b', 'border' => '#365355'],
                ],
            ],
            [
                'id' => 4,
                'name' => 'Nicva Shin',
                'subtitle' => 'Minimal — 38mm',
                'price' => 'Rs. 59,900',
                'tag' => 'Best Seller',
                'image' => asset('watches/nicva-shin.png'),
                'colors' => [
                    ['bg' => '#16191c', 'border' => '#3a3f45'],
                    ['bg' => '#384048', 'border' => '#5b6773'],
                    ['bg' => '#1f2429', 'border' => '#414b54'],
                ],
            ],
            [
                'id' => 5,
                'name' => 'Astra Chrono Emerald',
                'subtitle' => 'Automatic — Limited',
                'price' => 'Rs. 109,999',
                'tag' => 'Limited Edition',
                'image' => asset('watches/astra-black.png'),
                'colors' => [
                    ['bg' => '#1a3c34', 'border' => '#2d6a5c'],
                    ['bg' => '#d59a7a', 'border' => '#e8baa1'],
                    ['bg' => '#111417', 'border' => '#2f353b'],
                ],
            ],
            [
                'id' => 6,
                'name' => 'Saisae Celestial Rose',
                'subtitle' => 'Haute Horlogerie',
                'price' => 'Rs. 135,000',
                'tag' => 'Masterpiece',
                'image' => asset('watches/saisae-moonphase.png'),
                'colors' => [
                    ['bg' => '#e8baa1', 'border' => '#f5d3c1'],
                    ['bg' => '#382218', 'border' => '#5e3825'],
                    ['bg' => '#191f24', 'border' => '#303b44'],
                ],
            ],
            [
                'id' => 7,
                'name' => 'Titlon Deep Mariner',
                'subtitle' => 'Titanium — 1000m',
                'price' => 'Rs. 115,000',
                'tag' => 'Professional',
                'image' => asset('watches/titlon-dive.png'),
                'colors' => [
                    ['bg' => '#1b3b44', 'border' => '#326f80'],
                    ['bg' => '#d59a7a', 'border' => '#e8baa1'],
                    ['bg' => '#0c181c', 'border' => '#1c363e'],
                ],
            ],
            [
                'id' => 8,
                'name' => 'Nicva Pure Minimal',
                'subtitle' => 'Sapphire — 39mm',
                'price' => 'Rs. 65,000',
                'tag' => 'Signature',
                'image' => asset('watches/nicva-shin.png'),
                'colors' => [
                    ['bg' => '#242a30', 'border' => '#424d57'],
                    ['bg' => '#c99b82', 'border' => '#e8baa1'],
                    ['bg' => '#0f1316', 'border' => '#273138'],
                ],
            ],
        ];
    @endphp

    <!-- Carousel Viewport & Slider Track -->
    <div class="relative z-10 pt-2 pb-8 overflow-visible">

        <!-- Outer overflow wrapper with padding so hover pop-out doesn't clip -->
        <div class="overflow-x-hidden pt-8 pb-12 -mx-3 px-3">
            <div class="flex transition-transform duration-500 ease-out"
                :style="'transform: translateX(-' + (current * (100 / perView)) + '%)'">

                @foreach ($watches as $watch)
                    <!-- Single Card Slide Wrapper -->
                    <div class="w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 flex-shrink-0 px-3">

                        <!-- Single Watch Card Container -->
                        <div class="veltra-collection-item group"
                            :class="{ 'is-active': activeCard === {{ $watch['id'] }} }"
                            @mouseenter="activeCard = {{ $watch['id'] }}"
                            @mouseleave="activeCard = null"
                            x-data="{ selectedColor: 0 }">

                            <!-- Layer 1: Bronze Metallic Angled Backplate -->
                            <div
                                class="veltra-collection-backdrop absolute -top-1.5 -left-3 -bottom-2 -right-1 rounded-[28px] opacity-80 -rotate-[2.5deg] scale-[0.98] pointer-events-none">
                            </div>

                            <!-- Layer 2: Main Dark Teal Satin Brushed Metal Card Body -->
                            <div
                                class="veltra-collection-card relative h-[475px] rounded-[26px] p-6 flex flex-col justify-between overflow-visible">

                                <!-- Top Card Bar: Best Seller Pill/Label -->
                                <div class="flex items-center justify-between z-20 pointer-events-none">
                                    <span
                                        class="text-[12px] sm:text-[13px] font-medium tracking-wide text-[#d59a7a] transition-colors group-hover:text-[#e4b295]">
                                        {{ $watch['tag'] }}
                                    </span>
                                </div>

                                <!-- Center Watch Showcase (With Signature 3D Pop-Out Breakout Effect on Hover) -->
                                <div
                                    class="relative w-full h-[250px] my-1 flex items-center justify-center overflow-visible pointer-events-none">

                                    <!-- Soft Ambient Radial Glow Behind the Watch on Hover -->
                                    <div
                                        class="absolute w-[200px] h-[200px] rounded-full bg-[#4fd1c5]/0 group-hover:bg-[#4fd1c5]/15 blur-2xl transition-all duration-700 ease-out pointer-events-none">
                                    </div>

                                    <img src="{{ $watch['image'] }}" alt="{{ $watch['name'] }}"
                                        class="veltra-watch-img relative z-30 w-auto h-[205px] max-w-none object-contain" />
                                </div>

                                <!-- Bottom Content Area -->
                                <div class="relative z-20 pt-2 flex flex-col justify-end">

                                    <!-- Watch Title -->
                                    <h3
                                        class="text-white font-medium text-[17px] sm:text-[18px] tracking-wide group-hover:text-white transition-colors truncate">
                                        {{ $watch['name'] }}
                                    </h3>

                                    <!-- Movement / Dimension Specs -->
                                    <p class="text-[#7c9aa0] text-xs font-light tracking-wide mt-0.5 truncate">
                                        {{ $watch['subtitle'] }}
                                    </p>

                                    <!-- Price Tag -->
                                    <div class="text-[#d8dee2] text-[15px] sm:text-[16px] font-semibold tracking-tight mt-2.5">
                                        {{ $watch['price'] }}
                                    </div>

                                    <!-- Interactive Bottom Controls Bar (Color Swatches & Add-to-Cart) -->
                                    <div class="flex items-center justify-between mt-3.5 pt-1">

                                        <!-- Color Swatch Selectors -->
                                        <div class="flex items-center gap-2">
                                            @foreach ($watch['colors'] as $cIndex => $color)
                                                <button type="button" @click.stop="selectedColor = {{ $cIndex }}"
                                                    class="w-3.5 h-3.5 rounded-full transition-all duration-300 relative flex items-center justify-center cursor-pointer"
                                                    :class="selectedColor === {{ $cIndex }} ?
                                                        'ring-2 ring-offset-2 ring-[#4fd1c5] ring-offset-[#0d2124] scale-110' :
                                                        'opacity-70 hover:opacity-100 hover:scale-105'"
                                                    style="background-color: {{ $color['bg'] }}; border: 1px solid {{ $color['border'] }};"
                                                    title="Select color variant">
                                                </button>
                                            @endforeach
                                        </div>

                                        <!-- Cart Button with Subtle Teal Glass Style -->
                                        <button type="button"
                                            class="w-9 h-9 rounded-xl flex items-center justify-center 
                                                       bg-[#14282c] border border-[#2d565e] text-[#65a396] 
                                                       hover:bg-[#65a396] hover:text-[#0b1416] hover:border-[#65a396]
                                                       active:scale-95 transition-all duration-300 shadow-md group/btn cursor-pointer"
                                            title="Add to cart">
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
            class="flex absolute -left-2 sm:-left-3 lg:-left-5 top-1/2 -translate-y-1/2 z-40 w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#d59a7a]/50 bg-[#0d2124]/95 backdrop-blur-md hover:bg-[#153237] hover:border-[#4fd1c5] hover:shadow-[0_0_20px_rgba(79,209,197,0.4)] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 items-center justify-center cursor-pointer shadow-2xl active:scale-95 group"
            title="Previous Watch"
            aria-label="Previous Watch">
            <svg class="w-5 h-5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button @click="next()" type="button"
            class="flex absolute -right-2 sm:-right-3 lg:-right-5 top-1/2 -translate-y-1/2 z-40 w-11 h-11 sm:w-12 sm:h-12 rounded-full border border-[#d59a7a]/50 bg-[#0d2124]/95 backdrop-blur-md hover:bg-[#153237] hover:border-[#4fd1c5] hover:shadow-[0_0_20px_rgba(79,209,197,0.4)] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 items-center justify-center cursor-pointer shadow-2xl active:scale-95 group"
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

</section>
