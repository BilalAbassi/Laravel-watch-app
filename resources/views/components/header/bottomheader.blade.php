<div class="relative w-full mt-4 lg:mt-6 z-20">

    <style>
        .feature-card-chassis {
            background: linear-gradient(145deg, 
                rgba(213, 154, 122, 0.28) 0%, 
                rgba(79, 209, 197, 0.12) 35%, 
                rgba(255, 255, 255, 0.05) 70%, 
                rgba(213, 154, 122, 0.22) 100%
            );
            box-shadow: 
                0 14px 28px -6px rgba(0, 0, 0, 0.85),
                0 4px 12px -2px rgba(0, 0, 0, 0.6);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .feature-card-chassis:hover {
            transform: translateY(-2px);
            background: linear-gradient(145deg, 
                rgba(213, 154, 122, 0.42) 0%, 
                rgba(79, 209, 197, 0.22) 35%, 
                rgba(255, 255, 255, 0.09) 70%, 
                rgba(213, 154, 122, 0.35) 100%
            );
            box-shadow: 
                0 20px 36px -6px rgba(0, 0, 0, 0.92),
                0 0 20px rgba(213, 154, 122, 0.16);
        }

        .feature-card-inner {
            background-color: #11171b;
            background-image: 
                /* Subtle radial glow from the bronze icon */
                radial-gradient(circle at 22% 50%, rgba(79, 209, 197, 0.07) 0%, rgba(213, 154, 122, 0.05) 30%, transparent 65%),
                /* Dark charcoal to deep midnight-teal subtle gradient */
                linear-gradient(175deg, #182329 0%, #121a1f 48%, #0d1316 100%);
            box-shadow: 
                inset 0 1px 1px 0 rgba(255, 255, 255, 0.14),
                inset 0 -1.5px 2px 0 rgba(0, 0, 0, 0.7);
        }

        .feature-card-inner:hover {
            background-image: 
                radial-gradient(circle at 22% 50%, rgba(79, 209, 197, 0.1) 0%, rgba(213, 154, 122, 0.08) 35%, transparent 65%),
                linear-gradient(175deg, #1c2930 0%, #141c22 48%, #0f1519 100%);
        }
    </style>

    <!-- 4 Feature Cards Grid: 1 col on mobile, 2 on tablet, 4 on desktop -->
    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 xl:gap-4.5">

        <!-- Card 1: Free Shipping -->
        <div class="group relative p-[1.5px] rounded-[22px] feature-card-chassis">
            <div class="feature-card-inner w-full h-[84px] sm:h-[88px] rounded-[20.5px] border border-white/[0.06] px-4.5 sm:px-5 flex items-center gap-4 transition-all duration-300">
                <!-- Bronze Outline Icon -->
                <div class="flex-shrink-0 text-[#d59a7a] group-hover:text-[#e5ae8f] transition-colors duration-300">
                    <svg class="w-8 h-8 sm:w-[34px] sm:h-[34px]" viewBox="0 0 34 30" fill="none" stroke="currentColor"
                        stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
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
                <!-- Content -->
                <div class="flex flex-col justify-center min-w-0">
                    <span class="font-['Montserrat',sans-serif] font-medium text-[13.5px] sm:text-[14px] text-[#f1f5f9] tracking-[0.015em] leading-tight truncate">
                        Free Shipping
                    </span>
                    <span class="font-['Inter',sans-serif] text-[11px] sm:text-[11.5px] text-[#86949e] font-light leading-tight mt-1 truncate">
                        On orders over Rs. 1,800
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 2: 2 Years Warranty -->
        <div class="group relative p-[1.5px] rounded-[22px] feature-card-chassis">
            <div class="feature-card-inner w-full h-[84px] sm:h-[88px] rounded-[20.5px] border border-white/[0.06] px-4.5 sm:px-5 flex items-center gap-4 transition-all duration-300">
                <!-- Shield Icon -->
                <div class="flex-shrink-0 text-[#d59a7a] group-hover:text-[#e5ae8f] transition-colors duration-300">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        <path d="M9 12l2 2 4-4" />
                    </svg>
                </div>
                <!-- Content -->
                <div class="flex flex-col justify-center min-w-0">
                    <span class="font-['Montserrat',sans-serif] font-medium text-[13.5px] sm:text-[14px] text-[#f1f5f9] tracking-[0.015em] leading-tight truncate">
                        2 Years Warranty
                    </span>
                    <span class="font-['Inter',sans-serif] text-[11px] sm:text-[11.5px] text-[#86949e] font-light leading-tight mt-1 truncate">
                        Quality assured
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 3: Easy Returns -->
        <div class="group relative p-[1.5px] rounded-[22px] feature-card-chassis">
            <div class="feature-card-inner w-full h-[84px] sm:h-[88px] rounded-[20.5px] border border-white/[0.06] px-4.5 sm:px-5 flex items-center gap-4 transition-all duration-300">
                <!-- Refresh Loop Arrow Icon -->
                <div class="flex-shrink-0 text-[#d59a7a] group-hover:text-[#e5ae8f] transition-colors duration-300">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-2.64-6.36L21 8" />
                        <polyline points="21 3 21 8 16 8" />
                    </svg>
                </div>
                <!-- Content -->
                <div class="flex flex-col justify-center min-w-0">
                    <span class="font-['Montserrat',sans-serif] font-medium text-[13.5px] sm:text-[14px] text-[#f1f5f9] tracking-[0.015em] leading-tight truncate">
                        Easy Returns
                    </span>
                    <span class="font-['Inter',sans-serif] text-[11px] sm:text-[11.5px] text-[#86949e] font-light leading-tight mt-1 truncate">
                        Within 7 days
                    </span>
                </div>
            </div>
        </div>

        <!-- Card 4: 24/7 Support -->
        <div class="group relative p-[1.5px] rounded-[22px] feature-card-chassis">
            <div class="feature-card-inner w-full h-[84px] sm:h-[88px] rounded-[20.5px] border border-white/[0.06] px-4.5 sm:px-5 flex items-center gap-4 transition-all duration-300">
                <!-- Headset Icon -->
                <div class="flex-shrink-0 text-[#d59a7a] group-hover:text-[#e5ae8f] transition-colors duration-300">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 14v-2a8 8 0 1 1 16 0v2" />
                        <rect x="2" y="14" width="4" height="6" rx="1.5" />
                        <rect x="18" y="14" width="4" height="6" rx="1.5" />
                        <path d="M18 19a4 4 0 0 1-4 3h-2" />
                        <circle cx="11" cy="22" r="1" fill="currentColor" />
                    </svg>
                </div>
                <!-- Content -->
                <div class="flex flex-col justify-center min-w-0">
                    <span class="font-['Montserrat',sans-serif] font-medium text-[13.5px] sm:text-[14px] text-[#f1f5f9] tracking-[0.015em] leading-tight truncate">
                        24/7 Support
                    </span>
                    <span class="font-['Inter',sans-serif] text-[11px] sm:text-[11.5px] text-[#86949e] font-light leading-tight mt-1 truncate">
                        We're here to help
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
