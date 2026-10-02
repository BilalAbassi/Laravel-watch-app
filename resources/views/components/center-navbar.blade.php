<div class="flex justify-center items-start justify-self-center mt-0">
    <div class="flex items-start justify-center leading-none">
        <svg class="block overflow-visible" viewBox="0 0 640 58" width="640" height="58" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <defs>
                <!-- Outer Copper / Rose Gold Bevel Wing Gradient -->
                <linearGradient id="copperWingGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#dd9c7c" />
                    <stop offset="18%" stop-color="#ca8666" />
                    <stop offset="50%" stop-color="#9a5a3f" />
                    <stop offset="82%" stop-color="#693722" />
                    <stop offset="100%" stop-color="#462214" />
                </linearGradient>

                <!-- Brushed Titanium / Gunmetal Body Vertical Gradient -->
                <linearGradient id="titaniumGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#8e969e" />
                    <stop offset="8%" stop-color="#6b7279" />
                    <stop offset="28%" stop-color="#484f55" />
                    <stop offset="60%" stop-color="#2d3338" />
                    <stop offset="88%" stop-color="#1e2225" />
                    <stop offset="100%" stop-color="#14171a" />
                </linearGradient>

                <!-- Metallic Center Anisotropic Sheen Gradient -->
                <linearGradient id="metallicSheenGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#ffffff" stop-opacity="0.01" />
                    <stop offset="22%" stop-color="#ffffff" stop-opacity="0.06" />
                    <stop offset="38%" stop-color="#ffffff" stop-opacity="0.18" />
                    <stop offset="50%" stop-color="#ffffff" stop-opacity="0.32" />
                    <stop offset="62%" stop-color="#ffffff" stop-opacity="0.18" />
                    <stop offset="78%" stop-color="#ffffff" stop-opacity="0.06" />
                    <stop offset="100%" stop-color="#ffffff" stop-opacity="0.01" />
                </linearGradient>

                <!-- Fine Horizontal Brushed Metal Texture -->
                <pattern id="brushedTexture" width="120" height="2" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="0.5" x2="120" y2="0.5" stroke="#ffffff"
                        stroke-width="0.6" stroke-opacity="0.04" />
                </pattern>

                <!-- Top Edge Metallic Highlight -->
                <linearGradient id="topHighlightGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="transparent" />
                    <stop offset="8%" stop-color="#8e969e" stop-opacity="0.4" />
                    <stop offset="30%" stop-color="#d4dbe2" stop-opacity="0.85" />
                    <stop offset="50%" stop-color="#ffffff" stop-opacity="1" />
                    <stop offset="70%" stop-color="#d4dbe2" stop-opacity="0.85" />
                    <stop offset="92%" stop-color="#8e969e" stop-opacity="0.4" />
                    <stop offset="100%" stop-color="transparent" />
                </linearGradient>

                <!-- Copper Flare Rim Highlight Left -->
                <linearGradient id="copperRimLeft" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#f5be9f" />
                    <stop offset="45%" stop-color="#cb8665" />
                    <stop offset="100%" stop-color="#733b24" />
                </linearGradient>

                <!-- Copper Flare Rim Highlight Right -->
                <linearGradient id="copperRimRight" x1="100%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#f5be9f" />
                    <stop offset="45%" stop-color="#cb8665" />
                    <stop offset="100%" stop-color="#733b24" />
                </linearGradient>

                <!-- Drop Shadow for the sculpted tab -->
                <filter id="tabDropShadow" x="-10%" y="0%" width="120%" height="150%"
                    filterUnits="userSpaceOnUse">
                    <feDropShadow dx="0" dy="8" stdDeviation="10" flood-color="#000000"
                        flood-opacity="0.75" />
                    <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#000000"
                        flood-opacity="0.5" />
                </filter>
            </defs>

            <!-- LAYER 1: Drop Shadow & Copper Flare Wings Underlay -->
            <g filter="url(#tabDropShadow)">
                <!-- Outer Copper Wing Foundation Path -->
                <path d="M 0 0
                            C 14 3, 25 12, 33 25
                            C 42 39, 50 51, 64 55
                            C 70 57, 76 58, 86 58
                            L 554 58
                            C 564 58, 570 57, 576 55
                            C 590 51, 598 39, 607 25
                            C 615 12, 626 3, 640 0
                            Z" fill="url(#copperWingGrad)" />
                <!-- Glowing Copper Rim Edges -->
                <path d="M 0 0 C 14 3, 25 12, 33 25 C 42 39, 50 51, 64 55" stroke="url(#copperRimLeft)"
                    stroke-width="1.8" stroke-linecap="round" />
                <path d="M 640 0 C 626 3, 615 12, 607 25 C 598 39, 590 51, 576 55" stroke="url(#copperRimRight)"
                    stroke-width="1.8" stroke-linecap="round" />
            </g>

            <!-- LAYER 2: Brushed Titanium Tab Body -->
            <!-- Curves smoothly inside copper flare, exposing the copper bevel shoulders -->
            <path d="M 20 0
                        C 30 5, 40 14, 48 27
                        C 56 40, 64 51, 76 55
                        C 82 57, 88 58, 98 58
                        L 542 58
                        C 552 58, 558 57, 564 55
                        C 576 51, 584 40, 592 27
                        C 600 14, 610 5, 620 0
                        Z" fill="url(#titaniumGrad)" />

            <!-- Metallic Anisotropic Sheen Overlay -->
            <path d="M 20 0
                        C 30 5, 40 14, 48 27
                        C 56 40, 64 51, 76 55
                        C 82 57, 88 58, 98 58
                        L 542 58
                        C 552 58, 558 57, 564 55
                        C 576 51, 584 40, 592 27
                        C 600 14, 610 5, 620 0
                        Z" fill="url(#metallicSheenGrad)" />

            <!-- Brushed Metal Texture Fill Overlay -->
            <path d="M 20 0
                        C 30 5, 40 14, 48 27
                        C 56 40, 64 51, 76 55
                        C 82 57, 88 58, 98 58
                        L 542 58
                        C 552 58, 558 57, 564 55
                        C 576 51, 584 40, 592 27
                        C 600 14, 610 5, 620 0
                        Z" fill="url(#brushedTexture)" />

            <!-- Fine Titanium Left & Right Bevel Edge Line -->
            <path d="M 20 0 C 30 5, 40 14, 48 27 C 56 40, 64 51, 76 55 C 82 57, 88 58, 98 58"
                stroke="rgba(255, 255, 255, 0.28)" stroke-width="1" />
            <path d="M 620 0 C 610 5, 600 14, 592 27 C 584 40, 576 51, 564 55 C 558 57, 552 58, 542 58"
                stroke="rgba(255, 255, 255, 0.18)" stroke-width="1" />

            <!-- Bottom Inset Accent Line -->
            <path d="M 98 58 L 542 58" stroke="#0e1113" stroke-width="1.2" />

            <!-- Top Edge Brushed Metallic Highlight Line -->
            <line x1="0" y1="0.8" x2="640" y2="0.8" stroke="url(#topHighlightGrad)"
                stroke-width="1.6" />

            <!-- LAYER 3: Navigation Links Embedded Inside ForeignObject -->
            <foreignObject x="0" y="0" width="640" height="58">
                <div xmlns="http://www.w3.org/1999/xhtml" class="flex items-center justify-center w-full h-full pointer-events-auto">
                    <nav class="flex items-center justify-center gap-10 px-[70px] h-full max-lg:gap-6 max-lg:px-[50px]">
                        <!-- Link 1: The Collection (Active) -->
                        <a href="#collection" class="inline-flex flex-col items-center justify-center cursor-pointer transition-all duration-300 pt-[3px] group active">
                            <span class="font-['Outfit','Inter',sans-serif] text-[14.5px] font-[450] text-[#9ba1a7] tracking-[0.015em] whitespace-nowrap transition-colors duration-300 group-hover:text-[#e2e6eb] group-[.active]:text-[#cb987e] max-lg:text-[13px]">The Collection</span>
                            <span class="block w-full h-[1.8px] bg-[#5e9585] rounded-[1px] mt-1 shadow-[0_0_6px_rgba(94,149,133,0.35)] group-[.active]:block hidden"></span>
                        </a>

                        <!-- Link 2: Brand Story -->
                        <a href="#story" class="inline-flex flex-col items-center justify-center cursor-pointer transition-all duration-300 pt-[3px] group">
                            <span class="font-['Outfit','Inter',sans-serif] text-[14.5px] font-[450] text-[#9ba1a7] tracking-[0.015em] whitespace-nowrap transition-colors duration-300 group-hover:text-[#e2e6eb] group-[.active]:text-[#cb987e] max-lg:text-[13px]">Brand Story</span>
                        </a>

                        <!-- Link 3: About -->
                        <a href="#about" class="inline-flex flex-col items-center justify-center cursor-pointer transition-all duration-300 pt-[3px] group">
                            <span class="font-['Outfit','Inter',sans-serif] text-[14.5px] font-[450] text-[#9ba1a7] tracking-[0.015em] whitespace-nowrap transition-colors duration-300 group-hover:text-[#e2e6eb] group-[.active]:text-[#cb987e] max-lg:text-[13px]">About</span>
                        </a>

                        <!-- Link 4: Journal -->
                        <a href="#journal" class="inline-flex flex-col items-center justify-center cursor-pointer transition-all duration-300 pt-[3px] group">
                            <span class="font-['Outfit','Inter',sans-serif] text-[14.5px] font-[450] text-[#9ba1a7] tracking-[0.015em] whitespace-nowrap transition-colors duration-300 group-hover:text-[#e2e6eb] group-[.active]:text-[#cb987e] max-lg:text-[13px]">Journal</span>
                        </a>
                    </nav>
                </div>
            </foreignObject>
        </svg>
    </div>
</div>
