<section class="relative  mx-auto px-4 sm:px-6 lg:px-8 mt-16 sm:my-20 ">

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

    <!-- Main Banner Container with Layered Border and Rich Background -->
    <div
        class="veltra-story-banner relative rounded-[26px] sm:rounded-[30px]
         overflow-hidden flex flex-col lg:flex-row items-stretch min-h-[90vh]">

        <!-- Left Side: Cinematic Watchmaker & Caliber Movement Composite Image -->
        <div
            class="relative w-full lg:w-[48%] xl:w-[46%] min-h-[300px] sm:min-h-[360px] lg:min-h-[400px] overflow-hidden">
            <!-- Background Image -->
            <img src="{{ asset('images/make-banner.jpg') }}" alt="Veltra Swiss Watchmaking and In-House Caliber Precision"
                class="w-full h-full object-cover object-center lg:object-[45%_center] transition-transform duration-700 
                ease-out " />

            <!-- Soft Linear Fade Gradients to Seamlessly Blend into Dark Banner -->
            <div
                class="absolute inset-y-0 right-0 hidden lg:block w-36 bg-gradient-to-r from-transparent via-[#091518]/70 to-[#091518] pointer-events-none">
            </div>
            <div
                class="absolute inset-x-0 bottom-0 lg:hidden h-28 bg-gradient-to-t from-[#091518] via-[#091518]/80 to-transparent pointer-events-none">
            </div>

            <!-- Subtle Vignette Frame -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/20 pointer-events-none">
            </div>
        </div>

        <!-- Right Side: Story Content & Heritage Column -->
        <div
            class="relative z-10 flex-1 p-8 sm:p-10 lg:p-12 xl:p-14 flex flex-col xl:flex-row xl:items-center justify-between gap-8 xl:gap-10">

            <!-- Middle Text & Action Area -->
            <div class="flex-1 max-w-[520px]">

                <!-- Eyebrow Category Tag -->
                <div class="flex items-center gap-2 mb-3 sm:mb-4">
                    <span class="w-5 h-[1.5px] bg-[#65a396] inline-block"></span>
                    <span class="text-[#65a396] uppercase tracking-[0.3em] text-[11px] sm:text-xs font-semibold">
                        OUR STORY
                    </span>
                </div>

                <!-- Banner Heading -->
                <h2
                    class="text-3xl sm:text-4xl lg:text-[40px] xl:text-[42px] font-['Montserrat',sans-serif] font-normal text-white leading-[1.18] tracking-wide mb-4">
                    Built on Precision,<br>
                    <span
                        class="font-['Cormorant_Garamond',serif] italic font-normal text-[#d59a7a] tracking-wider text-4xl sm:text-5xl lg:text-[46px] drop-shadow-[0_0_15px_rgba(213,154,122,0.25)]">
                        Driven by Passion
                    </span>
                </h2>

                <!-- Story Paragraph -->
                <p class="text-[#889fa4] text-xs sm:text-sm font-light leading-relaxed mb-7 sm:mb-8">
                    At Veltra, we believe a watch is more than an accessory — it is a testament to precision engineering
                    and timeless craftsmanship. Combining modern architectural design with heritage Swiss watchmaking.
                </p>

                <!-- Button -->
                <a href="#story"
                    class="inline-flex items-center gap-3 px-6 sm:px-7 py-2.5 sm:py-3 rounded-full 
                          border border-[#325a62] bg-[#112428]/60 text-[#7eb8b1] 
                          hover:text-white hover:bg-[#65a396]/20 hover:border-[#65a396] 
                          active:scale-95 transition-all duration-300 group shadow-md">
                    <span class="text-xs sm:text-sm font-medium tracking-wide">
                        Our Story
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
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
                        EST.
                    </span>
                    <span
                        class="text-2xl sm:text-3xl font-light text-white tracking-widest font-['Montserrat',sans-serif]">
                        2026
                    </span>

                    <!-- Decorative Micro Divider -->
                    <div class="w-7 h-[1px] bg-white/20 my-3.5"></div>

                    <!-- Mission Subtitle -->
                    <div
                        class="text-[9.5px] tracking-[0.28em] text-[#7a9296] font-medium leading-[1.8] uppercase font-['Inter',sans-serif]">
                        CRAFTED<br>
                        FOR A<br>
                        BETTER<br>
                        TOMORROW
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>
