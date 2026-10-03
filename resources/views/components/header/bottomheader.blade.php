<div class="relative w-full mt-2 lg:mt-4 z-20">

    <style>
        .veltra-card-outer {
            background: linear-gradient(180deg, #2b333a 0%, #1c2126 35%, #111417 100%);
            box-shadow: 
                0 14px 32px -4px rgba(0, 0, 0, 0.75),
                0 4px 12px -2px rgba(0, 0, 0, 0.5),
                inset 0 1px 1px 0 rgba(255, 255, 255, 0.22),
                inset 0 -1px 1px 0 rgba(0, 0, 0, 0.6);
        }

        .veltra-card-outer:hover {
            box-shadow: 
                0 18px 38px -4px rgba(0, 0, 0, 0.85),
                0 6px 16px -2px rgba(0, 0, 0, 0.6),
                inset 0 1px 1.5px 0 rgba(255, 255, 255, 0.32),
                inset 0 -1px 1px 0 rgba(0, 0, 0, 0.7);
        }

        .veltra-card-metal {
            background-color: #1a2025;
            background-image: 
                /* 1. Ultra-fine subtle concentric micro-grooves (anisotropic brushed texture) */
                repeating-radial-gradient(circle at 26% 50%, 
                    rgba(255, 255, 255, 0.022) 0px, 
                    rgba(255, 255, 255, 0.022) 1px, 
                    transparent 1.2px, 
                    transparent 2.8px
                ),
                /* 2. Soft radial glow centered around the icon */
                radial-gradient(ellipse 70% 90% at 26% 50%, 
                    rgba(82, 98, 112, 0.42) 0%, 
                    rgba(44, 53, 62, 0.26) 38%, 
                    rgba(22, 27, 32, 0.65) 75%,
                    rgba(14, 17, 20, 0.92) 100%
                ),
                /* 3. Subtle anisotropic specular sheen reflections */
                conic-gradient(from 15deg at 26% 50%, 
                    #151a1e 0deg, 
                    #34404a 30deg, 
                    #1d242a 65deg, 
                    #14181c 120deg, 
                    #303c46 195deg, 
                    #1c2328 245deg, 
                    #13171b 300deg, 
                    #34404a 340deg, 
                    #151a1e 360deg
                );
        }

        .veltra-card-metal:hover {
            background-image: 
                repeating-radial-gradient(circle at 26% 50%, 
                    rgba(255, 255, 255, 0.032) 0px, 
                    rgba(255, 255, 255, 0.032) 1px, 
                    transparent 1.2px, 
                    transparent 2.8px
                ),
                radial-gradient(ellipse 75% 95% at 26% 50%, 
                    rgba(98, 116, 132, 0.48) 0%, 
                    rgba(52, 63, 73, 0.32) 40%, 
                    rgba(24, 30, 36, 0.6) 75%,
                    rgba(15, 19, 23, 0.88) 100%
                ),
                conic-gradient(from 20deg at 26% 50%, 
                    #171c21 0deg, 
                    #3c4955 30deg, 
                    #212830 65deg, 
                    #15191e 120deg, 
                    #374551 195deg, 
                    #1e262c 245deg, 
                    #14181c 300deg, 
                    #3c4955 340deg, 
                    #171c21 360deg
                );
        }
    </style>

    <!-- 4 Independent Metallic Cards Grid -->
    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-3.5 xl:gap-4">

        <!-- Card 1: Free Shipping -->
        <div class="group relative p-[4.5px] rounded-[22px] veltra-card-outer border border-[#262e36]/90 transition-all duration-300 hover:-translate-y-0.5">
            <div class="veltra-card-metal w-full h-[82px] sm:h-[86px] rounded-[17px] border border-[#222930] shadow-[inset_0_1.5px_3px_0_rgba(0,0,0,0.7),inset_0_-1px_1px_0_rgba(255,255,255,0.05)] px-4 sm:px-5 flex items-center gap-4 transition-all duration-300">
                <!-- Icon with motion lines -->
                <div class="flex-shrink-0 text-[#c99b82] group-hover:text-[#dcad93] transition-colors">
                    <svg class="w-8 h-8 sm:w-9 sm:h-9" viewBox="0 0 34 30" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Speed lines -->
                        <line x1="2" y1="10" x2="6.5" y2="10" />
                        <line x1="0.5" y1="14.5" x2="8" y2="14.5" />
                        <line x1="2" y1="19" x2="6.5" y2="19" />
                        <!-- Truck trailer -->
                        <rect x="9" y="6" width="14" height="13.5" rx="1.5" />
                        <!-- Cabin -->
                        <path d="M23 11h4.5l3 4.5v4h-7.5" />
                        <!-- Wheels -->
                        <circle cx="13.5" cy="22.5" r="2.5" />
                        <circle cx="26.5" cy="22.5" r="2.5" />
                    </svg>
                </div>
                <!-- Text -->
                <div class="flex flex-col justify-center min-w-0">
                    <span class="text-[#c99b82] group-hover:text-[#dcad93] font-medium text-[13.5px] sm:text-[14px] leading-tight tracking-[0.015em] transition-colors truncate">Free Shipping</span>
                    <span class="text-[#868f98] text-[11px] sm:text-[11.5px] leading-tight mt-1 truncate">On all orders over Rs. 1000</span>
                </div>
            </div>
        </div>

        <!-- Card 2: 2 Years Warranty -->
        <div class="group relative p-[4.5px] rounded-[22px] veltra-card-outer border border-[#262e36]/90 transition-all duration-300 hover:-translate-y-0.5">
            <div class="veltra-card-metal w-full h-[82px] sm:h-[86px] rounded-[17px] border border-[#222930] shadow-[inset_0_1.5px_3px_0_rgba(0,0,0,0.7),inset_0_-1px_1px_0_rgba(255,255,255,0.05)] px-4 sm:px-5 flex items-center gap-4 transition-all duration-300">
                <!-- Shield Icon -->
                <div class="flex-shrink-0 text-[#c99b82] group-hover:text-[#dcad93] transition-colors">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        <path d="M9 12l2 2 4-4" />
                    </svg>
                </div>
                <!-- Text -->
                <div class="flex flex-col justify-center min-w-0">
                    <span class="text-[#c99b82] group-hover:text-[#dcad93] font-medium text-[13.5px] sm:text-[14px] leading-tight tracking-[0.015em] transition-colors truncate">2 Years Warranty</span>
                    <span class="text-[#868f98] text-[11px] sm:text-[11.5px] leading-tight mt-1 truncate">Full coverage, worry free</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Easy Returns -->
        <div class="group relative p-[4.5px] rounded-[22px] veltra-card-outer border border-[#262e36]/90 transition-all duration-300 hover:-translate-y-0.5">
            <div class="veltra-card-metal w-full h-[82px] sm:h-[86px] rounded-[17px] border border-[#222930] shadow-[inset_0_1.5px_3px_0_rgba(0,0,0,0.7),inset_0_-1px_1px_0_rgba(255,255,255,0.05)] px-4 sm:px-5 flex items-center gap-4 transition-all duration-300">
                <!-- Refresh Arrow Icon -->
                <div class="flex-shrink-0 text-[#c99b82] group-hover:text-[#dcad93] transition-colors">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-2.64-6.36L21 8" />
                        <polyline points="21 3 21 8 16 8" />
                    </svg>
                </div>
                <!-- Text -->
                <div class="flex flex-col justify-center min-w-0">
                    <span class="text-[#c99b82] group-hover:text-[#dcad93] font-medium text-[13.5px] sm:text-[14px] leading-tight tracking-[0.015em] transition-colors truncate">Easy Returns</span>
                    <span class="text-[#868f98] text-[11px] sm:text-[11.5px] leading-tight mt-1 truncate">Within 7 days</span>
                </div>
            </div>
        </div>

        <!-- Card 4: 24/7 Support -->
        <div class="group relative p-[4.5px] rounded-[22px] veltra-card-outer border border-[#262e36]/90 transition-all duration-300 hover:-translate-y-0.5">
            <div class="veltra-card-metal w-full h-[82px] sm:h-[86px] rounded-[17px] border border-[#222930] shadow-[inset_0_1.5px_3px_0_rgba(0,0,0,0.7),inset_0_-1px_1px_0_rgba(255,255,255,0.05)] px-4 sm:px-5 flex items-center gap-4 transition-all duration-300">
                <!-- Headset Icon -->
                <div class="flex-shrink-0 text-[#c99b82] group-hover:text-[#dcad93] transition-colors">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 14v-2a8 8 0 1 1 16 0v2" />
                        <rect x="2" y="14" width="4" height="6" rx="1.5" />
                        <rect x="18" y="14" width="4" height="6" rx="1.5" />
                        <path d="M18 19a4 4 0 0 1-4 3h-2" />
                        <circle cx="11" cy="22" r="1" fill="currentColor" />
                    </svg>
                </div>
                <!-- Text -->
                <div class="flex flex-col justify-center min-w-0">
                    <span class="text-[#c99b82] group-hover:text-[#dcad93] font-medium text-[13.5px] sm:text-[14px] leading-tight tracking-[0.015em] transition-colors truncate">24/7 Support</span>
                    <span class="text-[#868f98] text-[11px] sm:text-[11.5px] leading-tight mt-1 truncate">We're here to help</span>
                </div>
            </div>
        </div>

    </div>
</div>
