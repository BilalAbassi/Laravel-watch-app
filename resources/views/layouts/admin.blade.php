<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Atelier' }} — TIMEYRAH Watches</title>

    <!-- Immediate Theme Initialization Script (Prevents Theme Flash) -->
    <script>
        (function() {
            try {
                var theme = localStorage.getItem('timeyrah_theme') || 'dark';
                if (theme === 'light') {
                    document.documentElement.classList.add('theme-light');
                    document.documentElement.setAttribute('data-theme', 'light');
                } else {
                    document.documentElement.classList.remove('theme-light');
                    document.documentElement.setAttribute('data-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>

    <!-- Google Fonts: Cormorant Garamond, Montserrat, Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-[#0b0f12] text-[#d1d6da] min-h-screen selection:bg-[#cb8665]/30 selection:text-white flex flex-col font-['Inter',sans-serif] antialiased">

    <!-- Top Admin Bar -->
    <header class="sticky top-0 z-40 border-b border-white/10 bg-[#0e1419]/95 backdrop-blur-xl">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-12 py-3.5 flex items-center justify-between gap-4">
            <!-- Brand & Admin Emblem -->
            <div class="flex items-center gap-3.5">
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#2a170e] to-[#120a06] border border-[#cb8665]/50 flex items-center justify-center shadow-[0_0_15px_rgba(203,134,101,0.25)] shrink-0 group-hover:scale-105 transition-transform">
                        <span class="font-['Cormorant_Garamond',serif] italic font-bold text-lg text-[#f5be9f]">TY</span>
                    </div>
                    <div>
                        <span class="font-['Montserrat',sans-serif] text-sm tracking-[0.25em] font-semibold text-white uppercase block">
                            TIMEYRAH
                        </span>
                        <span class="text-[10px] font-mono tracking-wider text-[#cb8665] uppercase block flex items-center gap-1 font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#cb8665] animate-pulse"></span>
                            Admin Atelier
                        </span>
                    </div>
                </a>

                <!-- Navigation Tabs -->
                <nav class="hidden md:flex items-center gap-1 ml-6 pl-6 border-l border-white/10 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold">
                    <a href="{{ route('admin.products.index') }}"
                        class="px-3 py-1.5 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/40' : 'text-[#8e969d] hover:text-white hover:bg-white/5' }} transition-colors">
                        Watches / Catalog
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="px-3 py-1.5 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/40' : 'text-[#8e969d] hover:text-white hover:bg-white/5' }} transition-colors">
                        Categories
                    </a>
                    <a href="{{ route('admin.specifications.index') }}"
                        class="px-3 py-1.5 rounded-lg {{ request()->routeIs('admin.specifications.*') ? 'bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/40' : 'text-[#8e969d] hover:text-white hover:bg-white/5' }} transition-colors">
                        Specifications
                    </a>
                    <a href="{{ route('admin.customizer.index') }}"
                        class="px-3 py-1.5 rounded-lg {{ request()->routeIs('admin.customizer.*') ? 'bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/40' : 'text-[#8e969d] hover:text-white hover:bg-white/5' }} transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Page Customizer</span>
                    </a>
                    <a href="{{ route('shop') }}" target="_blank"
                        class="px-3 py-1.5 rounded-lg text-[#8e969d] hover:text-white hover:bg-white/5 transition-colors flex items-center gap-1.5">
                        <span>Live Store</span>
                        <svg class="w-3 h-3 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="px-3 py-1.5 rounded-lg text-[#8e969d] hover:text-white hover:bg-white/5 transition-colors">
                        Customer View
                    </a>
                </nav>
            </div>

            <!-- Right Controls: Theme Toggle & Admin Profile -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Theme Switcher -->
                <button type="button"
                    onclick="(function(){
                        var html = document.documentElement;
                        var isLight = html.classList.contains('theme-light');
                        if (isLight) {
                            html.classList.remove('theme-light');
                            html.setAttribute('data-theme', 'dark');
                            localStorage.setItem('timeyrah_theme', 'dark');
                        } else {
                            html.classList.add('theme-light');
                            html.setAttribute('data-theme', 'light');
                            localStorage.setItem('timeyrah_theme', 'light');
                        }
                    })()"
                    class="p-2 rounded-xl bg-white/5 border border-white/10 text-[#8e969d] hover:text-white hover:border-[#cb8665]/40 transition-colors cursor-pointer"
                    title="Toggle Dark / Light Theme"
                    aria-label="Toggle Theme">
                    <svg class="w-4 h-4 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                </button>

                <!-- Admin User Info & Logout -->
                <div class="flex items-center gap-3 pl-3 border-l border-white/10">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#cb8665] to-[#8a4d34] flex items-center justify-center text-xs font-bold text-white border border-[#f5be9f]/40 shadow-sm shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <span class="text-xs font-medium text-white block leading-tight">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-[#cb8665] font-mono tracking-wider uppercase block">Administrator</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="ml-1">
                        @csrf
                        <button type="submit"
                            title="Sign Out"
                            class="p-2 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Admin Shell -->
    <main class="flex-1 max-w-[1600px] w-full mx-auto px-4 sm:px-6 lg:px-12 py-8 sm:py-10">
        <!-- Flash Alerts -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition
                class="mb-6 p-4 rounded-2xl bg-[#5ea391]/15 border border-[#5ea391]/40 text-[#a3decb] text-xs flex items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-[#5ea391]/25 flex items-center justify-center text-[#5ea391] font-bold">✓</div>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" @click="show = false" class="text-[#5ea391] hover:text-white p-1">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition
                class="mb-6 p-4 rounded-2xl bg-red-950/40 border border-red-500/40 text-red-200 text-xs shadow-lg space-y-1">
                <div class="flex items-center justify-between pb-1 border-b border-red-500/20">
                    <span class="font-bold uppercase tracking-wider text-red-400">Please review the form errors below:</span>
                    <button type="button" @click="show = false" class="text-red-400 hover:text-white p-1">✕</button>
                </div>
                <ul class="list-disc pl-5 pt-1 space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Admin Footer -->
    <footer class="border-t border-white/10 bg-[#0e1419] py-4 text-center text-xs text-[#6b737b]">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-12 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>TIMEYRAH Horology CMS • Pakistan Edition</span>
            <span>Connected to Database • Changes reflect live instantly</span>
        </div>
    </footer>
</body>

</html>

