@php
    $categories = [
        [
            'title' => 'STRAP',
            'subtitle' => 'Leather & Rubber',
            'brands' => [
                'Rado',
                'Citizen',
                'Seiko',
                'Casio General',
                'Casio Edifice',
                'Casio G Shock',
                'Omax General',
                'Omax Masterpiece',
                'Omax Premium',
                'Mathey Tissot',
                'Ferro',
                'Crysma',
                'Kenneth Cole',
                'Fossil',
                'Daniel Klein',
                'Swiss Military Hanowa',
                'Santa Barbara',
            ],
        ],
        [
            'title' => 'METAL CHAIN',
            'subtitle' => 'Steel & Titanium',
            'brands' => [
                'Rado',
                'Citizen',
                'Seiko',
                'Casio General',
                'Casio Edifice',
                'Casio G Shock',
                'Omax General',
                'Omax Masterpiece',
                'Omax Premium',
                'Mathey Tissot',
                'Ferro',
                'Crysma',
                'Kenneth Cole',
                'Fossil',
                'Daniel Klein',
                'Swiss Military Hanowa',
                'Santa Barbara',
            ],
        ],
        [
            'title' => 'ANALOG',
            'subtitle' => 'Classic Dials',
            'brands' => [
                'Rado',
                'Citizen',
                'Seiko',
                'Casio General',
                'Casio Edifice',
                'Casio G Shock',
                'Omax General',
                'Omax Masterpiece',
                'Omax Premium',
                'Mathey Tissot',
                'Ferro',
                'Crysma',
                'Kenneth Cole',
                'Fossil',
                'Daniel Klein',
                'Swiss Military Hanowa',
                'Santa Barbara',
            ],
        ],
        [
            'title' => 'QUARTZ',
            'subtitle' => 'Precision Battery',
            'brands' => [
                'Rado',
                'Citizen',
                'Seiko',
                'Casio General',
                'Casio Edifice',
                'Casio G Shock',
                'Omax General',
                'Omax Masterpiece',
                'Omax Premium',
                'Mathey Tissot',
                'Ferro',
                'Crysma',
                'Kenneth Cole',
                'Fossil',
                'Daniel Klein',
                'Swiss Military Hanowa',
                'Santa Barbara',
            ],
        ],
        [
            'title' => 'DIGITAL',
            'subtitle' => 'Multifunction LCD',
            'brands' => [
                'Rado',
                'Citizen',
                'Seiko',
                'Casio General',
                'Casio Edifice',
                'Casio G Shock',
                'Omax General',
                'Omax Masterpiece',
                'Omax Premium',
                'Mathey Tissot',
                'Ferro',
                'Crysma',
                'Kenneth Cole',
                'Fossil',
                'Daniel Klein',
                'Swiss Military Hanowa',
                'Santa Barbara',
            ],
        ],
        [
            'title' => 'AUTOMATIC',
            'subtitle' => 'Mechanical Caliber',
            'brands' => [
                'Rado',
                'Citizen',
                'Seiko',
                'Casio General',
                'Casio Edifice',
                'Casio G Shock',
                'Omax General',
                'Omax Masterpiece',
                'Omax Premium',
                'Mathey Tissot',
                'Ferro',
                'Crysma',
                'Kenneth Cole',
                'Fossil',
                'Daniel Klein',
                'Swiss Military Hanowa',
                'Santa Barbara',
            ],
        ],
    ];
@endphp

