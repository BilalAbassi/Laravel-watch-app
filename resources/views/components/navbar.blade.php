<header class="grid grid-cols-[1fr_auto_1fr] items-start w-full px-6 lg:px-12 bg-transparent select-none border-t border-white/5">
    {{-- Left: Brand Logo & Wordmark --}}
    <div class="flex items-center justify-self-start pt-[18px]">
        <a href="/" class="inline-flex items-center gap-4 no-underline transition-opacity duration-300 hover:opacity-90">
            <svg class="block shrink-0 drop-shadow-[0_0_8px_rgba(110,167,151,0.3)]" width="22" height="20" viewBox="0 0 24 22" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <!-- Outer Inverted Triangle -->
                <path d="M2.5 3.5H21.5L12 20.5L2.5 3.5Z" stroke="#6ea797" stroke-width="1.9" stroke-linejoin="round" />
                <!-- Inner Geometric Horizontal Divider -->
                <line x1="6.2" y1="8.5" x2="17.8" y2="8.5" stroke="#6ea797" stroke-width="1.8"
                    stroke-linecap="round" />
            </svg>
            <span class="font-['Montserrat',sans-serif] text-[14.5px] font-normal tracking-[0.35em] lg:tracking-[0.46em] text-[#cdd2d6] uppercase">VELTRA</span>
        </a>
    </div>

    {{-- Center: Sculpted Metallic Tab with Navigation Links --}}

    <x-center-navbar />

    {{-- Right: Pill-Shaped Glass Dock with Icons --}}
    <div class="flex items-center justify-end justify-self-end pt-[13px]">
        <div class="inline-flex items-center gap-5 px-[18px] py-2 bg-[#1e242a]/70 backdrop-blur-[14px] border border-white/10 rounded-full shadow-[inset_0_1px_1px_rgba(255,255,255,0.08),0_6px_18px_rgba(0,0,0,0.45)] transition-colors duration-300 hover:border-white/15">
            <!-- Search Icon -->
            <button type="button" class="inline-flex items-center justify-center bg-transparent border-none p-0 m-0 cursor-pointer outline-none transition-transform duration-200 hover:scale-110 group/btn" aria-label="Search">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" class="block stroke-[#9ba2a8] group-hover/btn:stroke-white transition-colors"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="10.5" cy="10.5" r="7.5"></circle>
                    <line x1="21" y1="21" x2="16" y2="16"></line>
                </svg>
            </button>

            <!-- User / Profile Icon -->
            <button type="button" class="inline-flex items-center justify-center bg-transparent border-none p-0 m-0 cursor-pointer outline-none transition-transform duration-200 hover:scale-110 group/btn" aria-label="User Account">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" class="block stroke-[#9ba2a8] group-hover/btn:stroke-white transition-colors"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </button>

            <!-- Shopping Bag Icon with Mint Badge -->
            <button type="button" class="inline-flex items-center justify-center bg-transparent border-none p-0 m-0 cursor-pointer outline-none transition-transform duration-200 hover:scale-110 group/btn" aria-label="Cart">
                <svg width="24" height="24" viewBox="0 0 28 28" fill="none" class="block"
                    xmlns="http://www.w3.org/2000/svg">
                    <!-- Rose Gold / Bronze Bag Handle -->
                    <path d="M10.5 8.5V7a3.5 3.5 0 0 1 7 0v1.5" class="stroke-[#cb987e] group-hover/btn:stroke-[#e4ab91] transition-colors" stroke-width="1.6"
                        stroke-linecap="round" />
                    <!-- Rose Gold / Bronze Bag Body -->
                    <path d="M6 9H22L23.5 23.5H4.5L6 9Z" class="stroke-[#cb987e] group-hover/btn:stroke-[#e4ab91] transition-colors" stroke-width="1.6" stroke-linejoin="round" />
                    <!-- Mint Badge Circle -->
                    <circle cx="22" cy="6.5" r="5" fill="#5ea391" />
                    <!-- Badge Number -->
                    <text x="22" y="9.4" fill="#0d1416" font-family="'Inter', sans-serif" font-size="9"
                        font-weight="700" text-anchor="middle">2</text>
                </svg>
            </button>
        </div>
    </div>
</header>
