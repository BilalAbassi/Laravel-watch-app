<section class="relative mx-auto px-4 sm:px-6 lg:px-8 mt-16 sm:my-20">

    <style>
        .veltra-story-banner {
            background-color: #091518;
            background-image:
                /* Subtle horizontal/vertical metal brushed texture on the right */
                repeating-linear-gradient(90deg,
                    rgba(255, 255, 255, 0.012) 0px,
                    rgba(255, 255, 255, 0.012) 1px,
                    transparent 1px,
                    transparent 4px),
                linear-gradient(175deg,
                    #12272b 0%,
                    #091619 50%,
                    #050c0e 100%);
            box-shadow:
                0 20px 48px -8px rgba(0, 0, 0, 0.85),
                0 4px 16px -2px rgba(0, 0, 0, 0.5),
                inset 0 1px 1.5px 0 rgba(255, 255, 255, 0.12),
                inset 0 -1px 1px 0 rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(79, 209, 197, 0.22);
        }
    </style>

    @php
        $slides = [
            [
                'tag' => 'OUR STORY',
                'title_line1' => 'Built on Precision,',
                'title_line2' => 'Driven by Passion',
                'description' =>
                    'At TimeYrah, we believe a watch is more than an accessory — it is a testament to precision engineering and timeless craftsmanship. Combining modern architectural design with heritage Swiss watchmaking.',
                'button_text' => 'Our Story',
                'button_link' => '#story',
                'image' => asset('images/make-banner.jpg'),
                'image_alt' => 'TimeYrah Swiss Watchmaking and In-House Caliber Precision',
                'stat_label' => 'EST.',
                'stat_val' => '2026',
                'stat_text' => 'CRAFTED<br>FOR A<br>BETTER<br>TOMORROW',
            ],
            [
                'tag' => 'HERITAGE CRAFT',
                'title_line1' => 'Damascus Steel,',
                'title_line2' => 'Bespoke Artistry',
                'description' =>
                    'Hand-forged Damascus wave patterns paired with alligator grain textures. Every TimeYrah timepiece is crafted with obsession over metallic grain, reflection, and tactile perfection.',
                'button_text' => 'Discover Craft',
                'button_link' => '#about',
                'image' => asset('images/timeYar.jpeg'),
                'image_alt' => 'TimeYrah Bespoke Damascus Steel and Horology Crest',
                'stat_label' => 'FINISH',
                'stat_val' => '100%',
                'stat_text' => 'HAND<br>CRAFTED<br>BESPOKE<br>EXCELLENCE',
            ],
            [
                'tag' => 'MECHANICAL MASTERY',
                'title_line1' => 'The Heartbeat of',
                'title_line2' => 'Mechanical Art',
                'description' =>
                    'Operating with chronometer-certified accuracy, anti-magnetic escapements, and sapphire crystal exhibition casebacks that celebrate the art of Swiss mechanical engineering.',
                'button_text' => 'View Calibers',
                'button_link' => '#collection',
                'image' => asset('watch1.jpg'),
                'image_alt' => 'TimeYrah In-House Caliber Movement and Balance Wheel',
                'stat_label' => 'BEAT',
                'stat_val' => '28.8K',
                'stat_text' => 'VPH<br>CHRONOMETER<br>CERTIFIED<br>CALIBER',
            ],
        ];
    @endphp

    <!-- Carousel Container -->
    <div x-data="{
        current: 0,
        total: {{ count($slides) }},
        timer: null,
        init() {
            this.startAutoplay();
        },
        startAutoplay() {
            this.timer = setInterval(() => {
                this.next();
            }, 6000);
        },
        stopAutoplay() {
            if (this.timer) clearInterval(this.timer);
        },
        next() {
            this.current = (this.current + 1) % this.total;
        },
        prev() {
            this.current = (this.current - 1 + this.total) % this.total;
        },
        goTo(index) {
            this.current = index;
        }
    }" @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()"
        class="veltra-story-banner relative rounded-[26px] sm:rounded-[30px] overflow-hidden flex flex-col justify-between">

        <!-- Slides Wrapper -->
        <div class="relative w-full">
            @foreach ($slides as $index => $slide)
                <div x-show="current === {{ $index }}" x-transition:enter="transition-all ease-out duration-600"
                    x-transition:enter-start="opacity-0 translate-x-6" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition-all ease-in duration-300 absolute inset-0 pointer-events-none"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-6"
                    class="w-full flex flex-col lg:flex-row items-stretch"
                    style="{{ $index === 0 ? '' : 'display: none;' }}">

                    <!-- Left Side: Cinematic Watchmaker & Caliber Movement Composite Image -->
                    <div
                        class="relative w-full lg:w-[48%] xl:w-[46%] sm:min-h-[360px] lg:min-h-[400px] max-h-[500px] overflow-hidden">
                        <!-- Background Image -->
                        <img src="{{ $slide['image'] }}" alt="{{ $slide['image_alt'] }}"
                            class="w-full h-full object-cover object-center lg:object-[45%_center] transition-transform duration-700 max-h-[500px] ease-out hover:scale-105" />

                        <!-- Soft Linear Fade Gradients to Seamlessly Blend into Dark Banner -->
                        <div
                            class="absolute inset-y-0 right-0 hidden lg:block w-36 bg-gradient-to-r from-transparent via-[#091518]/70 to-[#091518] pointer-events-none">
                        </div>
                        <div
                            class="absolute inset-x-0 bottom-0 lg:hidden h-28 bg-gradient-to-t from-[#091518] via-[#091518]/80 to-transparent pointer-events-none">
                        </div>

                        <!-- Subtle Vignette Frame -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/20 pointer-events-none">
                        </div>
                    </div>

                    <!-- Right Side: Story Content & Heritage Column -->
                    <div
                        class="relative z-10 flex-1 p-8 sm:p-10 lg:p-12 xl:p-14 max-h-[600px] flex flex-col xl:flex-row xl:items-center justify-between gap-8 xl:gap-10">

                        <!-- Middle Text & Action Area -->
                        <div class="flex-1 max-w-[520px]">

                            <!-- Eyebrow Category Tag -->
                            <div class="flex items-center gap-2 mb-3 sm:mb-4">
                                <span class="w-5 h-[1.5px] bg-[#65a396] inline-block"></span>
                                <span
                                    class="text-[#65a396] uppercase tracking-[0.3em] text-[11px] sm:text-xs font-semibold">
                                    {{ $slide['tag'] }}
                                </span>
                            </div>

                            <!-- Banner Heading -->
                            <h2
                                class="text-3xl sm:text-4xl lg:text-[40px] xl:text-[42px] font-['Montserrat',sans-serif] font-normal text-white leading-[1.18] tracking-wide mb-4">
                                {{ $slide['title_line1'] }}<br>
                                <span
                                    class="font-['Cormorant_Garamond',serif] italic font-normal text-[#d59a7a] tracking-wider text-4xl sm:text-5xl lg:text-[46px] drop-shadow-[0_0_15px_rgba(213,154,122,0.25)]">
                                    {{ $slide['title_line2'] }}
                                </span>
                            </h2>

                            <!-- Story Paragraph -->
                            <p class="text-[#889fa4] text-xs sm:text-sm font-light leading-relaxed mb-7 sm:mb-8">
                                {!! $slide['description'] !!}
                            </p>

                            <!-- Button -->
                            <a href="{{ $slide['button_link'] }}"
                                class="inline-flex items-center gap-3 px-6 sm:px-7 py-2.5 sm:py-3 rounded-full 
                                      border border-[#325a62] bg-[#112428]/60 text-[#7eb8b1] 
                                      hover:text-white hover:bg-[#65a396]/20 hover:border-[#65a396] 
                                      active:scale-95 transition-all duration-300 group shadow-md">
                                <span class="text-xs sm:text-sm font-medium tracking-wide">
                                    {{ $slide['button_text'] }}
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>

                        <!-- Far Right: Vertical Heritage / Establishment Stat Column -->
                        <div
                            class="hidden sm:flex items-center pt-4 xl:pt-0 border-t xl:border-t-0 xl:border-l border-[#4fd1c5]/40 xl:pl-8 2xl:pl-10">
                            <div class="flex flex-col">
                                <!-- Establishment Label & Year -->
                                <span class="text-[10px] tracking-[0.3em] font-medium text-[#65a396] uppercase mb-1">
                                    {{ $slide['stat_label'] }}
                                </span>
                                <span
                                    class="text-2xl sm:text-3xl font-light text-white tracking-widest font-['Montserrat',sans-serif]">
                                    {{ $slide['stat_val'] }}
                                </span>

                                <!-- Decorative Micro Divider -->
                                <div class="w-7 h-[1px] bg-white/20 my-3.5"></div>

                                <!-- Mission Subtitle -->
                                <div
                                    class="text-[9.5px] tracking-[0.28em] text-[#7a9296] font-medium leading-[1.8] uppercase font-['Inter',sans-serif]">
                                    {!! $slide['stat_text'] !!}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            @endforeach
        </div>

        <!-- Floating Left / Right Overlay Arrow Buttons -->
        <button @click="prev()" type="button"
            class="flex absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 sm:w-11 sm:h-11 rounded-full border border-[#d59a7a]/40 bg-[#061113]/80 backdrop-blur-md hover:bg-[#153237] hover:border-[#4fd1c5] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 items-center justify-center cursor-pointer shadow-xl active:scale-95 group"
            title="Previous Story Slide"
            aria-label="Previous Story Slide">
            <svg class="w-4 h-4 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button @click="next()" type="button"
            class="flex absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 sm:w-11 sm:h-11 rounded-full border border-[#d59a7a]/40 bg-[#061113]/80 backdrop-blur-md hover:bg-[#153237] hover:border-[#4fd1c5] text-[#d59a7a] hover:text-[#4fd1c5] transition-all duration-200 items-center justify-center cursor-pointer shadow-xl active:scale-95 group"
            title="Next Story Slide"
            aria-label="Next Story Slide">
            <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>

        <!-- Carousel Navigation Controls Bar (Bottom) -->
        <div
            class="relative z-20 flex items-center justify-between px-6 sm:px-10 py-3.5 border-t border-white/5 bg-[#061113]/70 backdrop-blur-md">
            <!-- Left: Slide Indicators (Pills / Bars) -->
            <div class="flex items-center gap-2">
                <template x-for="i in total" :key="i">
                    <button @click="goTo(i - 1)" type="button"
                        :class="current === (i - 1) ?
                            'w-7 sm:w-8 bg-gradient-to-r from-[#65a396] to-[#d59a7a]' :
                            'w-2 sm:w-2.5 bg-white/20 hover:bg-white/40'"
                        class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"
                        :aria-label="'Slide ' + i">
                    </button>
                </template>
                <!-- Slide Counter -->
                <span class="ml-3 font-['Inter',sans-serif] text-[11px] font-mono tracking-widest text-[#7a9296]">
                    <span class="text-[#d59a7a]" x-text="'0' + (current + 1)"></span> / <span
                        x-text="'0' + total"></span>
                </span>
            </div>

            <!-- Right: Arrow Controls -->
            <div class="flex items-center gap-2">
                <button @click="prev()" type="button"
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-white/10 bg-[#12272b]/70 hover:bg-[#65a396]/20 hover:border-[#65a396] text-[#7eb8b1] hover:text-white transition-all duration-200 flex items-center justify-center cursor-pointer active:scale-95"
                    aria-label="Previous Slide">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button @click="next()" type="button"
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-white/10 bg-[#12272b]/70 hover:bg-[#65a396]/20 hover:border-[#65a396] text-[#7eb8b1] hover:text-white transition-all duration-200 flex items-center justify-center cursor-pointer active:scale-95"
                    aria-label="Next Slide">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>

    </div>

</section>
