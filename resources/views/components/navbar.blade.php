<header x-data="{ 
    mobileMenuOpen: false,
    brandsOpen: false,
    timer: null,
    openBrands() {
        clearTimeout(this.timer);
        this.brandsOpen = true;
    },
    closeBrands() {
        this.timer = setTimeout(() => {
            this.brandsOpen = false;
        }, 300);
    }
}"
    class="relative z-50 w-full flex items-center justify-between 
 mx-auto
lg:grid lg:grid-cols-[1fr_auto_1fr] lg:items-start px-6 lg:px-12 border-t border-white/5 border-b border-[#dd9c7c]/15 py-5">
    {{-- Left: Brand Logo & Wordmark --}}
    <div class="flex items-center justify-self-start pt-[14px]">
        <a href="/" class="flex items-center gap-3.5 hover:opacity-90 transition-opacity duration-300 group">
            <img src="{{ asset('images/timeyar-circle-emblem.png') }}" alt="TimeYrah Emblem"
                class="w-10 h-10 rounded-full border border-[#d59a7a]/50 shadow-[0_0_14px_rgba(213,154,122,0.3)] object-cover shrink-0 group-hover:scale-105 transition-transform duration-300">
            <div class="flex flex-col">
                <span
                    class="font-['Montserrat',sans-serif] text-[15px] uppercase font-normal tracking-[0.38em] lg:tracking-[0.45em] text-[#e8e4df]">
                    TIMEYRAH
                </span>

            </div>
        </a>
    </div>

    {{-- Center: Navigation (Desktop Only) --}}
    <div class="hidden lg:block">
        <x-center-navbar />
    </div>

    {{-- Right: Glass Dock --}}
    <div class="flex items-center justify-self-end pt-[13px]">
        <div
            class="flex items-center gap-5 px-[18px] py-2 bg-[#1e242a]/70 backdrop-blur-[14px] border border-white/10 rounded-full shadow-[inset_0_1px_1px_rgba(255,255,255,0.08),0_6px_18px_rgba(0,0,0,0.45)] hover:border-white/15 transition-colors duration-300">

            {{-- Search --}}
            <button type="button"
                class="flex items-center justify-center p-0 cursor-pointer transition-transform duration-200 hover:scale-110 group/btn"
                aria-label="Search">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                    class="stroke-[#9ba2a8] group-hover/btn:stroke-white transition-colors" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="10.5" cy="10.5" r="7.5" />
                    <line x1="21" y1="21" x2="16" y2="16" />
                </svg>
            </button>

            {{-- User --}}
            <button type="button"
                class="flex items-center justify-center p-0 cursor-pointer transition-transform duration-200 hover:scale-110 group/btn"
                aria-label="User Account">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                    class="stroke-[#9ba2a8] group-hover/btn:stroke-white transition-colors" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </button>

            {{-- Cart --}}
            <button type="button"
                class="flex items-center justify-center p-0 cursor-pointer transition-transform duration-200 hover:scale-110 group/btn"
                aria-label="Cart">
                <svg width="24" height="24" viewBox="0 0 28 28" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5 8.5V7a3.5 3.5 0 0 1 7 0v1.5"
                        class="stroke-[#cb987e] group-hover/btn:stroke-[#e4ab91] transition-colors" stroke-width="1.6"
                        stroke-linecap="round" />

                    <path d="M6 9H22L23.5 23.5H4.5L6 9Z"
                        class="stroke-[#cb987e] group-hover/btn:stroke-[#e4ab91] transition-colors" stroke-width="1.6"
                        stroke-linejoin="round" />

                    <circle cx="22" cy="6.5" r="5" fill="#5ea391" />

                    <text x="22" y="9.4" fill="#0d1416" font-family="'Inter', sans-serif" font-size="9"
                        font-weight="700" text-anchor="middle">
                        2
                    </text>
                </svg>
            </button>

            {{-- Mobile Menu Hamburger --}}
            <button type="button" @click="mobileMenuOpen = true"
                class="lg:hidden flex items-center justify-center p-0 cursor-pointer transition-transform duration-200 hover:scale-110 group/btn"
                aria-label="Menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    class="stroke-[#9ba2a8] group-hover/btn:stroke-white transition-colors" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>

        </div>
    </div>

    {{-- Luxury Mega Menu Dropdown: View All Brands --}}
    <x-brands-mega-menu />

    {{-- Fullscreen Mobile Menu Overlay --}}
    <div x-show="mobileMenuOpen"
        class="fixed inset-0 z-[100] flex flex-col bg-[#0e1215]/95 backdrop-blur-xl text-white overflow-hidden lg:hidden"
        x-transition:enter="transition ease-out duration-500" x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-400"
        x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="-translate-x-full opacity-0"
        style="display: none;">

        {{-- Mobile Header with Close Button --}}
        <div class="flex justify-between items-center px-6 py-7 border-b border-white/5">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/timeyar-circle-emblem.png') }}" alt="TimeYrah"
                    class="w-8 h-8 rounded-full border border-[#d59a7a]/40 object-cover">
                <span
                    class="font-['Montserrat',sans-serif] text-[14.5px] uppercase font-normal tracking-[0.35em] text-[#cdd2d6]">TIMEYRAH</span>
            </div>
            <button @click="mobileMenuOpen = false"
                class="p-2 cursor-pointer transition-transform duration-300 hover:rotate-90">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9ba2a8"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Mobile Navigation Links --}}
        <div
            class="flex flex-col items-center justify-center flex-1 gap-10 font-['Inter',sans-serif] text-xl font-light tracking-[0.2em] uppercase">
            <a href="#collection" @click="mobileMenuOpen = false"
                class="hover:text-[#cb8665] transition-colors duration-300">The Collection</a>
            <a href="#collection" @click="mobileMenuOpen = false"
                class="hover:text-[#cb8665] transition-colors duration-300">View all Brands</a>
            <a href="#about" @click="mobileMenuOpen = false"
                class="hover:text-[#cb8665] transition-colors duration-300">About</a>
            <a href="#journal" @click="mobileMenuOpen = false"
                class="hover:text-[#cb8665] transition-colors duration-300">Journal</a>
        </div>

        {{-- Decorative bottom gradient --}}
        <div class="h-32 bg-gradient-to-t from-[#cb8665]/10 to-transparent"></div>
    </div>
</header>
