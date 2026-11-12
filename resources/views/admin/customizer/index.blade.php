<x-admin-layout title="Page Customizer & Side Panel Form">
    @php
        $orderedKeys = $sections->reject(fn($s) => $s->section_key === 'seo')->sortBy('sort_order')->keys()->values()->all();
        $activeMap = $sections->mapWithKeys(fn ($s) => [$s->section_key => (bool) $s->is_active])->all();
    @endphp

    <script>
        function pageCustomizer() {
            return {
                activeTab: 'hero',
                previewDevice: 'desktop',
                sectionsOrder: {!! json_encode($orderedKeys) !!},
                sectionLabels: {
                    hero_banner: 'Hero Banner',
                    featured_watches: 'Featured Watches',
                    shop_by_category: 'Shop By Category',
                    promo_banner: 'Promo Banner',
                    brand_showcase: 'Brand Showcase',
                    footer_section: 'Footer Section (Categories Cartouche)',
                    main_footer: 'Main Footer (Links & Branding)'
                },
                activeMap: {!! json_encode($activeMap) !!},
                reloadIframe() {
                    const frame = document.getElementById('previewIframe');
                    if (frame) {
                        frame.src = frame.src.split('#')[0] + '?preview=' + Date.now();
                    }
                },
                reorderUp(index) {
                    if (index > 0) {
                        const temp = this.sectionsOrder[index];
                        this.sectionsOrder[index] = this.sectionsOrder[index - 1];
                        this.sectionsOrder[index - 1] = temp;
                    }
                },
                reorderDown(index) {
                    if (index < this.sectionsOrder.length - 1) {
                        const temp = this.sectionsOrder[index];
                        this.sectionsOrder[index] = this.sectionsOrder[index + 1];
                        this.sectionsOrder[index + 1] = temp;
                    }
                }
            };
        }
        if (window.Alpine) {
            Alpine.data('pageCustomizer', pageCustomizer);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.data('pageCustomizer', pageCustomizer);
            });
        }
    </script>

    <div x-data="pageCustomizer()" class="space-y-6">

        <!-- Header Action Bar -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-white/10">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-4 h-[1.5px] bg-[#cb8665]"></span>
                    <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-[0.25em] text-[#cb8665] font-semibold">
                        Page Builder & CMS
                    </span>
                </div>
                <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl font-normal text-white tracking-tight">
                    Page Content Customizer
                </h1>
                <p class="text-xs text-[#8e969d] mt-1">
                    Edit SEO meta tags, hero banners, watch pictures, category collections, and rearrange sections on your store.
                </p>
            </div>

            <!-- Page Switcher & Global Actions -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="inline-flex rounded-xl p-1 bg-[#141b22] border border-white/10 text-xs font-['Montserrat',sans-serif]">
                    <a href="{{ route('admin.customizer.index', ['page' => 'home']) }}"
                        class="px-3 py-1.5 rounded-lg {{ $page === 'home' ? 'bg-[#cb8665] text-[#0e1215] font-bold shadow-md' : 'text-[#8e969d] hover:text-white' }} transition-all">
                        Home Page
                    </a>
                    <a href="{{ route('admin.customizer.index', ['page' => 'shop']) }}"
                        class="px-3 py-1.5 rounded-lg {{ $page === 'shop' ? 'bg-[#cb8665] text-[#0e1215] font-bold shadow-md' : 'text-[#8e969d] hover:text-white' }} transition-all">
                        Shop Page
                    </a>
                </div>

                <a href="{{ route('home') }}" target="_blank"
                    class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-[#f5be9f] hover:text-white transition-all flex items-center gap-1.5">
                    <span>View Live Site</span>
                    <svg class="w-3.5 h-3.5 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                </a>

                <button type="submit" form="customizerForm"
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all shadow-[0_6px_20px_rgba(203,134,101,0.35)] flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#0e1215]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Publish Changes</span>
                </button>
            </div>
        </div>

        <!-- Feedback Alerts -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-[#5ea391]/15 border border-[#5ea391]/40 text-[#5ea391] flex items-center justify-between text-xs font-medium shadow-lg">
                <div class="flex items-center gap-2.5">
                    <span class="w-5 h-5 rounded-full bg-[#5ea391]/20 flex items-center justify-center text-sm font-bold">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-[#5ea391]/60 hover:text-[#5ea391] text-sm px-1.5 py-0.5">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-500/15 border border-rose-500/40 text-rose-300 text-xs space-y-1.5 shadow-lg">
                <div class="flex items-center gap-2 font-semibold text-rose-200">
                    <span class="w-5 h-5 rounded-full bg-rose-500/20 flex items-center justify-center text-sm font-bold">⚠️</span>
                    <span>Please review the following errors before publishing:</span>
                </div>
                <ul class="list-disc list-inside pl-7 space-y-0.5 text-[11px] text-rose-300/90">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Two Column Workspace: Left Side Panel Form & Right Live Preview -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- ========================================== -->
            <!-- LEFT COLUMN: SIDE PANEL FORM              -->
            <!-- ========================================== -->
            <div class="lg:col-span-5 xl:col-span-5 space-y-4">

                <!-- Side Panel Tab Selector -->
                <div class="p-2 rounded-2xl bg-[#0e1419] border border-white/10 flex flex-wrap gap-1 text-xs font-['Montserrat',sans-serif] font-semibold">
                    <button type="button" @click="activeTab = 'hero'"
                        :class="activeTab === 'hero' ? 'bg-[#cb8665]/25 text-[#f5be9f] border-[#cb8665]/50' : 'text-[#8e969d] hover:text-white border-transparent'"
                        class="px-3 py-2 rounded-xl border transition-all flex items-center gap-1.5">
                        <span>🌟 Hero Banner</span>
                    </button>
                    <button type="button" @click="activeTab = 'seo'"
                        :class="activeTab === 'seo' ? 'bg-[#cb8665]/25 text-[#f5be9f] border-[#cb8665]/50' : 'text-[#8e969d] hover:text-white border-transparent'"
                        class="px-3 py-2 rounded-xl border transition-all flex items-center gap-1.5">
                        <span>🔍 SEO & Meta</span>
                    </button>
                    <button type="button" @click="activeTab = 'categories'"
                        :class="activeTab === 'categories' ? 'bg-[#cb8665]/25 text-[#f5be9f] border-[#cb8665]/50' : 'text-[#8e969d] hover:text-white border-transparent'"
                        class="px-3 py-2 rounded-xl border transition-all flex items-center gap-1.5">
                        <span>📂 Categories</span>
                    </button>
                    <button type="button" @click="activeTab = 'promo'"
                        :class="activeTab === 'promo' ? 'bg-[#cb8665]/25 text-[#f5be9f] border-[#cb8665]/50' : 'text-[#8e969d] hover:text-white border-transparent'"
                        class="px-3 py-2 rounded-xl border transition-all flex items-center gap-1.5">
                        <span>🚀 Promo Banner</span>
                    </button>
                    <button type="button" @click="activeTab = 'featured'"
                        :class="activeTab === 'featured' ? 'bg-[#cb8665]/25 text-[#f5be9f] border-[#cb8665]/50' : 'text-[#8e969d] hover:text-white border-transparent'"
                        class="px-3 py-2 rounded-xl border transition-all flex items-center gap-1.5">
                        <span>⌚ Featured</span>
                    </button>
                    <button type="button" @click="activeTab = 'footer'"
                        :class="activeTab === 'footer' ? 'bg-[#cb8665]/25 text-[#f5be9f] border-[#cb8665]/50' : 'text-[#8e969d] hover:text-white border-transparent'"
                        class="px-3 py-2 rounded-xl border transition-all flex items-center gap-1.5">
                        <span>👣 Footer</span>
                    </button>
                    <button type="button" @click="activeTab = 'sections'"
                        :class="activeTab === 'sections' ? 'bg-[#cb8665]/25 text-[#f5be9f] border-[#cb8665]/50' : 'text-[#8e969d] hover:text-white border-transparent'"
                        class="px-3 py-2 rounded-xl border transition-all flex items-center gap-1.5">
                        <span>↕️ Sections Order</span>
                    </button>
                </div>

                <!-- Customizer Form -->
                <form id="customizerForm" method="POST" action="{{ route('admin.customizer.update') }}" enctype="multipart/form-data"
                    class="p-6 rounded-2xl bg-[#0e1419] border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-6">
                    @csrf
                    <input type="hidden" name="page" value="{{ $page }}">

                    <!-- ============================================== -->
                    <!-- TAB 1: HERO BANNER & PAGE IMAGES               -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'hero'" class="space-y-5">
                        @php
                            $hero = $sections['hero_banner'] ?? null;
                            $heroContent = $hero->content ?? [];
                            $heroPos = $heroContent['position'] ?? [];
                        @endphp
                        <div class="border-b border-white/10 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="font-['Montserrat',sans-serif] text-sm uppercase tracking-wider font-bold text-white flex items-center gap-2">
                                    <span>🌟 Hero Header Banner</span>
                                </h3>
                                <p class="text-[11px] text-[#8e969d] mt-0.5">Top-of-page headline, branding message, and main watch hero photo.</p>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-mono rounded bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/30">hero_banner</span>
                        </div>

                        <!-- Badge Tag -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Eyebrow Badge Text</label>
                            <input type="text" name="hero[badge]" value="{{ old('hero.badge', $heroContent['badge'] ?? 'TIME-REDEFINED • 100% ORIGINAL') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                placeholder="e.g. TIME-REDEFINED • 100% ORIGINAL">
                        </div>

                        <!-- Brand / Title -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Brand Main Title</label>
                                <input type="text" name="hero[title]" value="{{ old('hero.title', $hero->title ?? 'TIMEYRAH.') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                    placeholder="TIMEYRAH.">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Subheading Line</label>
                                <input type="text" name="hero[subtitle]" value="{{ old('hero.subtitle', $hero->subtitle ?? 'More than Just a Watch. Experience the Caliber.') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                    placeholder="Experience the Caliber.">
                            </div>
                        </div>

                        <!-- Headline Accents -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Bronze Accent Word</label>
                                <input type="text" name="hero[heading_accent_1]" value="{{ old('hero.heading_accent_1', $heroContent['heading_accent_1'] ?? 'PRECISION IN') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-[#e4ab91] focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                    placeholder="PRECISION IN">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Serif Glow Word</label>
                                <input type="text" name="hero[heading_accent_2]" value="{{ old('hero.heading_accent_2', $heroContent['heading_accent_2'] ?? 'MOTION') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-[#4fd1c5] focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                    placeholder="MOTION">
                            </div>
                        </div>

                        <!-- Description Paragraph -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Hero Body Description</label>
                            <textarea name="hero[description]" rows="3"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                placeholder="Describe the boutique philosophy...">{{ old('hero.description', $heroContent['description'] ?? 'At TIMEYRAH, we believe a watch is more than an accessory — it is a symbol of style, premium quality, and timeless craftsmanship with nationwide delivery across Pakistan.') }}</textarea>
                        </div>

                        <!-- Hero Watch Image Upload & Selection -->
                        <div class="p-4 rounded-xl bg-[#141b22] border border-white/10 space-y-3.5"
                            x-data="{
                                previewUrl: '{{ $hero?->image_url ?? asset('hero-watch.png') }}',
                                fileName: '',
                                fileSize: '',
                                sizeWarning: '',
                                catalogChoice: '',
                                resetActive: false,
                                handleFile(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        this.resetActive = false;
                                        this.catalogChoice = '';
                                        this.fileName = file.name;
                                        this.previewUrl = URL.createObjectURL(file);
                                        const mb = (file.size / (1024 * 1024)).toFixed(2);
                                        this.fileSize = mb + ' MB';
                                        if (file.size > 20 * 1024 * 1024) {
                                            this.sizeWarning = '⚠️ File is ' + mb + ' MB. Maximum allowed is 20 MB.';
                                        } else {
                                            this.sizeWarning = '';
                                        }
                                    }
                                },
                                applyCatalogWatch(e) {
                                    const val = e.target.value;
                                    if (val) {
                                        this.resetActive = false;
                                        this.fileName = '';
                                        this.sizeWarning = '';
                                        this.catalogChoice = val;
                                        this.previewUrl = val;
                                        if (this.$refs.fileInput) {
                                            this.$refs.fileInput.value = '';
                                        }
                                    }
                                },
                                resetDefault() {
                                    this.resetActive = true;
                                    this.catalogChoice = 'RESET_DEFAULT';
                                    this.previewUrl = '{{ asset('hero-watch.png') }}';
                                    this.fileName = 'Default Watch Restored';
                                    this.sizeWarning = '';
                                    if (this.$refs.fileInput) {
                                        this.$refs.fileInput.value = '';
                                    }
                                }
                            }">
                            <input type="hidden" name="hero_catalog_image" :value="catalogChoice">
                            <input type="hidden" name="hero_reset_image" :value="resetActive ? '1' : '0'">

                            <div class="flex items-center justify-between border-b border-white/10 pb-2">
                                <label class="block text-xs font-semibold text-white flex items-center gap-2">
                                    <span>⌚ Center Hero Watch Image</span>
                                    <span class="text-[10px] text-[#cb8665] font-mono px-1.5 py-0.5 rounded bg-[#cb8665]/10 border border-[#cb8665]/30">Upload or Pick</span>
                                </label>
                                <button type="button" @click="resetDefault()"
                                    class="text-[11px] text-[#e4ab91] hover:text-white px-2 py-0.5 rounded bg-white/5 hover:bg-white/10 border border-white/10 transition-all flex items-center gap-1"
                                    title="Revert to original default hero watch">
                                    <span>↺</span> Reset to Default
                                </button>
                            </div>

                            <!-- Image Preview & File Input -->
                            <div class="flex items-center gap-4">
                                <div class="relative w-20 h-20 rounded-xl bg-[#091518] border border-[#cb8665]/40 flex items-center justify-center p-2 shrink-0 overflow-hidden shadow-inner group">
                                    <img :src="previewUrl"
                                        onerror="this.src='{{ asset('hero-watch.png') }}'"
                                        alt="Hero Watch Preview" class="max-h-full max-w-full object-contain transition-transform group-hover:scale-105">
                                    <template x-if="fileName || resetActive || catalogChoice">
                                        <span class="absolute bottom-1 right-1 w-2.5 h-2.5 rounded-full bg-[#5ea391] ring-2 ring-[#091518]" title="Ready to publish"></span>
                                    </template>
                                </div>

                                <div class="flex-1 space-y-1.5">
                                    <input type="file" name="hero_image" accept="image/*,.png,.jpg,.jpeg,.webp,.avif,.svg"
                                        x-ref="fileInput"
                                        @change="handleFile($event)"
                                        class="block w-full text-xs text-[#8e969d] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#cb8665]/20 file:text-[#f5be9f] hover:file:bg-[#cb8665]/30 file:cursor-pointer">

                                    <div class="flex flex-wrap items-center gap-2">
                                        <template x-if="fileName">
                                            <span class="inline-flex items-center gap-1 text-[11px] text-[#5ea391] font-medium">
                                                <span>✓ Selected:</span>
                                                <strong x-text="fileName"></strong>
                                                <span x-show="fileSize" class="text-[10px] text-[#8e969d]" x-text="'(' + fileSize + ')'"></span>
                                            </span>
                                        </template>
                                        <template x-if="!fileName && !resetActive && !catalogChoice">
                                            <p class="text-[11px] text-[#6b737b]">Upload transparent PNG/WEBP (recommended approx 600×800, up to 20MB).</p>
                                        </template>
                                    </div>
                                    <template x-if="sizeWarning">
                                        <p class="text-[11px] text-rose-400 font-medium" x-text="sizeWarning"></p>
                                    </template>
                                </div>
                            </div>

                            <!-- Alternative: Quick Pick from Store Catalog -->
                            <div class="pt-2.5 border-t border-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <label class="text-[11px] text-[#8e969d] flex items-center gap-1.5">
                                    <span>🏪</span> Or select any watch from store catalog:
                                </label>
                                <select @change="applyCatalogWatch($event)"
                                    class="px-3 py-1.5 rounded-lg bg-[#0d1217] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none max-w-xs">
                                    <option value="">-- Choose a store timepiece --</option>
                                    @if(isset($availableWatches))
                                        @foreach($availableWatches as $w)
                                            <option value="{{ $w->primary_image_url }}">{{ $w->title }} ({{ $w->brand?->name }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Center Watch Position by Screen Size -->
                        <div class="p-4 rounded-xl bg-[#141b22] border border-white/10 space-y-4" x-data="{ deviceTab: 'desktop' }">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-white/10 pb-2.5">
                                <div>
                                    <label class="block text-xs font-semibold text-white flex items-center gap-2">
                                        <span>🧭 Center Watch Position & Size</span>
                                        <span class="text-[10px] text-[#5ea391] font-mono px-1.5 py-0.5 rounded bg-[#5ea391]/10 border border-[#5ea391]/30">All Screen Sizes</span>
                                    </label>
                                    <p class="text-[11px] text-[#8e969d] mt-0.5">Customize horizontal/vertical position and dimensions for Desktop, Tablet, and Mobile. If empty, default positions are used.</p>
                                </div>
                                <!-- Device Tab Buttons -->
                                <div class="inline-flex items-center gap-1 p-1 rounded-lg bg-[#0d1217] border border-white/10 text-[11px] self-start sm:self-auto">
                                    <button type="button" @click="deviceTab = 'desktop'"
                                        :class="deviceTab === 'desktop' ? 'bg-[#cb8665] text-white font-semibold shadow' : 'text-[#8e969d] hover:text-white'"
                                        class="px-2.5 py-1 rounded-md transition-all flex items-center gap-1.5">
                                        <span>🖥️</span> Desktop
                                    </button>
                                    <button type="button" @click="deviceTab = 'tablet'"
                                        :class="deviceTab === 'tablet' ? 'bg-[#cb8665] text-white font-semibold shadow' : 'text-[#8e969d] hover:text-white'"
                                        class="px-2.5 py-1 rounded-md transition-all flex items-center gap-1.5">
                                        <span>📱</span> Tablet
                                    </button>
                                    <button type="button" @click="deviceTab = 'mobile'"
                                        :class="deviceTab === 'mobile' ? 'bg-[#cb8665] text-white font-semibold shadow' : 'text-[#8e969d] hover:text-white'"
                                        class="px-2.5 py-1 rounded-md transition-all flex items-center gap-1.5">
                                        <span>📱</span> Mobile
                                    </button>
                                </div>
                            </div>

                            <!-- Desktop Panel -->
                            <div x-show="deviceTab === 'desktop'" class="space-y-3">
                                <div class="text-[11px] text-[#6b737b] bg-[#0d1217] p-2.5 rounded-lg border border-white/5 flex items-center justify-between">
                                    <span><strong>Desktop Mode:</strong> Positioned within hero banner. Defaults: <strong>Left: 55%</strong>, <strong>Top: 50%</strong> (vertically centered in banner, cards always show below), <strong>Width: 350px</strong>.</span>
                                    <span class="text-[#cb8665] font-mono text-[10px]">≥ 1024px</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-medium text-[#c0c6cc] mb-1">Horizontal Position (Left)</label>
                                        <input type="text" name="hero[position][desktop_left]"
                                            value="{{ old('hero.position.desktop_left', $heroPos['desktop_left'] ?? '') }}"
                                            placeholder="55% (Default)"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0d1217] border border-white/10 text-xs text-white placeholder-[#6b737b] focus:border-[#cb8665] focus:outline-none">
                                        <span class="text-[10px] text-[#6b737b] mt-1 block">e.g. <code>60%</code>, <code>55%</code>, or <code>500px</code></span>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-[#c0c6cc] mb-1">Vertical Position (Top)</label>
                                        <input type="text" name="hero[position][desktop_top]"
                                            value="{{ old('hero.position.desktop_top', $heroPos['desktop_top'] ?? '') }}"
                                            placeholder="50% (Default)"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0d1217] border border-white/10 text-xs text-white placeholder-[#6b737b] focus:border-[#cb8665] focus:outline-none">
                                        <span class="text-[10px] text-[#6b737b] mt-1 block">e.g. <code>110%</code>, <code>100%</code>, or <code>420px</code></span>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-[#c0c6cc] mb-1">Watch Size / Width</label>
                                        <input type="text" name="hero[position][desktop_width]"
                                            value="{{ old('hero.position.desktop_width', $heroPos['desktop_width'] ?? '') }}"
                                            placeholder="350px (Default)"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0d1217] border border-white/10 text-xs text-white placeholder-[#6b737b] focus:border-[#cb8665] focus:outline-none">
                                        <span class="text-[10px] text-[#6b737b] mt-1 block">e.g. <code>380px</code> or <code>420px</code></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tablet Panel -->
                            <div x-show="deviceTab === 'tablet'" class="space-y-3" x-cloak>
                                <div class="text-[11px] text-[#6b737b] bg-[#0d1217] p-2.5 rounded-lg border border-white/5 flex items-center justify-between">
                                    <span><strong>Tablet Mode:</strong> Defaults: <strong>Vertical Shift: -80px</strong>, <strong>Width: 310px</strong>.</span>
                                    <span class="text-[#cb8665] font-mono text-[10px]">640px – 1023px</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-medium text-[#c0c6cc] mb-1">Vertical Shift (Margin Top)</label>
                                        <input type="text" name="hero[position][tablet_top]"
                                            value="{{ old('hero.position.tablet_top', $heroPos['tablet_top'] ?? '') }}"
                                            placeholder="-80px (Default)"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0d1217] border border-white/10 text-xs text-white placeholder-[#6b737b] focus:border-[#cb8665] focus:outline-none">
                                        <span class="text-[10px] text-[#6b737b] mt-1 block">e.g. <code>-80px</code>, <code>-60px</code>, or <code>0px</code></span>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-[#c0c6cc] mb-1">Watch Size / Width</label>
                                        <input type="text" name="hero[position][tablet_width]"
                                            value="{{ old('hero.position.tablet_width', $heroPos['tablet_width'] ?? '') }}"
                                            placeholder="310px (Default)"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0d1217] border border-white/10 text-xs text-white placeholder-[#6b737b] focus:border-[#cb8665] focus:outline-none">
                                        <span class="text-[10px] text-[#6b737b] mt-1 block">e.g. <code>310px</code>, <code>340px</code>, or <code>280px</code></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Panel -->
                            <div x-show="deviceTab === 'mobile'" class="space-y-3" x-cloak>
                                <div class="text-[11px] text-[#6b737b] bg-[#0d1217] p-2.5 rounded-lg border border-white/5 flex items-center justify-between">
                                    <span><strong>Mobile Mode:</strong> Defaults: <strong>Vertical Shift: -48px</strong>, <strong>Width: 260px</strong>.</span>
                                    <span class="text-[#cb8665] font-mono text-[10px]">&lt; 640px</span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-medium text-[#c0c6cc] mb-1">Vertical Shift (Margin Top)</label>
                                        <input type="text" name="hero[position][mobile_top]"
                                            value="{{ old('hero.position.mobile_top', $heroPos['mobile_top'] ?? '') }}"
                                            placeholder="-48px (Default)"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0d1217] border border-white/10 text-xs text-white placeholder-[#6b737b] focus:border-[#cb8665] focus:outline-none">
                                        <span class="text-[10px] text-[#6b737b] mt-1 block">e.g. <code>-48px</code>, <code>-30px</code>, or <code>0px</code></span>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-[#c0c6cc] mb-1">Watch Size / Width</label>
                                        <input type="text" name="hero[position][mobile_width]"
                                            value="{{ old('hero.position.mobile_width', $heroPos['mobile_width'] ?? '') }}"
                                            placeholder="260px (Default)"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0d1217] border border-white/10 text-xs text-white placeholder-[#6b737b] focus:border-[#cb8665] focus:outline-none">
                                        <span class="text-[10px] text-[#6b737b] mt-1 block">e.g. <code>260px</code>, <code>290px</code>, or <code>220px</code></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Primary Button Text</label>
                                <input type="text" name="hero[button_text]" value="{{ old('hero.button_text', $heroContent['button_text'] ?? 'Explore The Collection') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Primary Button Link</label>
                                <input type="text" name="hero[button_url]" value="{{ old('hero.button_url', $heroContent['button_url'] ?? '#collection') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 2: SEO & META TAGS                         -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'seo'" class="space-y-5" x-cloak>
                        @php
                            $seo = $sections['seo'] ?? null;
                            $seoContent = $seo->content ?? [];
                        @endphp
                        <div class="border-b border-white/10 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="font-['Montserrat',sans-serif] text-sm uppercase tracking-wider font-bold text-white flex items-center gap-2">
                                    <span>🔍 Search Engine Optimization (SEO)</span>
                                </h3>
                                <p class="text-[11px] text-[#8e969d] mt-0.5">Control Google search results, browser page tab title, and WhatsApp/social link preview cards.</p>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-mono rounded bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/30">seo</span>
                        </div>

                        <!-- Meta Title -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Browser Tab & Meta Title</label>
                            <input type="text" name="seo[title]" value="{{ old('seo.title', $seo->title ?? 'TIMEYRAH — Precision in Motion | Luxury & Everyday Watches Pakistan') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                placeholder="TIMEYRAH — Precision in Motion">
                        </div>

                        <!-- Meta Subtitle / OpenGraph Description -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Social Share Headline (OpenGraph)</label>
                            <input type="text" name="seo[subtitle]" value="{{ old('seo.subtitle', $seo->subtitle ?? 'Official online boutique for authentic Swiss, Japanese, and international horology in Pakistan.') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                placeholder="Official online boutique...">
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Google Meta Description (Snippet in Search)</label>
                            <textarea name="seo[meta_description]" rows="3"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                placeholder="Shop 100% original watches in Pakistan...">{{ old('seo.meta_description', $seoContent['meta_description'] ?? 'Shop 100% original watches in Pakistan. Featuring Rado, Citizen, Seiko, Casio G-Shock, and Tissot with official brand warranty and Cash on Delivery across Karachi, Lahore, Islamabad, and nationwide.') }}</textarea>
                            <span class="text-[10.5px] text-[#6b737b] mt-1 block">Recommended length: 140–160 characters.</span>
                        </div>

                        <!-- Keywords -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Meta Keywords (Comma separated)</label>
                            <input type="text" name="seo[keywords]" value="{{ old('seo.keywords', $seoContent['keywords'] ?? 'watches pakistan, luxury watches karachi, original watches lahore, casio g shock pakistan, seiko watches, rado captain cook') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none focus:ring-1 focus:ring-[#cb8665] transition-all"
                                placeholder="watches pakistan, cash on delivery watches">
                        </div>

                        <!-- OpenGraph Social Image -->
                        <div class="p-4 rounded-xl bg-[#141b22] border border-white/10 space-y-3"
                            x-data="{
                                previewUrl: '{{ $seo?->image_url ?? asset('images/timeyar-circle-emblem.png') }}',
                                fileName: '',
                                handleFile(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        this.fileName = file.name;
                                        this.previewUrl = URL.createObjectURL(file);
                                    }
                                }
                            }">
                            <label class="block text-xs font-semibold text-white">Social Share Preview Image (WhatsApp, Facebook, Twitter)</label>
                            <div class="flex items-center gap-4">
                                <div class="relative w-20 h-16 rounded-lg bg-[#0b0f12] border border-white/15 flex items-center justify-center p-1 shrink-0 overflow-hidden shadow-inner group">
                                    <img :src="previewUrl"
                                        onerror="this.src='{{ asset('images/timeyar-circle-emblem.png') }}'"
                                        alt="Social Share Preview" class="max-h-full max-w-full object-cover transition-transform group-hover:scale-105">
                                    <template x-if="fileName">
                                        <span class="absolute bottom-1 right-1 w-2.5 h-2.5 rounded-full bg-[#5ea391] ring-2 ring-[#0b0f12]" title="New file chosen"></span>
                                    </template>
                                </div>
                                <div class="flex-1 space-y-1.5">
                                    <input type="file" name="seo_image" accept="image/*"
                                        @change="handleFile($event)"
                                        class="block w-full text-xs text-[#8e969d] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#cb8665]/20 file:text-[#f5be9f] hover:file:bg-[#cb8665]/30 file:cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <template x-if="fileName">
                                            <span class="inline-flex items-center gap-1 text-[11px] text-[#5ea391] font-medium">
                                                <span>✓ Selected:</span>
                                                <strong x-text="fileName"></strong>
                                            </span>
                                        </template>
                                        <template x-if="!fileName">
                                            <p class="text-[11px] text-[#6b737b]">Recommended 1200×630px JPG or PNG.</p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 3: SHOP BY CATEGORY SECTION                -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'categories'" class="space-y-5" x-cloak>
                        @php
                            $catSec = $sections['shop_by_category'] ?? null;
                            $catContent = $catSec->content ?? [];
                            $selectedCatIds = (array) ($catContent['category_ids'] ?? $categories->pluck('id')->all());
                        @endphp
                        <div class="border-b border-white/10 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="font-['Montserrat',sans-serif] text-sm uppercase tracking-wider font-bold text-white flex items-center gap-2">
                                    <span>📂 Shop By Category Section</span>
                                </h3>
                                <p class="text-[11px] text-[#8e969d] mt-0.5">Control headings, badge label, and select which horological categories are displayed.</p>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-mono rounded bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/30">shop_by_category</span>
                        </div>

                        <!-- Section Badge -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Section Badge</label>
                            <input type="text" name="shop_by_category[badge]" value="{{ old('shop_by_category.badge', $catContent['badge'] ?? 'SHOP BY CATEGORY') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                        </div>

                        <!-- Section Heading & Subtitle -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Section Heading</label>
                            <input type="text" name="shop_by_category[title]" value="{{ old('shop_by_category.title', $catSec->title ?? 'THE EXCLUSIVE HOROLOGY COLLECTION') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Subtitle / Description</label>
                            <input type="text" name="shop_by_category[subtitle]" value="{{ old('shop_by_category.subtitle', $catSec->subtitle ?? 'Explore iconic timepieces curated across luxury categories.') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                        </div>

                        <!-- Dynamic Category Showcase Cards -->
                        <div class="space-y-2 pt-2">
                            <label class="block text-xs font-semibold text-white">Dynamic Category Showcase Cards</label>
                            <p class="text-[11px] text-[#8e969d]">Select which categories appear on the storefront, customize subtitles, and choose or upload custom cover photos:</p>
                            <div class="space-y-3">
                                @php
                                    $catMeta = (array) ($catContent['category_meta'] ?? []);
                                @endphp
                                @foreach($categories as $category)
                                    @php
                                        $itemMeta = $catMeta[$category->id] ?? [];
                                        $currentSubtitle = $itemMeta['subtitle'] ?? $category->curated_subtitle;
                                        $currentImgSource = $itemMeta['image_source'] ?? 'curated';
                                        $customImgPath = $itemMeta['image'] ?? null;
                                        $resolvedCover = !empty($customImgPath) ? asset($customImgPath) : $category->cover_image_url;
                                    @endphp
                                    <div class="p-3.5 rounded-xl bg-[#141b22] border border-white/10 space-y-3 hover:border-[#cb8665]/40 transition-colors"
                                        x-data="{
                                            enabled: {{ in_array($category->id, $selectedCatIds) ? 'true' : 'false' }},
                                            imgSource: '{{ $currentImgSource }}',
                                            previewUrl: '{{ $resolvedCover }}',
                                            handleFile(e) {
                                                const file = e.target.files[0];
                                                if (file) {
                                                    this.previewUrl = URL.createObjectURL(file);
                                                    this.imgSource = 'custom';
                                                }
                                            }
                                        }">
                                        <!-- Top Row: Checkbox, Name, Watch Count Badge -->
                                        <div class="flex items-center justify-between gap-3">
                                            <label class="flex items-center gap-2.5 cursor-pointer">
                                                <input type="checkbox" name="shop_by_category[category_ids][]" value="{{ $category->id }}"
                                                    x-model="enabled"
                                                    class="rounded bg-[#0b0f12] border-white/20 text-[#cb8665] focus:ring-0 focus:ring-offset-0">
                                                <span class="text-xs font-bold text-white font-['Montserrat',sans-serif] tracking-wider">{{ $category->name }}</span>
                                            </label>
                                            <span class="text-[10.5px] font-mono px-2 py-0.5 rounded-full bg-white/5 border border-white/10 text-[#4fd1c5]">
                                                {{ $category->products_count ?? $category->products()->count() }} Timepieces
                                            </span>
                                        </div>

                                        <!-- Customizer details for enabled categories -->
                                        <div x-show="enabled" class="space-y-2.5 pt-2 border-t border-white/10" x-transition>
                                            <!-- Subtitle Input -->
                                            <div>
                                                <label class="block text-[11px] text-[#8e969d] mb-1">Card Subtitle / Descriptor</label>
                                                <input type="text"
                                                    name="shop_by_category[category_meta][{{ $category->id }}][subtitle]"
                                                    value="{{ $currentSubtitle }}"
                                                    placeholder="{{ $category->curated_subtitle }}"
                                                    class="w-full px-3 py-1.5 rounded-lg bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                                            </div>

                                            <!-- Image Option & Upload -->
                                            <div class="flex items-center gap-3">
                                                <div class="relative w-16 h-12 rounded-lg bg-[#0b0f12] border border-white/15 overflow-hidden shrink-0 shadow-inner">
                                                    <img :src="previewUrl" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                                </div>

                                                <div class="flex-1 space-y-1.5">
                                                    <div class="flex flex-wrap items-center gap-2 text-[11px] text-[#c0c6cc]">
                                                        <label class="flex items-center gap-1 cursor-pointer">
                                                            <input type="radio" name="shop_by_category[category_meta][{{ $category->id }}][image_source]" value="curated"
                                                                x-model="imgSource" class="text-[#cb8665] bg-[#0b0f12] focus:ring-0 text-[10px]">
                                                            <span>Curated</span>
                                                        </label>
                                                        <label class="flex items-center gap-1 cursor-pointer">
                                                            <input type="radio" name="shop_by_category[category_meta][{{ $category->id }}][image_source]" value="watch"
                                                                x-model="imgSource" class="text-[#cb8665] bg-[#0b0f12] focus:ring-0 text-[10px]">
                                                            <span>Dynamic Watch</span>
                                                        </label>
                                                        <label class="flex items-center gap-1 cursor-pointer">
                                                            <input type="radio" name="shop_by_category[category_meta][{{ $category->id }}][image_source]" value="custom"
                                                                x-model="imgSource" class="text-[#cb8665] bg-[#0b0f12] focus:ring-0 text-[10px]">
                                                            <span>Custom Upload</span>
                                                        </label>
                                                    </div>

                                                    <div x-show="imgSource === 'custom'" class="pt-1">
                                                        <input type="file" name="category_images[{{ $category->id }}]" accept="image/*"
                                                            @change="handleFile($event)"
                                                            class="block w-full text-[10.5px] text-[#8e969d] file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:bg-[#cb8665]/20 file:text-[#f5be9f] hover:file:bg-[#cb8665]/30 file:cursor-pointer">
                                                        @if(!empty($customImgPath))
                                                            <input type="hidden" name="shop_by_category[category_meta][{{ $category->id }}][image]" value="{{ $customImgPath }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 4: PROMO STORY BANNER                      -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'promo'" class="space-y-5" x-cloak>
                        @php
                            $promo = $sections['promo_banner'] ?? null;
                            $promoContent = $promo->content ?? [];
                        @endphp
                        <div class="border-b border-white/10 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="font-['Montserrat',sans-serif] text-sm uppercase tracking-wider font-bold text-white flex items-center gap-2">
                                    <span>🚀 Promo Story Banner ("Make Your Mark")</span>
                                </h3>
                                <p class="text-[11px] text-[#8e969d] mt-0.5">Middle-of-page full-width luxury promotional callout banner.</p>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-mono rounded bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/30">promo_banner</span>
                        </div>

                        <!-- Badge & Accent -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Promo Eyebrow Badge</label>
                                <input type="text" name="promo[badge]" value="{{ old('promo.badge', $promoContent['badge'] ?? 'CRAFTSMANSHIP & HERITAGE') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Warranty Accent Tag</label>
                                <input type="text" name="promo[accent_badge]" value="{{ old('promo.accent_badge', $promoContent['accent_badge'] ?? 'OFFICIAL WARRANTY INCLUDED') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-[#5ea391] focus:border-[#5ea391] focus:outline-none transition-all">
                            </div>
                        </div>

                        <!-- Heading -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Banner Main Headline</label>
                            <input type="text" name="promo[title]" value="{{ old('promo.title', $promo->title ?? 'MAKE YOUR MARK WITH TIMEYRAH') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                        </div>

                        <!-- Subtitle / Story -->
                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Banner Story Description</label>
                            <textarea name="promo[subtitle]" rows="3"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">{{ old('promo.subtitle', $promo->subtitle ?? 'Every second tells a story. Craft yours with an authentic timepiece delivered to your doorstep across Pakistan.') }}</textarea>
                        </div>

                        <!-- Button Text & Link -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Button Text</label>
                                <input type="text" name="promo[button_text]" value="{{ old('promo.button_text', $promoContent['button_text'] ?? 'Discover The Atelier') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Button URL</label>
                                <input type="text" name="promo[button_url]" value="{{ old('promo.button_url', $promoContent['button_url'] ?? '/shop') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                            </div>
                        </div>

                        <!-- Promo Watch Image Upload -->
                        <div class="p-4 rounded-xl bg-[#141b22] border border-white/10 space-y-3"
                            x-data="{
                                previewUrl: '{{ $promo?->image_url ?? asset('images/make-banner.jpg') }}',
                                fileName: '',
                                handleFile(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        this.fileName = file.name;
                                        this.previewUrl = URL.createObjectURL(file);
                                    }
                                }
                            }">
                            <label class="block text-xs font-semibold text-white">Promo Watch Accent Image</label>
                            <div class="flex items-center gap-4">
                                <div class="relative w-20 h-20 rounded-xl bg-[#091518] border border-[#cb8665]/40 flex items-center justify-center p-2 shrink-0 overflow-hidden shadow-inner group">
                                    <img :src="previewUrl"
                                        onerror="this.src='{{ asset('images/make-banner.jpg') }}'"
                                        alt="Promo Image Preview" class="max-h-full max-w-full object-contain transition-transform group-hover:scale-105">
                                    <template x-if="fileName">
                                        <span class="absolute bottom-1 right-1 w-2.5 h-2.5 rounded-full bg-[#5ea391] ring-2 ring-[#091518]" title="New file chosen"></span>
                                    </template>
                                </div>
                                <div class="flex-1 space-y-1.5">
                                    <input type="file" name="promo_image" accept="image/*"
                                        @change="handleFile($event)"
                                        class="block w-full text-xs text-[#8e969d] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#cb8665]/20 file:text-[#f5be9f] hover:file:bg-[#cb8665]/30 file:cursor-pointer">
                                    <div class="flex items-center gap-2">
                                        <template x-if="fileName">
                                            <span class="inline-flex items-center gap-1 text-[11px] text-[#5ea391] font-medium">
                                                <span>✓ Selected:</span>
                                                <strong x-text="fileName"></strong>
                                            </span>
                                        </template>
                                        <template x-if="!fileName">
                                            <p class="text-[11px] text-[#6b737b]">Saved directly to <code class="text-[#cb8665]">public/uploads/pages/</code>.</p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 5: FEATURED CURATED WATCHES                -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'featured'" class="space-y-5" x-cloak>
                        @php
                            $featSec = $sections['featured_watches'] ?? null;
                            $featContent = $featSec->content ?? [];
                        @endphp
                        <div class="border-b border-white/10 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="font-['Montserrat',sans-serif] text-sm uppercase tracking-wider font-bold text-white flex items-center gap-2">
                                    <span>⌚ Featured Releases Section</span>
                                </h3>
                                <p class="text-[11px] text-[#8e969d] mt-0.5">Control the 3D interactive watch carousel headings and maximum item count.</p>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-mono rounded bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/30">featured_watches</span>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Section Eyebrow Badge</label>
                            <input type="text" name="featured_watches[badge]" value="{{ old('featured_watches.badge', $featContent['badge'] ?? 'FEATURED RELEASES') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Section Title</label>
                            <input type="text" name="featured_watches[title]" value="{{ old('featured_watches.title', $featSec->title ?? 'CURATED ATELIER TIMEPIECES') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Section Subtitle</label>
                            <input type="text" name="featured_watches[subtitle]" value="{{ old('featured_watches.subtitle', $featSec->subtitle ?? 'Handpicked precision movements and iconic complications available with nationwide Cash on Delivery.') }}"
                                class="w-full px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Maximum Watches to Display</label>
                            <input type="number" min="4" max="24" step="1" name="featured_watches[limit]" value="{{ old('featured_watches.limit', $featContent['limit'] ?? 8) }}"
                                class="w-32 px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 6: FOOTER & FOOTER SECTION CUSTOMIZER      -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'footer'" class="space-y-6" x-cloak>
                        @php
                            $footerSec = $sections['footer_section'] ?? null;
                            $footerContent = (array) ($footerSec?->content ?? []);
                            $mainFooterSec = $sections['main_footer'] ?? null;
                            $mainFooterContent = (array) ($mainFooterSec?->content ?? []);
                        @endphp

                        <!-- 1. Upper Footer Section ("Find Your Style" Cartouche) -->
                        <div class="p-5 rounded-2xl bg-[#141b22] border border-white/10 space-y-4">
                            <div class="border-b border-white/10 pb-3 flex items-center justify-between">
                                <div>
                                    <h3 class="font-['Montserrat',sans-serif] text-sm uppercase tracking-wider font-bold text-[#f5be9f] flex items-center gap-2">
                                        <span>💎 Upper Footer Section (Categories Cartouche)</span>
                                    </h3>
                                    <p class="text-[11px] text-[#8e969d] mt-0.5">
                                        Customizes the luxury "Find Your Style" cartouche, category cards, and promotional ribbon.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Eyebrow Tag Badge</label>
                                    <input type="text" name="footer_section[badge]" value="{{ old('footer_section.badge', $footerContent['badge'] ?? 'SHOP BY CATEGORY') }}"
                                        class="w-full px-4 py-2.5 rounded-xl bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Heading Title</label>
                                    <input type="text" name="footer_section[title]" value="{{ old('footer_section.title', $footerSec?->title ?? 'Find Your Style') }}"
                                        class="w-full px-4 py-2.5 rounded-xl bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Subtitle / Description</label>
                                <input type="text" name="footer_section[subtitle]" value="{{ old('footer_section.subtitle', $footerSec?->subtitle ?? 'Curated horological collections across luxury materials and movements.') }}"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                            </div>

                            <!-- Promotional Ribbon Badge Settings -->
                            <div class="pt-3 border-t border-white/10 space-y-3"
                                x-data="{
                                    showRibbon: {{ old('footer_section.show_discount_badge', $footerContent['show_discount_badge'] ?? true) ? 'true' : 'false' }},
                                    dealPrefix: '{{ addslashes(old('footer_section.discount_text', $footerContent['discount_text'] ?? 'Save up to')) }}',
                                    dealHighlight: '{{ addslashes(old('footer_section.discount_percent', $footerContent['discount_percent'] ?? '25%')) }}'
                                }">
                                <div class="flex items-center justify-between gap-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="footer_section[show_discount_badge]" value="0">
                                        <input type="checkbox" name="footer_section[show_discount_badge]" value="1"
                                            x-model="showRibbon"
                                            class="rounded bg-[#0b0f12] border-white/20 text-[#5ea391] focus:ring-0">
                                        <span class="text-xs font-semibold text-white">
                                            Show Center Ribbon Deal Banner
                                        </span>
                                    </label>
                                    <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-white/5 border border-white/10 text-[#8e969d]"
                                        x-text="showRibbon ? 'Visible' : 'Hidden'"></span>
                                </div>

                                <!-- Live Interactive Deal Ribbon Preview -->
                                <div class="p-3 rounded-xl bg-[#0b0f12] border border-white/10 flex flex-col sm:flex-row items-center justify-between gap-2.5 transition-all"
                                    x-show="showRibbon">
                                    <div class="flex items-center gap-1.5 text-[11px] text-[#8e969d]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#cb8665]"></span>
                                        <span>Ribbon Banner Live Preview:</span>
                                    </div>
                                    <div class="px-5 py-2 rounded-full bg-gradient-to-r from-[#2a170e] via-[#1a0e08] to-[#2a170e] border border-[#cb8665]/60 text-xs font-['Outfit',sans-serif] tracking-wide text-[#e8d5ca] shadow-[0_2px_12px_rgba(203,134,101,0.25)] flex items-center gap-1.5">
                                        <span x-text="dealPrefix || 'Exclusive Deal'"></span>
                                        <span class="text-[#f5be9f] font-bold text-sm" x-text="dealHighlight" x-show="dealHighlight"></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3" x-show="showRibbon">
                                    <div>
                                        <label class="block text-[11px] text-[#8e969d] mb-1">Deal Headline / Prefix</label>
                                        <input type="text" name="footer_section[discount_text]"
                                            x-model="dealPrefix"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none"
                                            placeholder="e.g. Save up to, Special Offer, Flat">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-[#8e969d] mb-1">Highlighted Deal / Value</label>
                                        <input type="text" name="footer_section[discount_percent]"
                                            x-model="dealHighlight"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none"
                                            placeholder="e.g. 25%, 50% OFF, PKR 5,000">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-[#8e969d] mb-1">Banner Link URL</label>
                                        <input type="text" name="footer_section[discount_url]" value="{{ old('footer_section.discount_url', $footerContent['discount_url'] ?? '/shop') }}"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none"
                                            placeholder="e.g. /shop or /shop?discount=deal">
                                    </div>
                                </div>
                            </div>

                            <!-- Cartouche Background & Visual Theme -->
                            <div class="pt-3 border-t border-white/10"
                                x-data="{
                                    bgPreview: '{{ !empty($footerContent['cartouche_bg_image']) ? asset($footerContent['cartouche_bg_image']) : asset('images/footer/bronze_bg.jpg') }}',
                                    handleBg(e) {
                                        const file = e.target.files[0];
                                        if (file) {
                                            this.bgPreview = URL.createObjectURL(file);
                                        }
                                    }
                                }">
                                <label class="block text-xs font-semibold text-white mb-1.5">Cartouche Ornate Horology Background</label>
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#0b0f12] border border-white/10">
                                    <div class="w-20 h-12 rounded-lg bg-black border border-white/15 overflow-hidden shrink-0 shadow-inner">
                                        <img :src="bgPreview" alt="Cartouche Background" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <input type="file" name="footer_cartouche_bg" @change="handleBg" accept="image/*"
                                            class="block w-full text-[11px] text-[#8e969d] file:mr-2.5 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-[10.5px] file:font-semibold file:bg-[#cb8665]/20 file:text-[#f5be9f] hover:file:bg-[#cb8665]/30 file:cursor-pointer">
                                        <p class="text-[10px] text-[#8e969d]">Leave empty to retain current antique bronze horology gear background.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic 4-Category Showcase Cards -->
                            <div class="pt-3 border-t border-white/10 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="block text-xs font-semibold text-white">4 Dynamic Showcase Cards</label>
                                        <p class="text-[10.5px] text-[#8e969d]">Customize the 4 cards inside the "Find Your Style" frame with your own titles, photos, and destinations.</p>
                                    </div>
                                    <span class="px-2 py-0.5 text-[10px] font-mono rounded bg-white/5 border border-white/10 text-[#cb8665]">4 Cards Grid</span>
                                </div>

                                @php
                                    $defaultFooterCards = [
                                        [
                                            'title' => 'Automatic Watches',
                                            'button_text' => 'Shop Now',
                                            'link_url' => '/shop?category=automatic',
                                            'image' => 'images/footer/cat_sports.jpg',
                                            'accent_color' => '#9eb5b3',
                                        ],
                                        [
                                            'title' => 'Leather & Strap',
                                            'button_text' => 'Shop Now',
                                            'link_url' => '/shop?category=strap',
                                            'image' => 'images/footer/cat_limited.jpg',
                                            'accent_color' => '#d5a48e',
                                        ],
                                        [
                                            'title' => 'Steel & Titanium',
                                            'button_text' => 'Shop Now',
                                            'link_url' => '/shop?category=metal-chain',
                                            'image' => 'images/footer/cat_mens.jpg',
                                            'accent_color' => '#5cb9a5',
                                        ],
                                        [
                                            'title' => 'Precision Quartz',
                                            'button_text' => 'Shop Now',
                                            'link_url' => '/shop?category=quartz',
                                            'image' => 'images/footer/cat_womens.jpg',
                                            'accent_color' => '#e4ab91',
                                        ],
                                    ];
                                    $savedCards = (array) ($footerContent['cards'] ?? []);
                                @endphp

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @for ($i = 0; $i < 4; $i++)
                                        @php
                                            $def = $defaultFooterCards[$i];
                                            $c = $savedCards[$i] ?? [];
                                            $cTitle = $c['title'] ?? $def['title'];
                                            $cBtn = $c['button_text'] ?? $def['button_text'];
                                            $cLink = $c['link_url'] ?? $def['link_url'];
                                            $cImg = $c['image'] ?? $def['image'];
                                            $cImgUrl = !empty($cImg) ? (str_starts_with($cImg, 'http') ? $cImg : asset($cImg)) : asset($def['image']);
                                            $cAccent = $c['accent_color'] ?? $def['accent_color'];
                                        @endphp
                                        <div class="p-3.5 rounded-xl bg-[#0b0f12] border border-white/10 space-y-2.5 hover:border-[#cb8665]/40 transition-colors"
                                            x-data="{
                                                cardPreview: '{{ $cImgUrl }}',
                                                handleCardFile(e) {
                                                    const file = e.target.files[0];
                                                    if (file) {
                                                        this.cardPreview = URL.createObjectURL(file);
                                                    }
                                                }
                                            }">
                                            <div class="flex items-center justify-between border-b border-white/10 pb-2">
                                                <span class="text-xs font-bold text-white flex items-center gap-1.5 font-['Montserrat',sans-serif]">
                                                    <span class="w-4 h-4 rounded-full bg-[#cb8665]/20 text-[#f5be9f] text-[10px] flex items-center justify-center font-mono font-bold">{{ $i + 1 }}</span>
                                                    <span>Card {{ $i + 1 }}</span>
                                                </span>
                                                <span class="w-3 h-3 rounded-full border" style="background-color: {{ $cAccent }}; border-color: rgba(255,255,255,0.2)"></span>
                                            </div>

                                            <!-- Thumbnail & File Picker -->
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-14 h-14 rounded-lg bg-[#141b22] border border-white/15 overflow-hidden shrink-0 shadow-inner">
                                                    <img :src="cardPreview" alt="Card Preview" class="w-full h-full object-cover">
                                                </div>
                                                <div class="flex-1 space-y-1">
                                                    <input type="hidden" name="footer_section[cards][{{ $i }}][image]" value="{{ $cImg }}">
                                                    <input type="file" name="footer_card_images[{{ $i }}]" @change="handleCardFile" accept="image/*"
                                                        class="block w-full text-[10.5px] text-[#8e969d] file:mr-2 file:py-0.5 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-[#cb8665]/20 file:text-[#f5be9f] hover:file:bg-[#cb8665]/30 file:cursor-pointer">
                                                    <p class="text-[9.5px] text-[#8e969d]">Choose image file to replace photo</p>
                                                </div>
                                            </div>

                                            <!-- Card Title -->
                                            <div>
                                                <label class="block text-[10.5px] text-[#8e969d] mb-1">Card Title</label>
                                                <input type="text" name="footer_section[cards][{{ $i }}][title]" value="{{ $cTitle }}"
                                                    class="w-full px-3 py-1.5 rounded-lg bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none">
                                            </div>

                                            <!-- Button Text & Accent -->
                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <label class="block text-[10.5px] text-[#8e969d] mb-1">Button Text</label>
                                                    <input type="text" name="footer_section[cards][{{ $i }}][button_text]" value="{{ $cBtn }}"
                                                        class="w-full px-3 py-1.5 rounded-lg bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none"
                                                        placeholder="Shop Now">
                                                </div>
                                                <div>
                                                    <label class="block text-[10.5px] text-[#8e969d] mb-1">Accent Hex</label>
                                                    <input type="text" name="footer_section[cards][{{ $i }}][accent_color]" value="{{ $cAccent }}"
                                                        class="w-full px-3 py-1.5 rounded-lg bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none font-mono text-[11px]"
                                                        placeholder="#d5a48e">
                                                </div>
                                            </div>

                                            <!-- Link Destination -->
                                            <div>
                                                <div class="flex items-center justify-between mb-1">
                                                    <label class="text-[10.5px] text-[#8e969d]">Destination Link URL</label>
                                                    <span class="text-[9.5px] text-[#cb8665] cursor-pointer hover:underline"
                                                        @click="$el.parentElement.nextElementSibling.value = '/shop'">Reset URL</span>
                                                </div>
                                                <input type="text" name="footer_section[cards][{{ $i }}][link_url]" value="{{ $cLink }}"
                                                    class="w-full px-3 py-1.5 rounded-lg bg-[#141b22] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none"
                                                    placeholder="/shop?category=...">
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- 2. Main Footer (Branding, Links & Legal) -->
                        <div class="p-5 rounded-2xl bg-[#141b22] border border-white/10 space-y-4">
                            <div class="border-b border-white/10 pb-3 flex items-center justify-between">
                                <div>
                                    <h3 class="font-['Montserrat',sans-serif] text-sm uppercase tracking-wider font-bold text-[#f5be9f] flex items-center gap-2">
                                        <span>🏛️ Main Footer (Branding, Links & Legal)</span>
                                    </h3>
                                    <p class="text-[11px] text-[#8e969d] mt-0.5">
                                        Configure brand wordmark, manifesto, social links, column visibility, and copyright.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Brand Wordmark</label>
                                    <input type="text" name="main_footer[title]" value="{{ old('main_footer.title', $mainFooterSec?->title ?? 'TIMEYRAH') }}"
                                        class="w-full px-4 py-2.5 rounded-xl bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Sub-Badge Tag</label>
                                    <input type="text" name="main_footer[subtitle]" value="{{ old('main_footer.subtitle', $mainFooterSec?->subtitle ?? 'SWISS') }}"
                                        class="w-full px-4 py-2.5 rounded-xl bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Brand Manifesto / Tagline</label>
                                <textarea name="main_footer[tagline]" rows="2"
                                    class="w-full px-4 py-2.5 rounded-xl bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all font-mono text-[11px]">{{ old('main_footer.tagline', $mainFooterContent['tagline'] ?? "PRECISION CHRONOGRAPHS.\nBORN OF OBSIDIAN TEAL AND BRONZE MASTERY.") }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Copyright Notice</label>
                                    <input type="text" name="main_footer[copyright]" value="{{ old('main_footer.copyright', $mainFooterContent['copyright'] ?? '© 2026 TimeYrah. All rights reserved.') }}"
                                        class="w-full px-4 py-2.5 rounded-xl bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-[#c0c6cc] mb-1.5">Concierge Support Email</label>
                                    <input type="email" name="main_footer[contact_email]" value="{{ old('main_footer.contact_email', $mainFooterContent['contact_email'] ?? 'concierge@timeyrah.com') }}"
                                        class="w-full px-4 py-2.5 rounded-xl bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none transition-all">
                                </div>
                            </div>

                            <!-- Social Links -->
                            <div class="pt-3 border-t border-white/10 space-y-3">
                                <label class="block text-xs font-semibold text-white">Social Media Profiles</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                    <div>
                                        <label class="block text-[11px] text-[#8e969d] mb-1">Instagram URL</label>
                                        <input type="url" name="main_footer[social_instagram]" value="{{ old('main_footer.social_instagram', $mainFooterContent['social_instagram'] ?? 'https://instagram.com') }}"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-[#8e969d] mb-1">Facebook URL</label>
                                        <input type="url" name="main_footer[social_facebook]" value="{{ old('main_footer.social_facebook', $mainFooterContent['social_facebook'] ?? 'https://facebook.com') }}"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-[#8e969d] mb-1">X / Twitter URL</label>
                                        <input type="url" name="main_footer[social_twitter]" value="{{ old('main_footer.social_twitter', $mainFooterContent['social_twitter'] ?? 'https://x.com') }}"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] text-[#8e969d] mb-1">YouTube URL</label>
                                        <input type="url" name="main_footer[social_youtube]" value="{{ old('main_footer.social_youtube', $mainFooterContent['social_youtube'] ?? 'https://youtube.com') }}"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-[11px] text-[#8e969d] mb-1">LinkedIn URL</label>
                                        <input type="url" name="main_footer[social_linkedin]" value="{{ old('main_footer.social_linkedin', $mainFooterContent['social_linkedin'] ?? 'https://linkedin.com') }}"
                                            class="w-full px-3 py-2 rounded-lg bg-[#0b0f12] border border-white/10 text-xs text-white focus:border-[#cb8665] focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- Column Toggles -->
                            <div class="pt-3 border-t border-white/10">
                                <label class="block text-xs font-semibold text-white mb-2">Footer Columns Visibility</label>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                    <label class="p-2 rounded-lg bg-[#0b0f12] border border-white/10 flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="main_footer[show_collections]" value="0">
                                        <input type="checkbox" name="main_footer[show_collections]" value="1"
                                            {{ old('main_footer.show_collections', $mainFooterContent['show_collections'] ?? true) ? 'checked' : '' }}
                                            class="rounded bg-[#141b22] border-white/20 text-[#5ea391] focus:ring-0">
                                        <span class="text-[11px] text-[#d1d6da]">Collections</span>
                                    </label>
                                    <label class="p-2 rounded-lg bg-[#0b0f12] border border-white/10 flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="main_footer[show_company]" value="0">
                                        <input type="checkbox" name="main_footer[show_company]" value="1"
                                            {{ old('main_footer.show_company', $mainFooterContent['show_company'] ?? true) ? 'checked' : '' }}
                                            class="rounded bg-[#141b22] border-white/20 text-[#5ea391] focus:ring-0">
                                        <span class="text-[11px] text-[#d1d6da]">Company</span>
                                    </label>
                                    <label class="p-2 rounded-lg bg-[#0b0f12] border border-white/10 flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="main_footer[show_support]" value="0">
                                        <input type="checkbox" name="main_footer[show_support]" value="1"
                                            {{ old('main_footer.show_support', $mainFooterContent['show_support'] ?? true) ? 'checked' : '' }}
                                            class="rounded bg-[#141b22] border-white/20 text-[#5ea391] focus:ring-0">
                                        <span class="text-[11px] text-[#d1d6da]">Support</span>
                                    </label>
                                    <label class="p-2 rounded-lg bg-[#0b0f12] border border-white/10 flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="main_footer[show_connect]" value="0">
                                        <input type="checkbox" name="main_footer[show_connect]" value="1"
                                            {{ old('main_footer.show_connect', $mainFooterContent['show_connect'] ?? true) ? 'checked' : '' }}
                                            class="rounded bg-[#141b22] border-white/20 text-[#5ea391] focus:ring-0">
                                        <span class="text-[11px] text-[#d1d6da]">Connect/Legal</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================== -->
                    <!-- TAB 7: SECTION BELOW SECTION (ORDER & TOGGLE)   -->
                    <!-- ============================================== -->
                    <div x-show="activeTab === 'sections'" class="space-y-5" x-cloak>
                        <div class="border-b border-white/10 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="font-['Montserrat',sans-serif] text-sm uppercase tracking-wider font-bold text-white flex items-center gap-2">
                                    <span>↕️ Section Below Section (Order & Toggle)</span>
                                </h3>
                                <p class="text-[11px] text-[#8e969d] mt-0.5">
                                    Rearrange what section appears above/below another on your homepage, and turn sections on or off.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(secKey, index) in sectionsOrder" :key="secKey">
                                <div class="p-4 rounded-xl bg-[#141b22] border border-white/10 flex items-center justify-between gap-3 shadow-md hover:border-[#cb8665]/40 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <!-- Index & Drag Handle Simulation -->
                                        <div class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center font-mono text-xs font-bold text-[#cb8665]">
                                            <span x-text="index + 1"></span>
                                        </div>

                                        <div>
                                            <input type="hidden" name="section_order[]" :value="secKey">
                                            <span class="text-xs font-semibold text-white block capitalize"
                                                x-text="sectionLabels[secKey] || secKey.replace(/_/g, ' ')"></span>
                                            <span class="text-[10px] font-mono text-[#8e969d]" x-text="'section_key: ' + secKey"></span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <!-- Reorder Buttons -->
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click="reorderUp(index)"
                                                :disabled="index === 0"
                                                class="w-7 h-7 rounded bg-white/5 hover:bg-white/15 disabled:opacity-30 disabled:cursor-not-allowed text-[#cb8665] flex items-center justify-center transition-colors">
                                                ▲
                                            </button>
                                            <button type="button" @click="reorderDown(index)"
                                                :disabled="index === sectionsOrder.length - 1"
                                                class="w-7 h-7 rounded bg-white/5 hover:bg-white/15 disabled:opacity-30 disabled:cursor-not-allowed text-[#cb8665] flex items-center justify-center transition-colors">
                                                ▼
                                            </button>
                                        </div>

                                        <!-- Visibility Checkbox Switch -->
                                        <label class="flex items-center gap-1.5 ml-2 pl-3 border-l border-white/10 cursor-pointer">
                                            <input type="checkbox" :name="'section_visibility[' + secKey + ']'" value="1"
                                                :checked="activeMap[secKey] ?? false"
                                                @change="activeMap[secKey] = $el.checked"
                                                class="rounded bg-[#0b0f12] border-white/20 text-[#5ea391] focus:ring-0 focus:ring-offset-0">
                                            <span class="text-[11px] text-[#8e969d]">Visible</span>
                                        </label>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Side Panel Footer Button -->
                    <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                        <span class="text-[11px] text-[#8e969d]">Changes are saved to database and live immediately.</span>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all shadow-[0_6px_20px_rgba(203,134,101,0.35)] flex items-center gap-2">
                            <span>Save & Publish</span>
                            <span>→</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- ========================================== -->
            <!-- RIGHT COLUMN: LIVE RESPONSIVE PREVIEW      -->
            <!-- ========================================== -->
            <div class="lg:col-span-7 xl:col-span-7 sticky top-20 space-y-3">
                <div class="p-4 rounded-2xl bg-[#0e1419] border border-white/10 flex flex-wrap items-center justify-between gap-3 shadow-lg">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#5ea391] animate-ping"></span>
                        <span class="text-xs font-semibold text-white">Live Storefront Preview</span>
                        <span class="text-[11px] text-[#8e969d] font-mono hidden sm:inline">127.0.0.1:8000/</span>
                    </div>

                    <!-- Device Switcher Controls -->
                    <div class="flex items-center gap-2">
                        <div class="inline-flex rounded-lg p-1 bg-[#141b22] border border-white/10 text-xs">
                            <button type="button" @click="previewDevice = 'desktop'"
                                :class="previewDevice === 'desktop' ? 'bg-[#cb8665] text-[#0e1215] font-bold' : 'text-[#8e969d] hover:text-white'"
                                class="px-2.5 py-1 rounded transition-all flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                                <span class="hidden md:inline">Desktop</span>
                            </button>
                            <button type="button" @click="previewDevice = 'tablet'"
                                :class="previewDevice === 'tablet' ? 'bg-[#cb8665] text-[#0e1215] font-bold' : 'text-[#8e969d] hover:text-white'"
                                class="px-2.5 py-1 rounded transition-all flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="4" y="2" width="16" height="20" rx="2"></rect>
                                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                                </svg>
                                <span class="hidden md:inline">Tablet</span>
                            </button>
                            <button type="button" @click="previewDevice = 'mobile'"
                                :class="previewDevice === 'mobile' ? 'bg-[#cb8665] text-[#0e1215] font-bold' : 'text-[#8e969d] hover:text-white'"
                                class="px-2.5 py-1 rounded transition-all flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="5" y="2" width="14" height="20" rx="2"></rect>
                                    <line x1="12" y1="18" x2="12.01" y2="18"></line>
                                </svg>
                                <span class="hidden md:inline">Mobile</span>
                            </button>
                        </div>

                        <!-- Reload Button -->
                        <button type="button" @click="reloadIframe()"
                            class="p-2 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-[#8e969d] hover:text-white transition-colors"
                            title="Refresh Preview Frame">
                            <svg class="w-3.5 h-3.5 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Live Iframe Viewport Frame -->
                <div class="rounded-2xl bg-[#090d10] border border-white/15 p-2 sm:p-3 shadow-2xl flex justify-center overflow-hidden">
                    <div :class="{
                        'w-full': previewDevice === 'desktop',
                        'w-[768px]': previewDevice === 'tablet',
                        'w-[375px]': previewDevice === 'mobile'
                    }" class="transition-all duration-300 rounded-xl overflow-hidden border border-white/10 bg-[#0b0f12]">
                        <iframe id="previewIframe" src="{{ route('home') }}?v={{ time() }}"
                            class="w-full h-[760px] border-0 select-none shadow-inner"
                            title="Live Storefront Preview"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