{{-- Mega Menu Container --}}
<div x-show="brandsOpen" x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300 transform"
    x-transition:enter-start="opacity-0 -translate-y-3 scale-[0.99]"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 -translate-y-3 scale-[0.99]" @mouseenter="openBrands()" @mouseleave="closeBrands()"
    @keydown.escape.window="brandsOpen = false"
    class="absolute left-0 right-0 top-full w-full z-50 px-4 sm:px-6 lg:px-10 pt-2 pb-8 pointer-events-auto col-span-full"
    style="display: none;">

    {{-- Invisible hover bridge preventing any gap flickers --}}
    <div class="absolute -top-4 left-0 right-0 h-5"></div>

    {{-- Main Luxury Cartouche Card --}}
    <div x-data="{ searchQuery: '' }"
        class="relative max-w-[1520px] max-h-[calc(100vh-115px)] overflow-y-auto mx-auto rounded-2xl
                bg-[#0c1014]/95 backdrop-blur-2xl
                border border-[#cb8665]/35
                shadow-[0_28px_80px_-15px_rgba(0,0,0,0.95),0_0_40px_rgba(203,152,126,0.1),inset_0_1px_1.5px_rgba(255,255,255,0.15)]">

        {{-- Top Copper Bevel Highlight Rim --}}
        <div class="h-[2px] w-full bg-gradient-to-r from-transparent via-[#dd9c7c] to-transparent opacity-90"></div>

        {{-- Subtle Technical Horology Blueprint Background Lines (Echoing Hero Design) --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-25">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none">
                <circle cx="8%" cy="15%" r="180" stroke="#4fd1c5" stroke-width="0.5" stroke-dasharray="3 6"
                    opacity="0.3" />
                <circle cx="92%" cy="85%" r="220" stroke="#dd9c7c" stroke-width="0.5" stroke-dasharray="4 8"
                    opacity="0.25" />
                <line x1="0" y1="52" x2="100%" y2="52" stroke="#ffffff" stroke-width="0.5"
                    stroke-opacity="0.05" />
            </svg>
        </div>

        {{-- Top Utility Bar --}}
        <div
            class="relative z-10 flex flex-wrap items-center justify-between gap-4 px-6 lg:px-10 py-3.5 border-b border-white/5 bg-[#141a21]/60">
            {{-- Left: Badge & Tagline --}}
            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#cb8665]/15 border border-[#cb8665]/35 text-[10.5px] font-['Montserrat',sans-serif] font-semibold tracking-[0.25em] text-[#e4ab91] uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#cb8665] animate-pulse"></span>
                    Authentic Watch Catalog
                </span>
                <span class="text-[12.5px] font-['Inter',sans-serif] text-[#8e969d] hidden md:inline">
                    Browse timepieces by case architecture, movement caliber & authorized brands
                </span>
            </div>

            {{-- Right: Quick Search Filter & Close Button --}}
            <div class="flex items-center gap-4">
                {{-- Quick Filter Input --}}
                <div class="relative flex items-center">
                    <input type="text" x-model="searchQuery"
                        @keydown.enter="window.location.href = '{{ route('shop') }}?search=' + encodeURIComponent(searchQuery)"
                        placeholder="Filter or press Enter to search..."
                        class="w-44 sm:w-64 px-3.5 py-1.5 text-xs text-[#e8e4df] placeholder-[#6b737b] bg-[#0c1014]/80 border border-white/10 rounded-full focus:outline-none focus:border-[#cb8665]/60 focus:ring-1 focus:ring-[#cb8665]/40 transition-all font-['Inter',sans-serif]" />
                    <svg class="absolute right-3 w-3.5 h-3.5 stroke-[#8e969d] pointer-events-none" viewBox="0 0 24 24"
                        fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </div>

                {{-- Close Button --}}
                <button type="button" @click="brandsOpen = false"
                    class="p-1.5 rounded-full text-[#8e969d] hover:text-[#e8e4df] hover:bg-white/5 transition-colors"
                    aria-label="Close menu">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- 6 Columns Grid (Faithfully matching user's layout) --}}
        <div class="relative z-10 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-x-6 gap-y-8 px-6 lg:px-10 py-7">
            @foreach ($categories as $cat)
                <div class="flex flex-col group/col">
                    {{-- Category Header --}}
                    <div class="mb-3.5 pb-2 border-b border-white/5">
                        <a href="{{ route('shop', ['category' => $cat['title']]) }}"
                            class="inline-block font-['Montserrat',sans-serif] text-[13px] font-bold tracking-[0.2em] text-[#e8e4df] uppercase group-hover/col:text-[#e4ab91] transition-colors duration-200">
                            {{ $cat['title'] }}
                        </a>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span
                                class="h-[2px] w-6 bg-[#cb8665] rounded-full transition-all duration-300 group-hover/col:w-10"></span>
                            <span
                                class="text-[10.5px] font-['Outfit',sans-serif] text-[#6b737b] tracking-wider uppercase font-medium">
                                {{ $cat['subtitle'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Brand List Items --}}
                    <ul class="flex flex-col space-y-[7px]">
                        @foreach ($cat['brands'] as $brand)
                            <li x-show="!searchQuery || '{{ strtolower($brand) }}'.includes(searchQuery.toLowerCase().trim())"
                                x-transition>
                                <a href="{{ route('shop', ['brand' => $brand, 'category' => $cat['title']]) }}"
                                    class="group/link flex items-center justify-between text-[13.5px] font-['Inter',sans-serif] text-[#9ba1a7] hover:text-[#ffffff] transition-all duration-200 py-0.5">
                                    <span
                                        class="flex items-center gap-2 transition-transform duration-200 group-hover/link:translate-x-1.5">
                                        <span
                                            class="w-1 h-1 rounded-full bg-[#cb8665] opacity-0 group-hover/link:opacity-100 transition-opacity"></span>
                                        <span class="group-hover/link:text-[#f7ebe4]">{{ $brand }}</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        {{-- Bottom Status & Guarantee Bar --}}
        <div
            class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4 px-6 lg:px-10 py-4 bg-[#080b0e]/85 border-t border-white/5">
            {{-- Trust Badges --}}
            <div
                class="flex flex-wrap items-center justify-center md:justify-start gap-6 text-[12px] font-['Inter',sans-serif] text-[#8e969d]">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 stroke-[#5ea391]" viewBox="0 0 24 24" fill="none" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                    <span>100% Authentic Timepieces</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 stroke-[#cb8665]" viewBox="0 0 24 24" fill="none" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m4.93 4.93 4.24 4.24" />
                        <path d="m14.83 9.17 4.24-4.24" />
                        <path d="m14.83 14.83 4.24 4.24" />
                        <path d="m9.17 14.83-4.24 4.24" />
                        <circle cx="12" cy="12" r="4" />
                    </svg>
                    <span>Official International Warranty</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 stroke-[#38bdf8]" viewBox="0 0 24 24" fill="none" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="14" x="2" y="5" rx="2" />
                        <line x1="2" x2="22" y1="10" y2="10" />
                    </svg>
                    <span>Complimentary Secure Delivery</span>
                </div>
            </div>

            {{-- CTA Button --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('shop') }}"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-full
                    font-['Outfit',sans-serif] text-[12.5px] font-medium tracking-[0.08em]
                     text-[#0e1215] bg-gradient-to-r from-[#e4ab91] via-[#cb8665]
                      to-[#9a5a3f] hover:brightness-110 shadow-[0_4px_16px_rgba(203,152,126,0.35)
                      ] transition-all duration-300">
                    <span class="text-white">EXPLORE ALL COLLECTIONS</span>
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

    </div>
</div>
