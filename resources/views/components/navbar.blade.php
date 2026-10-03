<header x-data="{ mobileMenuOpen: false }"
    class="w-full flex items-center justify-between 
max-w-[1380px] mx-auto
lg:grid lg:grid-cols-[1fr_auto_1fr] lg:items-start px-6 lg:px-12 border-t border-white/5 border-b-2 py-5">
    {{-- Left: Brand Logo & Wordmark --}}
    <div class="flex items-center justify-self-start pt-[18px]">
        <a href="/" class="flex items-center gap-4 hover:opacity-90 transition-opacity duration-300">
            <svg width="22" height="20" viewBox="0 0 24 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="shrink-0 drop-shadow-[0_0_8px_rgba(110,167,151,0.3)]">
                <path d="M2.5 3.5H21.5L12 20.5L2.5 3.5Z" stroke="#6ea797" stroke-width="1.9" stroke-linejoin="round" />
                <line x1="6.2" y1="8.5" x2="17.8" y2="8.5" stroke="#6ea797" stroke-width="1.8"
                    stroke-linecap="round" />
            </svg>

            <span
                class="font-['Montserrat',sans-serif] text-[14.5px] uppercase font-normal tracking-[0.35em] lg:tracking-[0.46em] text-[#cdd2d6]">
                VELTRA
            </span>
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

    {{-- Fullscreen Mobile Menu Overlay --}}
    <div x-show="mobileMenuOpen" 
         class="fixed inset-0 z-[100] flex flex-col bg-[#0e1215]/95 backdrop-blur-xl text-white overflow-hidden lg:hidden"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="-translate-x-full opacity-0"
         x-transition:enter-end="translate-x-0 opacity-100"
         x-transition:leave="transition ease-in duration-400"
         x-transition:leave-start="translate-x-0 opacity-100"
         x-transition:leave-end="-translate-x-full opacity-0"
         style="display: none;">
        
        {{-- Mobile Header with Close Button --}}
        <div class="flex justify-between items-center px-6 py-7 border-b border-white/5">
            <span class="font-['Montserrat',sans-serif] text-[14.5px] uppercase font-normal tracking-[0.35em] text-[#cdd2d6]">VELTRA</span>
            <button @click="mobileMenuOpen = false" class="p-2 cursor-pointer transition-transform duration-300 hover:rotate-90">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9ba2a8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Mobile Navigation Links --}}
        <div class="flex flex-col items-center justify-center flex-1 gap-12 font-['Inter',sans-serif] text-xl font-light tracking-[0.2em] uppercase">
            <a href="#collection" @click="mobileMenuOpen = false" class="hover:text-[#cb8665] transition-colors duration-300">The Collection</a>
            <a href="#story" @click="mobileMenuOpen = false" class="hover:text-[#cb8665] transition-colors duration-300">Brand Story</a>
            <a href="#about" @click="mobileMenuOpen = false" class="hover:text-[#cb8665] transition-colors duration-300">About</a>
            <a href="#journal" @click="mobileMenuOpen = false" class="hover:text-[#cb8665] transition-colors duration-300">Journal</a>
        </div>
        
        {{-- Decorative bottom gradient --}}
        <div class="h-32 bg-gradient-to-t from-[#cb8665]/10 to-transparent"></div>
    </div>
</header>
