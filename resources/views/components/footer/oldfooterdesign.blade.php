<footer class="relative w-full  mx-auto px-4 sm:px-6 lg:px-8 mt-14 sm:mt-20 mb-16 select-none font-['Inter',sans-serif]">

    <style>
        /* Main Footer Container with Luminous Antique Bronze & Copper Horology Background */
        .veltra-footer-wrapper {
            background-color: #1a0f09;
            background-image:
                radial-gradient(ellipse 95% 45% at 50% 12%, rgba(226, 166, 137, 0.22) 0%, transparent 70%),
                radial-gradient(ellipse 85% 50% at 50% 88%, rgba(180, 114, 84, 0.18) 0%, transparent 65%),
                linear-gradient(180deg, rgba(25, 14, 9, 0.38) 0%, rgba(18, 10, 6, 0.52) 45%, rgba(12, 6, 4, 0.72) 100%),
                url('{{ asset('images/footer/bronze_bg.jpg') }}');
            background-size: cover;
            background-position: center top;
            border: 1.5px solid rgba(212, 155, 126, 0.42);
            box-shadow:
                0 30px 70px -10px rgba(0, 0, 0, 0.95),
                0 10px 28px -4px rgba(0, 0, 0, 0.75),
                inset 0 1.5px 2px 0 rgba(255, 230, 215, 0.3),
                inset 0 -1.5px 2px 0 rgba(0, 0, 0, 0.85);
        }

        /* Sculpted Upper Category Card Cartouche Panel */
        .veltra-category-cartouche {
            position: relative;
            background-color: #140b07;
            background-image:
                radial-gradient(ellipse 90% 70% at 50% 30%, rgba(85, 46, 28, 0.5) 0%, transparent 75%),
                linear-gradient(180deg, rgba(24, 13, 8, 0.62) 0%, rgba(16, 9, 5, 0.76) 55%, rgba(10, 5, 3, 0.9) 100%),
                url('{{ asset('images/footer/bronze_bg.jpg') }}');
            background-size: cover;
            background-position: center 25%;
            border: 1.5px solid rgba(212, 155, 126, 0.45);
            border-radius: 28px;
            box-shadow:
                0 22px 50px -6px rgba(0, 0, 0, 0.9),
                inset 0 1.5px 2px 0 rgba(255, 235, 220, 0.28),
                inset 0 -1.5px 2.5px 0 rgba(0, 0, 0, 0.85);
        }

        /* Decorative sculpted corner brackets on the cartouche */
        .veltra-cartouche-bracket-left,
        .veltra-cartouche-bracket-right {
            position: absolute;
            bottom: 30px;
            width: 24px;
            height: 48px;
            pointer-events-none;
        }

        .veltra-cartouche-bracket-left {
            left: -1px;
            border-left: 2px solid rgba(230, 175, 148, 0.6);
            border-bottom: 2px solid rgba(230, 175, 148, 0.6);
            border-bottom-left-radius: 12px;
        }

        .veltra-cartouche-bracket-right {
            right: -1px;
            border-right: 2px solid rgba(230, 175, 148, 0.6);
            border-bottom: 2px solid rgba(230, 175, 148, 0.6);
            border-bottom-right-radius: 12px;
        }

        /* Category Card Hover & Aesthetic */
        .veltra-card-item {
            background-color: #0d1215;
            border: 1px solid rgba(184, 126, 95, 0.38);
            box-shadow:
                0 14px 30px -4px rgba(0, 0, 0, 0.8),
                inset 0 1px 1px 0 rgba(255, 255, 255, 0.15);
            transition: all 0.45s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .veltra-card-item:hover {
            border-color: rgba(238, 185, 160, 0.85);
            transform: translateY(-4px);
            box-shadow:
                0 22px 45px -6px rgba(0, 0, 0, 0.95),
                0 0 28px -2px rgba(212, 155, 126, 0.3),
                inset 0 1px 2px 0 rgba(255, 255, 255, 0.3);
        }

        /* Luxury Curled Architectural Ribbon Banner */
        .veltra-ribbon-badge-wrap {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-9px);
            z-index: 30;
            filter: drop-shadow(0 14px 22px rgba(0, 0, 0, 0.88)) drop-shadow(0 3px 6px rgba(0, 0, 0, 0.6));
            transition: transform 0.35s ease, filter 0.35s ease;
        }

        .veltra-ribbon-badge-wrap:hover {
            transform: translateX(-50%) translateY(-11px) scale(1.02);
            filter: drop-shadow(0 18px 28px rgba(0, 0, 0, 0.95)) drop-shadow(0 0 24px rgba(226, 166, 137, 0.4));
        }
    </style>

    <!-- Main Footer Container -->
    <div class="veltra-footer-wrapper relative rounded-[28px] sm:rounded-[36px] overflow-hidden p-4 sm:p-7 lg:p-10">

        <!-- ========================================== -->
        <!-- UPPER SECTION: Find Your Style Categories -->
        <!-- ========================================== -->
        <div
            class="veltra-category-cartouche relative rounded-[24px] sm:rounded-[30px] p-6 sm:p-9 lg:p-11 pb-14 sm:pb-16 mb-11 sm:mb-14">

            <!-- Sculpted baroque corner bracket accents -->
            {{-- <div class="veltra-cartouche-bracket-left hidden sm:block"></div>
            <div class="veltra-cartouche-bracket-right hidden sm:block"></div> --}}

            <!-- Category Header -->
            <div class="mb-6 sm:mb-8">
                <!-- Eyebrow Tag -->
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-4 h-[1.5px] bg-[#d5a48e] inline-block"></span>
                    <span
                        class="text-[#d5a48e] uppercase tracking-[0.28em] text-[10.5px] sm:text-[11.5px] font-semibold">
                        SHOP BY CATEGORY
                    </span>
                </div>

                <!-- Title -->
                <h3
                    class="font-['Cormorant_Garamond',serif] italic font-normal text-[#faf0e6] text-3xl sm:text-4xl lg:text-[42px] leading-tight tracking-wide drop-shadow-[0_2px_12px_rgba(0,0,0,0.7)]">
                    Find Your Style
                </h3>
            </div>

            <!-- 4 Category Cards Grid -->
            <x-footer.category />



            <!-- "Save up to 25%" 3D Curled Architectural Ribbon Banner -->
            <a href="#sale" class="veltra-ribbon-badge-wrap group block cursor-pointer">
                <svg viewBox="0 0 520 88" class="w-[310px] sm:w-[380px] md:w-[440px] lg:w-[480px] h-auto block"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <!-- Satin Champagne Rose Gold Metallic Gradient for Front Body -->
                        <linearGradient id="body-metal-grad" x1="0%" y1="0%" x2="0%"
                            y2="100%">
                            <stop offset="0%" stop-color="#fff8f3" />
                            <stop offset="5%" stop-color="#f9e2d4" />
                            <stop offset="25%" stop-color="#ecc4af" />
                            <stop offset="60%" stop-color="#d7a189" />
                            <stop offset="85%" stop-color="#c08770" />
                            <stop offset="100%" stop-color="#9a6048" />
                        </linearGradient>

                        <!-- Left Wing Face Gradient -->
                        <linearGradient id="wing-left-metal" x1="0%" y1="0%" x2="100%"
                            y2="80%">
                            <stop offset="0%" stop-color="#fff9f5" />
                            <stop offset="35%" stop-color="#eec7b3" />
                            <stop offset="80%" stop-color="#c68f78" />
                            <stop offset="100%" stop-color="#9d624a" />
                        </linearGradient>

                        <!-- Right Wing Face Gradient -->
                        <linearGradient id="wing-right-metal" x1="100%" y1="0%" x2="0%"
                            y2="80%">
                            <stop offset="0%" stop-color="#fff9f5" />
                            <stop offset="35%" stop-color="#eec7b3" />
                            <stop offset="80%" stop-color="#c68f78" />
                            <stop offset="100%" stop-color="#9d624a" />
                        </linearGradient>

                        <!-- Left Fold Underside Shadow Gradient -->
                        <linearGradient id="fold-left-dark" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#5a2d19" />
                            <stop offset="50%" stop-color="#2a1208" />
                            <stop offset="100%" stop-color="#120602" />
                        </linearGradient>

                        <!-- Right Fold Underside Shadow Gradient -->
                        <linearGradient id="fold-right-dark" x1="100%" y1="0%" x2="0%"
                            y2="100%">
                            <stop offset="0%" stop-color="#5a2d19" />
                            <stop offset="50%" stop-color="#2a1208" />
                            <stop offset="100%" stop-color="#120602" />
                        </linearGradient>

                        <!-- Razor Sharp 1px Top Rim Specular Line -->
                        <linearGradient id="top-line-specular" x1="0%" y1="0%" x2="100%"
                            y2="0%">
                            <stop offset="0%" stop-color="#ffffff" stop-opacity="0.3" />
                            <stop offset="12%" stop-color="#ffffff" stop-opacity="0.95" />
                            <stop offset="50%" stop-color="#ffffff" stop-opacity="1" />
                            <stop offset="88%" stop-color="#ffffff" stop-opacity="0.95" />
                            <stop offset="100%" stop-color="#ffffff" stop-opacity="0.3" />
                        </linearGradient>
                    </defs>

                    <!-- Left Wing Background (Curled Ribbon Tail) -->
                    <path d="M 24,9 H 98 C 98,28 60,30 24,9 Z" fill="url(#wing-left-metal)" />
                    <!-- Left Wing Fold Dark Triangular Shadow -->
                    <path d="M 98,9 L 98,28 C 84,26 72,19 65,14 Z" fill="url(#fold-left-dark)" />

                    <!-- Right Wing Background (Curled Ribbon Tail) -->
                    <path d="M 496,9 H 422 C 422,28 460,30 496,9 Z" fill="url(#wing-right-metal)" />
                    <!-- Right Wing Fold Dark Triangular Shadow -->
                    <path d="M 422,9 L 422,28 C 436,26 448,19 455,14 Z" fill="url(#fold-right-dark)" />

                    <!-- Main Center Banner Body -->
                    <!-- Top: (98, 9) to (422, 9). Graceful Concave Scoop to Bottom: (174, 75.5) to (346, 75.5) -->
                    <path d="M 98,9
                             H 422
                             C 402,11 386,23 376,39
                             C 368,54 360,71 346,74.5
                             C 342,75.2 338,75.5 332,75.5
                             H 188
                             C 182,75.5 178,75.2 174,74.5
                             C 160,71 152,54 144,39
                             C 134,23 118,11 98,9
                             Z" fill="url(#body-metal-grad)" />

                    <!-- Top Sharp Specular Highlight Line Across Entire Banner -->
                    <line x1="24" y1="9" x2="496" y2="9"
                        stroke="url(#top-line-specular)" stroke-width="1.3" />

                    <!-- Subtle Specular Reflection Along Bottom Edge -->
                    <path d="M 180,75.5 H 340" stroke="#f8ddd0" stroke-opacity="0.55" stroke-width="1" />

                    <!-- Text: Save up to 25% (Roman Serif, Cormorant Garamond 600) -->
                    <text x="260" y="53" text-anchor="middle"
                        font-family="'Cormorant Garamond', Garamond, Georgia, serif" font-weight="600" font-size="36"
                        fill="#26130b" letter-spacing="0.012em">
                        Save up to 25%
                    </text>
                </svg>
            </a>

        </div>

        <!-- ========================================== -->
        <!-- LOWER SECTION: 5 Links Columns & Brand Info -->
        <!-- ========================================== -->
        <div class="pt-6 sm:pt-8 px-2 sm:px-4">

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-12 gap-8 lg:gap-6">

                <!-- 1. Brand Logo & Manifesto (lg:col-span-3) -->
                <div class="col-span-2 md:col-span-3 lg:col-span-3">
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <h2
                                class="font-['Cormorant_Garamond',serif] text-2xl sm:text-3xl text-[#faeee6] font-normal tracking-wide">
                                Veltra Logo
                            </h2>
                        </div>
                        <p
                            class="font-['Montserrat',sans-serif] text-[11px] sm:text-[11.5px] uppercase tracking-[0.22em] text-[#bca292] leading-[1.85] font-normal">
                            PRECISION CHRONOGRAPHS.<br>
                            BORN OF OBSIDIAN TEAL AND<br>
                            BRONZE MASTERY.
                        </p>
                    </div>
                </div>

                <!-- 2. Collections (lg:col-span-2 lg:col-start-4) -->
                <div class="col-span-1 md:col-span-1 lg:col-span-2 lg:col-start-4">
                    <h4
                        class="font-['Montserrat',sans-serif] text-[11px] uppercase tracking-[0.26em] font-semibold text-[#ddc1af] mb-4">
                        COLLECTIONS
                    </h4>
                    <ul class="space-y-2.5 text-[12.5px] sm:text-[13px] font-normal font-['Inter',sans-serif]">
                        <li>
                            <a href="#collection"
                                class="text-[#4fd1c5] hover:text-[#7ee2d7] transition-colors font-medium">
                                The Collection
                            </a>
                        </li>
                        <li>
                            <a href="#chronographs" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Chronographs
                            </a>
                        </li>
                        <li>
                            <a href="#dive-watches" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Dive Watches
                            </a>
                        </li>
                        <li>
                            <a href="#limited-editions" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Limited Editions
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- 3. Company (lg:col-span-2) -->
                <div class="col-span-1 md:col-span-1 lg:col-span-2">
                    <h4
                        class="font-['Montserrat',sans-serif] text-[11px] uppercase tracking-[0.26em] font-semibold text-[#ddc1af] mb-4">
                        COMPANY
                    </h4>
                    <ul class="space-y-2.5 text-[12.5px] sm:text-[13px] font-normal font-['Inter',sans-serif]">
                        <li>
                            <a href="#story" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Brand Story
                            </a>
                        </li>
                        <li>
                            <a href="#about" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                About Veltra
                            </a>
                        </li>
                        <li>
                            <a href="#journal" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Journal
                            </a>
                        </li>
                        <li>
                            <a href="#careers" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Careers
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- 4. Support (lg:col-span-2) -->
                <div class="col-span-1 md:col-span-1 lg:col-span-2">
                    <h4
                        class="font-['Montserrat',sans-serif] text-[11px] uppercase tracking-[0.26em] font-semibold text-[#ddc1af] mb-4">
                        SUPPORT
                    </h4>
                    <ul class="space-y-2.5 text-[12.5px] sm:text-[13px] font-normal font-['Inter',sans-serif]">
                        <li>
                            <a href="#support" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Contact Support
                            </a>
                        </li>
                        <li>
                            <a href="#faq" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                FAQ
                            </a>
                        </li>
                        <li>
                            <a href="#returns" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Returns & Exchanges
                            </a>
                        </li>
                        <li>
                            <a href="#warranty" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Warranty
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- 5. Connect & Legal (lg:col-span-3) -->
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <h4
                        class="font-['Montserrat',sans-serif] text-[11px] uppercase tracking-[0.26em] font-semibold text-[#ddc1af] mb-3.5">
                        CONNECT & LEGAL
                    </h4>

                    <!-- Social Media Icons Row with Clear Visibility -->
                    <div class="flex items-center gap-4 mb-4 text-[#d4b9a8]">
                        <!-- Instagram -->
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                            class="hover:text-[#ffffff] hover:scale-115 transition-all duration-200"
                            aria-label="Instagram">
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.8"
                                viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                                </rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <!-- Facebook -->
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                            class="hover:text-[#ffffff] hover:scale-115 transition-all duration-200"
                            aria-label="Facebook">
                            <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9.198 21.5h4v-8.01h3.604l.396-3.98h-4V7.5a1 1 0 0 1 1-1h3V2.5h-3.955C9.7 2.5 8 4.2 8 6.95v2.56H5v3.98h3v8.01h1.198z" />
                            </svg>
                        </a>
                        <!-- X (Twitter) -->
                        <a href="https://x.com" target="_blank" rel="noopener noreferrer"
                            class="hover:text-[#ffffff] hover:scale-115 transition-all duration-200"
                            aria-label="X (Twitter)">
                            <svg class="w-[16px] h-[16px]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <!-- YouTube -->
                        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer"
                            class="hover:text-[#ffffff] hover:scale-115 transition-all duration-200"
                            aria-label="YouTube">
                            <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                        <!-- LinkedIn -->
                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer"
                            class="hover:text-[#ffffff] hover:scale-115 transition-all duration-200"
                            aria-label="LinkedIn">
                            <svg class="w-[16px] h-[16px]" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                            </svg>
                        </a>
                    </div>

                    <!-- Legal Links -->
                    <ul class="space-y-2 text-[12.5px] font-normal font-['Inter',sans-serif]">
                        <li>
                            <a href="#privacy" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Privacy Policy
                            </a>
                        </li>
                        <li>
                            <a href="#terms" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Terms of Service
                            </a>
                        </li>
                        <li>
                            <a href="#shipping" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Shipping Info
                            </a>
                        </li>
                        <li>
                            <a href="#cookies" class="text-[#bca292] hover:text-[#faeee6] transition-colors">
                                Cookie Policy
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- ========================================== -->
            <!-- SUB-BOTTOM BAR: Divider & Bottom Row       -->
            <!-- ========================================== -->
            <div class="w-full h-[1px] bg-gradient-to-r from-transparent via-[#b58066]/40 to-transparent my-7 sm:my-8">
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-5 text-xs text-[#a98f7e] pb-2">

                <!-- Left: Veltra Wordmark & Chevron Logo -->
                <div class="flex items-center gap-3">
                    <svg width="22" height="20" viewBox="0 0 24 22" fill="none"
                        class="shrink-0 drop-shadow-[0_0_6px_rgba(212,155,126,0.35)]">
                        <path d="M2.5 3.5H21.5L12 20.5L2.5 3.5Z" stroke="#b58066" stroke-width="1.8"
                            stroke-linejoin="round" />
                        <line x1="6.2" y1="8.5" x2="17.8" y2="8.5" stroke="#b58066"
                            stroke-width="1.7" stroke-linecap="round" />
                    </svg>
                    <span
                        class="font-['Montserrat',sans-serif] uppercase tracking-[0.38em] text-[13.5px] font-medium text-[#faeee6]">
                        VELTRA
                    </span>
                    <span class="text-[9.5px] tracking-[0.24em] text-[#9c7d6b] font-sans ml-1">
                        SWISS HOROLOGY
                    </span>
                </div>

                <!-- Center: Site Map & Store Locator -->
                <div class="flex items-center gap-7">
                    <a href="#sitemap"
                        class="text-[#bca292] hover:text-[#faeee6] transition-colors text-[12.5px] tracking-wide">
                        Site Map
                    </a>
                    <a href="#store-locator"
                        class="text-[#bca292] hover:text-[#faeee6] transition-colors text-[12.5px] tracking-wide">
                        Store Locator
                    </a>
                </div>

                <!-- Center-Right: Secondary Micro Social Icons -->
                <div class="flex items-center gap-4 text-[#d4b9a8]">
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                        class="hover:text-[#ffffff] transition-colors" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                        class="hover:text-[#ffffff] transition-colors" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9.198 21.5h4v-8.01h3.604l.396-3.98h-4V7.5a1 1 0 0 1 1-1h3V2.5h-3.955C9.7 2.5 8 4.2 8 6.95v2.56H5v3.98h3v8.01h1.198z" />
                        </svg>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer"
                        class="hover:text-[#ffffff] transition-colors" aria-label="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </a>
                    <a href="https://x.com" target="_blank" rel="noopener noreferrer"
                        class="hover:text-[#ffffff] transition-colors" aria-label="X (Twitter)">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                    </a>
                </div>

                <!-- Right: Copyright Notice -->
                <div class="text-[#967968] text-[11.5px] tracking-wide font-normal">
                    © 2025 Veltra. All rights reserved.
                </div>

            </div>

        </div>

    </div>

</footer>
