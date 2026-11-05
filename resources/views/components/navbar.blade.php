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

            {{-- Theme Switcher Button (Dark / Light Mode) --}}
            <button type="button"
                @click="$store.theme.toggle()"
                class="flex items-center justify-center p-0 cursor-pointer transition-transform duration-200 hover:scale-110 group/theme relative focus:outline-none"
                :title="$store.theme.isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                aria-label="Toggle Color Theme">
                
                <!-- Sun Icon (Shown in Dark Mode) -->
                <svg x-show="$store.theme.isDark"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="rotate-90 scale-50 opacity-0"
                    x-transition:enter-end="rotate-0 scale-100 opacity-100"
                    width="17" height="17" viewBox="0 0 24 24" fill="none"
                    class="stroke-[#cb987e] hover:stroke-[#e4ab91] transition-colors" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2"/>
                    <path d="M12 20v2"/>
                    <path d="m4.93 4.93 1.41 1.41"/>
                    <path d="m17.66 17.66 1.41 1.41"/>
                    <path d="M2 12h2"/>
                    <path d="M20 12h2"/>
                    <path d="m6.34 17.66-1.41 1.41"/>
                    <path d="m19.07 4.93-1.41 1.41"/>
                </svg>

                <!-- Moon Icon (Shown in Light Mode) -->
                <svg x-show="!$store.theme.isDark"
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="-rotate-90 scale-50 opacity-0"
                    x-transition:enter-end="rotate-0 scale-100 opacity-100"
                    width="17" height="17" viewBox="0 0 24 24" fill="none"
                    class="stroke-[#0f172a] hover:stroke-[#cb8665] transition-colors" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    style="display: none;">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                </svg>
            </button>

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

            {{-- User Account / Auth Dropdown --}}
            @guest
                <a href="{{ route('login') }}"
                    class="flex items-center justify-center p-0 cursor-pointer transition-transform duration-200 hover:scale-110 group/btn"
                    aria-label="Login"
                    title="Login or Register">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                        class="stroke-[#9ba2a8] group-hover/btn:stroke-white transition-colors" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </a>
            @else
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                    <button type="button" @click="open = !open"
                        class="flex items-center gap-1.5 p-0 cursor-pointer transition-transform duration-200 hover:scale-105 group/btn focus:outline-none"
                        aria-label="User Account">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#cb8665] to-[#9a5a3f] flex items-center justify-center text-[10px] font-bold text-white shadow-sm border border-[#f5be9f]/40">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <!-- User Dropdown Menu -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         style="display: none;"
                         class="veltra-user-dropdown absolute right-0 mt-3 w-56 rounded-2xl bg-[#0e1419]/95 border border-[#cb8665]/40 shadow-[0_20px_45px_rgba(0,0,0,0.85)] p-2 z-50 backdrop-blur-xl">
                        <div class="px-3 py-2.5 border-b border-white/10 mb-1">
                            <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-[#8e969d] truncate">{{ Auth::user()->email }}</p>
                            @if (Auth::user()->is_admin)
                                <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[9px] font-mono tracking-wider uppercase bg-[#cb8665]/25 text-[#f5be9f] border border-[#cb8665]/40 font-semibold">
                                    Admin Atelier
                                </span>
                            @else
                                <span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[9px] font-mono tracking-wider uppercase bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/30">
                                    Customer
                                </span>
                            @endif
                        </div>
                        @if (Auth::user()->is_admin)
                            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-[#f5be9f] hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
                                <span>Admin Products CMS</span>
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-[#d1d6da] hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>
                            <span>My Dashboard</span>
                        </a>
                        <a href="{{ route('shop') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-[#d1d6da] hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                            <span>Shop All Watches</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-[#d1d6da] hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>My Profile</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1 pt-1 border-t border-white/10">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors cursor-pointer text-left">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endguest

            {{-- Bag / Cart Button --}}
            <button type="button"
                @click="$store.cart.toggle()"
                class="relative flex items-center justify-center p-0 cursor-pointer transition-all duration-200 hover:scale-110 group/btn focus:outline-none"
                aria-label="View Shopping Cart"
                :title="$store.cart.count > 0 ? $store.cart.count + ' watch(es) in Cart' : 'Cart is empty'">
                <svg width="24" height="24" viewBox="0 0 28 28" fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    :class="$store.cart.animating ? 'scale-125 rotate-[-8deg]' : ''"
                    class="transition-transform duration-300">
                    <path d="M10.5 8.5V7a3.5 3.5 0 0 1 7 0v1.5"
                        class="stroke-[#cb987e] group-hover/btn:stroke-[#e4ab91] transition-colors" stroke-width="1.6"
                        stroke-linecap="round" />

                    <path d="M6 9H22L23.5 23.5H4.5L6 9Z"
                        class="stroke-[#cb987e] group-hover/btn:stroke-[#e4ab91] transition-colors" stroke-width="1.6"
                        stroke-linejoin="round" />

                    <!-- Dynamic Count Badge Indicator -->
                    <g x-show="$store.cart.count > 0"
                       x-transition:enter="transition ease-out duration-200"
                       x-transition:enter-start="scale-0 opacity-0"
                       x-transition:enter-end="scale-100 opacity-100">
                        <circle cx="22" cy="6.5" r="5.5" fill="#5ea391" />
                        <text x="22" y="9.5" fill="#0d1416" font-family="'Inter', sans-serif" font-size="8.5"
                            font-weight="800" text-anchor="middle"
                            x-text="$store.cart.count > 99 ? '99+' : $store.cart.count">
                        </text>
                    </g>
                </svg>

                <!-- Dynamic Ping Pulse on Product Addition -->
                <span x-show="$store.cart.animating"
                      x-transition:enter="transition ease-out duration-200"
                      x-transition:leave="transition ease-in duration-300"
                      class="absolute -top-1 -right-1 flex h-3 w-3 pointer-events-none"
                      style="display: none;">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#5ea391] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-[#5ea391]"></span>
                </span>
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
            class="flex flex-col items-center justify-center flex-1 gap-8 font-['Inter',sans-serif] text-xl font-light tracking-[0.2em] uppercase">
            @auth
                @if (Auth::user()->is_admin)
                    <a href="{{ route('admin.products.index') }}" @click="mobileMenuOpen = false"
                        class="text-[#f5be9f] hover:text-white transition-colors duration-300 font-medium">Admin CMS</a>
                @endif
                <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false"
                    class="hover:text-[#cb8665] transition-colors duration-300">My Dashboard</a>
            @endauth
            <a href="{{ route('shop') }}" @click="mobileMenuOpen = false"
                class="hover:text-[#cb8665] transition-colors duration-300">The Collection</a>
            <a href="{{ route('shop') }}" @click="mobileMenuOpen = false"
                class="hover:text-[#cb8665] transition-colors duration-300">View all Brands</a>
            <button type="button" @click="mobileMenuOpen = false; $store.cart.open()"
                class="hover:text-[#cb8665] transition-colors duration-300 flex items-center gap-2 cursor-pointer uppercase">
                <span>Shopping Cart</span>
                <span x-show="$store.cart.count > 0"
                    class="text-xs px-2.5 py-0.5 rounded-full bg-[#5ea391]/20 text-[#5ea391] border border-[#5ea391]/40 font-mono font-bold"
                    x-text="$store.cart.count">
                </span>
            </button>
            <button type="button" @click="$store.theme.toggle()"
                class="hover:text-[#cb8665] transition-colors duration-300 flex items-center gap-2.5 cursor-pointer uppercase text-base font-medium">
                <span x-text="$store.theme.isDark ? '☀️ Switch to Light Mode' : '🌙 Switch to Dark Mode'"></span>
            </button>
            <a href="{{ route('home') }}#about" @click="mobileMenuOpen = false"
                class="hover:text-[#cb8665] transition-colors duration-300">About</a>
            <a href="{{ route('home') }}#journal" @click="mobileMenuOpen = false"
                class="hover:text-[#cb8665] transition-colors duration-300">Journal</a>
        </div>

        {{-- Decorative bottom gradient --}}
        <div class="h-32 bg-gradient-to-t from-[#cb8665]/10 to-transparent"></div>
    </div>

    {{-- Interactive Luxury Horology Cart Slide-Over Drawer --}}
    <x-cart-drawer />
</header>

<!-- Global Alpine Theme Store Definition -->
<script>
    (function () {
        function registerTimeyrahThemeStore() {
            if (!window.Alpine || window.Alpine.store('theme')) {
                return;
            }

            const currentIsLight = document.documentElement.classList.contains('theme-light') ||
                                   localStorage.getItem('timeyrah_theme') === 'light';

            window.Alpine.store('theme', {
                isDark: !currentIsLight,

                toggle() {
                    // Temporarily add smooth transition class
                    document.documentElement.classList.add('theme-transition');

                    this.isDark = !this.isDark;
                    const newTheme = this.isDark ? 'dark' : 'light';

                    if (this.isDark) {
                        document.documentElement.classList.remove('theme-light');
                        document.documentElement.setAttribute('data-theme', 'dark');
                        try { localStorage.setItem('timeyrah_theme', 'dark'); } catch (e) {}
                    } else {
                        document.documentElement.classList.add('theme-light');
                        document.documentElement.setAttribute('data-theme', 'light');
                        try { localStorage.setItem('timeyrah_theme', 'light'); } catch (e) {}
                    }

                    setTimeout(() => {
                        document.documentElement.classList.remove('theme-transition');
                    }, 350);

                    window.dispatchEvent(new CustomEvent('timeyrah:theme-changed', {
                        detail: { theme: newTheme }
                    }));
                }
            });
        }

        document.addEventListener('alpine:init', registerTimeyrahThemeStore);
        if (window.Alpine) {
            registerTimeyrahThemeStore();
        }
    })();
</script>
