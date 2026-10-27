<section class="relative  mx-auto mt-28 ">


    <!-- Background Technical Horology Blueprint Lines -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden opacity-40 flex items-center justify-center">
        <svg viewBox="0 0 1400 700" fill="none" class="w-full h-full">
            <circle cx="660" cy="260" r="260" stroke="#38bdf8" stroke-width="0.5" stroke-dasharray="3 6"
                opacity="0.35" />
            <circle cx="660" cy="260" r="225" stroke="#4fd1c5" stroke-width="0.8" opacity="0.55" />
            <circle cx="660" cy="260" r="190" stroke="#5ea391" stroke-width="0.5" stroke-dasharray="6 4"
                opacity="0.3" />
            <circle cx="660" cy="260" r="155" stroke="#4fd1c5" stroke-width="0.75" opacity="0.45" />
            <circle cx="660" cy="260" r="110" stroke="#38bdf8" stroke-width="0.5" stroke-dasharray="2 4"
                opacity="0.35" />

            <!-- Degree Marks -->
            <g stroke="#4fd1c5" stroke-width="1" opacity="0.6">
                <line x1="885" y1="260" x2="897" y2="260" />
                <line x1="819" y1="419" x2="828" y2="428" />
                <line x1="660" y1="485" x2="660" y2="497" />
                <line x1="501" y1="419" x2="492" y2="428" />
                <line x1="435" y1="260" x2="423" y2="260" />
                <line x1="501" y1="101" x2="492" y2="92" />
                <line x1="660" y1="35" x2="660" y2="23" />
                <line x1="819" y1="101" x2="828" y2="92" />
            </g>

            <!-- Orthogonal Crosshairs -->
            <line x1="180" y1="260" x2="1220" y2="260" stroke="#38bdf8" stroke-width="0.5"
                stroke-dasharray="4 8" opacity="0.22" />

            <line x1="660" y1="0" x2="660" y2="550" stroke="#38bdf8" stroke-width="0.5"
                stroke-dasharray="4 8" opacity="0.22" />

            <!-- CAD Technical Frame -->
            <path d="M 500 60 L 820 60 L 850 90 L 850 430 L 820 460 L 500 460 L 470 430 L 470 90 Z" stroke="#4fd1c5"
                stroke-width="0.5" stroke-dasharray="8 6" opacity="0.3" />

            <!-- Corner Markers -->
            <path d="M 450 110 L 450 80 L 480 80" stroke="#4fd1c5" stroke-width="1.2" opacity="0.5" />
            <path d="M 840 80 L 870 80 L 870 110" stroke="#4fd1c5" stroke-width="1.2" opacity="0.5" />
            <path d="M 450 410 L 450 440 L 480 440" stroke="#4fd1c5" stroke-width="1.2" opacity="0.5" />
            <path d="M 840 440 L 870 440 L 870 410" stroke="#4fd1c5" stroke-width="1.2" opacity="0.5" />

            <!-- Bottom Accent -->
            <path d="M 520 640 A 180 180 0 0 1 800 640" stroke="#4fd1c5" stroke-width="0.75" stroke-dasharray="4 4"
                opacity="0.35" />
        </svg>
    </div>



    <!-- Main Hero Content -->
    <div class="relative z-10 flex flex-col lg:flex-row items-stretch mb-20">

        <!-- Left Panel -->
        <div class="relative w-full lg:w-[52%] xl:w-[51%] z-20 lg:h-full flex items-center pr-0 lg:pr-10"
            x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">

            <!-- Bronze Background -->
            <div class="absolute -inset-4 z-0 bg-gradient-to-br from-[#d59a7a] via-[#8c5a40] to-[#3a2012] rounded-3xl -rotate-3 scale-105 opacity-80"
                style="clip-path: polygon(0 0, 95% 0, 100% 5%, 100% 95%, 95% 100%, 0 100%);"></div>

            <!-- Dark Glass Panel -->
            <div class="relative z-10 w-full  lg:h-full bg-gradient-to-br from-[#1c2324] to-[#0f1416] p-10 shadow-2xl rounded-l-2xl border border-white/10 border-r-0 flex flex-col justify-center"
                style="clip-path: polygon(0 0, 85% 0, 100% 20%, 100% 80%, 85% 100%, 0 100%);" x-show="show"
                x-transition:enter="transition ease-out duration-1000"
                x-transition:enter-start="opacity-0 -translate-x-12"
                x-transition:enter-end="opacity-100 translate-x-0" x-cloak>

                <div class="">

                    <!-- Category -->
                    <div class="mb-4">
                        <span
                            class="text-[#65a396] uppercase tracking-[0.38em] text-[10px] sm:text-[11px] font-semibold">
                            TIME-DEDEFINED
                        </span>
                    </div>

                    <!-- Brand -->
                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-['Montserrat',sans-serif] font-medium text-white leading-[1.1] tracking-wide mb-3">
                        TIMEYRAH.<br>

                        <span class="text-3xl sm:text-4xl lg:text-[42px] text-[#d59a7a] font-bold tracking-tight">
                            PRECISION IN
                        </span>

                        <span
                            class="font-['Cormorant_Garamond',serif] italic font-normal text-[#65a396] text-4xl sm:text-5xl lg:text-5xl tracking-wide drop-shadow-[0_0_12px_rgba(101,163,150,0.5)]">
                            MOTION
                        </span>
                    </h1>

                    <!-- Subheading -->
                    <div class="text-[#e2e8f0] text-lg sm:text-xl font-light mb-5">
                        <p>More than Just a Watch.</p>
                        <p>Experience the Caliber.</p>
                    </div>

                    <!-- Description -->
                    <p class="text-[#8c96a0] text-xs sm:text-sm font-light leading-relaxed max-w-[340px] mb-8">
                        At TimeYrah, we believe a timepiece is more than an accessory — it is an uncompromising statement of precision, passion, and timeless horological art.
                    </p>

                    <!-- Button -->
                    <a href="#collection"
                        class="inline-flex items-center gap-3 px-6 py-2.5 rounded-full border border-[#d59a7a] text-[#d6dbe0] hover:text-white hover:bg-[#d59a7a]/15 transition-all duration-300 group">
                        <span class="text-xs sm:text-sm font-light tracking-wide">
                            Explore Collection
                        </span>

                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="group-hover:translate-x-1 transition-transform duration-300">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>

                </div>
            </div>
        </div>

        <!-- Center Watch -->
        <div
            class="relative lg:absolute lg:left-[47.5%]  lg:left-[60%] lg:top-[110%] 
            lg:-translate-x-1/2 lg:-translate-y-1/2 z-20 w-[260px] sm:w-[310px] xl:w-[380px] 
             mx-auto -mt-12 sm:-mt-20 lg:my-0 lg:mt-0 pointer-events-none transition-transform duration-500 hover:scale-105">

            <img src="{{ asset('hero-watch.png') }}" alt="TimeYrah Chronograph Watch"
                class="w-full h-auto object-contain drop-shadow-[0_26px_50px_rgba(0,0,0,0.95)] drop-shadow-[0_0_30px_rgba(79,209,197,0.14)]">
        </div>

        <!-- Right Panel -->
        <div
            class="hidden lg:block relative w-full lg:w-[38%] xl:w-[37%] h-[320px] sm:h-[380px] lg:h-[450px] my-auto lg:ml-auto rounded-[32px] overflow-hidden shadow-[0_25px_50px_rgba(0,0,0,0.7)] border border-white/10 group z-10 bg-[#121619]">

            <img src="{{ asset('watch1.jpg') }}" alt="TimeYrah In-House Caliber Movement"
                class="w-full h-full object-cover object-[52%_48%] group-hover:scale-105 transition-transform duration-700 ease-out" />

            <!-- Bevel -->
            <div
                class="absolute inset-0 rounded-[32px] pointer-events-none border border-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
            </div>

            <!-- Vignette -->
            <div
                class="absolute inset-0 pointer-events-none bg-gradient-to-t from-black/40 via-transparent to-black/20">
            </div>
        </div>

    </div>



    <!-- Bottom Features -->
    <x-header.bottomheader />

</section>
