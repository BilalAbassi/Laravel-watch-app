<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $seo = $sections['seo'] ?? null;
        $seoContent = $seo?->content ?? [];
        $pageTitle = $seo?->title ?? 'TIMEYRAH — Precision in Motion | Luxury & Everyday Watches Pakistan';
        $metaDesc = $seoContent['meta_description'] ?? 'Shop 100% original watches in Pakistan. Featuring Rado, Citizen, Seiko, Casio G-Shock, and Tissot with official brand warranty and Cash on Delivery across Karachi, Lahore, Islamabad, and nationwide.';
        $metaKeywords = $seoContent['keywords'] ?? 'watches pakistan, luxury watches karachi, original watches lahore, casio g shock pakistan, seiko watches, rado captain cook';
        $ogImage = $seo?->image_url ?? asset('images/timeyar-circle-emblem.png');
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="keywords" content="{{ $metaKeywords }}">

    <!-- OpenGraph / Social Meta Tags -->
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $seo?->subtitle ?? $metaDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

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
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#0b0f12] text-[#d1d6da]   ">

    <!-- Top Navigation Component -->
    <x-navbar />

    <main class="max-w-[1600px] mx-auto">
        @if (isset($activeSections) && $activeSections->isNotEmpty())
            @foreach ($activeSections as $sec)
                @if ($sec->section_key === 'hero_banner')
                    <x-header :section="$sec" />
                @elseif ($sec->section_key === 'featured_watches')
                    <x-home.collectionbar :section="$sec" :featuredWatches="$featuredWatches ?? null" />
                @elseif ($sec->section_key === 'shop_by_category')
                    <!-- Shop By Category Collection (Large Luxury Cards with Rich Watch Photography) -->
                    <section class="relative mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                        <div class="mb-8 sm:mb-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2.5">
                                    <span class="w-5 h-[1.5px] bg-[#65a396]"></span>
                                    <span class="text-[#65a396] uppercase tracking-[0.3em] text-[11px] sm:text-xs font-semibold">
                                        {{ $sec->content['badge'] ?? 'SHOP BY CATEGORY' }}
                                    </span>
                                </div>
                                <h2 class="text-3xl sm:text-4xl lg:text-[42px] font-['Montserrat',sans-serif] text-white font-normal tracking-wide leading-tight">
                                    {{ $sec->title ?? 'THE EXCLUSIVE HOROLOGY COLLECTION' }}
                                </h2>
                                <p class="text-xs sm:text-sm text-[#8e969d] mt-1.5 max-w-2xl">
                                    {{ $sec->subtitle ?? 'Explore iconic timepieces curated across luxury categories.' }}
                                </p>
                            </div>
                            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-[#cb8665]/40 text-xs text-[#f5be9f] hover:text-white hover:bg-[#cb8665]/20 font-semibold uppercase tracking-wider transition-all shadow-md shrink-0">
                                <span>Browse All Watches</span>
                                <span>→</span>
                            </a>
                        </div>

                        <!-- Grand Category Showcase Grid: 3 large columns on desktop -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7">
                            @foreach ($categories as $cat)
                                <a href="{{ route('shop', ['category' => $cat->slug]) }}"
                                    class="category-showcase-card group relative h-[320px] sm:h-[350px] lg:h-[380px] rounded-[26px] overflow-hidden flex flex-col justify-end p-6 sm:p-7 border border-white/10 hover:border-[#cb8665]/60 shadow-[0_20px_50px_rgba(0,0,0,0.7)] transition-all duration-500 bg-[#0e1419]">

                                    <!-- Atmospheric Background Photo -->
                                    <img src="{{ $cat->cover_image_url }}" alt="{{ $cat->name }}"
                                        class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-108 transition-transform duration-700 ease-out" />

                                    <!-- Multi-Layer Cinematic Gradients for Peak Legibility in Light & Dark Mode -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/60 to-black/25 pointer-events-none"></div>
                                    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/45 to-transparent pointer-events-none"></div>
                                    <div class="absolute inset-0 bg-black/25 pointer-events-none"></div>
                                    <div class="absolute inset-0 bg-[#cb8665]/0 group-hover:bg-[#cb8665]/10 transition-colors duration-500 pointer-events-none"></div>

                                    <!-- Category Card Details Panel -->
                                    <div class="relative z-10 w-full">
                                        <!-- Category Eyebrow Pill -->
                                        <div class="mb-3">
                                            <span class="category-card-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-['Montserrat',sans-serif] uppercase tracking-[0.22em] font-semibold bg-black/60 backdrop-blur-md text-[#e4ab91] border border-white/15 shadow-sm">
                                                <span>Collection</span>
                                            </span>
                                        </div>

                                        <!-- Category Title (Always White & Vivid in Both Light & Dark Theme) -->
                                        <h3 class="category-card-title font-['Montserrat',sans-serif] text-2xl sm:text-3xl font-medium text-white tracking-wide leading-tight group-hover:text-[#f5be9f] transition-colors drop-shadow-md">
                                            {{ $cat->name }}
                                        </h3>

                                        <!-- Subtitle Descriptor -->
                                        <p class="category-card-subtitle text-xs sm:text-sm text-[#b0bcc2] font-light mt-1.5 drop-shadow line-clamp-1">
                                            {{ $cat->curated_subtitle }}
                                        </p>

                                        <!-- Bottom Control Bar -->
                                        <div class="flex items-center justify-between mt-5 pt-3.5 border-t border-white/15">
                                            <span class="category-card-count text-xs font-mono text-[#4fd1c5] font-semibold tracking-wide">
                                                {{ $cat->products_count }} Timepieces
                                            </span>

                                            <div class="category-card-cta inline-flex items-center gap-1.5 text-xs font-['Montserrat',sans-serif] font-bold uppercase tracking-wider text-white group-hover:text-[#e4ab91] transition-colors">
                                                <span>Shop Now</span>
                                                <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1.5 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    <polyline points="12 5 19 12 12 19"></polyline>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @elseif ($sec->section_key === 'promo_banner')
                    <x-home.makebanner :section="$sec" />
                @endif
            @endforeach
        @else
            <!-- Default Storefront Layout -->
            <x-header />
            <x-home.collectionbar />
            <x-home.makebanner />
        @endif
    </main>
    <x-footer />
</body>

</html>
