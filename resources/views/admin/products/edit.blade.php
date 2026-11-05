<x-admin-layout title="Edit Watch — {{ $product->title }}">
    <div class="max-w-5xl mx-auto space-y-8"
        x-data="{
            newPreviews: [],
            customSpecs: [],
            addCustomSpec() {
                this.customSpecs.push({ name: '', value: '' });
            },
            removeCustomSpec(index) {
                this.customSpecs.splice(index, 1);
            },
            handleNewFiles(e) {
                const files = e.target.files;
                this.newPreviews = [];
                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        this.newPreviews.push(event.target.result);
                    };
                    reader.readAsDataURL(files[i]);
                }
            }
        }">

        <!-- Top Breadcrumb & Heading -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#8e969d] mb-2">
                    <a href="{{ route('admin.products.index') }}" class="hover:text-[#cb8665] transition-colors">Watches</a>
                    <span>/</span>
                    <span class="text-[#cb8665] font-medium">Edit Watch</span>
                </nav>
                <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl font-normal text-white tracking-tight">
                    Edit Timepiece: {{ $product->title }}
                </h1>
                <p class="text-xs sm:text-sm text-[#8e969d] mt-1">
                    Update technical specifications, modify stock or pricing, manage photos, and toggle store visibility.
                </p>
            </div>

            <div class="flex items-center gap-3 self-start sm:self-auto">
                <a href="{{ route('product.show', $product->slug ?: $product->id) }}" target="_blank"
                    class="veltra-auth-secondary-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/15 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-white hover:border-[#cb8665] transition-all">
                    <span>View in Shop ↗</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                    class="veltra-auth-secondary-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#141b22] border border-[#cb8665]/35 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#f5be9f] hover:border-[#cb8665] hover:text-white transition-all">
                    <span>← Back</span>
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1: Core Watch Details -->
            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center text-xs font-bold font-['Montserrat',sans-serif]">1</span>
                        <span>Basic Information</span>
                    </h2>
                    <span class="text-[11px] text-[#cb8665] uppercase tracking-wider font-mono">ID: #{{ $product->id }}</span>
                </div>

                <div class="space-y-5">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            Watch Model Title *
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $product->title) }}" required
                            class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] outline-none">
                    </div>

                    <!-- Brand & Custom SKU -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="brand_id" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                Brand / Manufacture *
                            </label>
                            <select id="brand_id" name="brand_id" required
                                class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white outline-none">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sku" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                SKU / Stock Code *
                            </label>
                            <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required
                                class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] outline-none font-mono">
                        </div>
                    </div>

                    <!-- Category Multi-Checkboxes -->
                    @php
                        $selectedCatIds = old('category_ids', $product->categories->pluck('id')->all());
                    @endphp
                    <div>
                        <label class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-2 font-semibold">
                            Collection & Filter Categories *
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2.5">
                            @foreach ($categories as $cat)
                                <label class="veltra-dash-subcard flex items-center gap-2.5 p-3 rounded-xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/40 cursor-pointer transition-colors">
                                    <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                                        @checked(in_array($cat->id, $selectedCatIds))
                                        class="rounded text-[#cb8665] bg-[#141b22] border-white/20 focus:ring-[#cb8665]">
                                    <span class="text-xs font-medium text-white truncate">{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Stock Inventory -->
            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center text-xs font-bold font-['Montserrat',sans-serif]">2</span>
                        <span>Pricing & Inventory</span>
                    </h2>
                    <span class="text-xs text-[#8e969d]">Authoritative store values</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="price" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            Regular Price (USD) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-sm text-[#cb8665] font-mono">$</span>
                            <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $product->price) }}" required
                                class="veltra-auth-input w-full pl-8 pr-4 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white font-mono outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="sale_price" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            Sale Price (USD) <span class="text-[#8e969d] lowercase">(leave empty if regular)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-sm text-[#5ea391] font-mono">$</span>
                            <input type="number" step="0.01" min="0" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                                class="veltra-auth-input w-full pl-8 pr-4 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#5ea391] text-sm text-white font-mono outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="stock" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            Available Stock Units *
                        </label>
                        <input type="number" min="0" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required
                            class="veltra-auth-input w-full px-4 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white font-mono outline-none">
                    </div>
                </div>
            </div>

            <!-- Section 3: Technical Specifications & Horology Details -->
            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center text-xs font-bold font-['Montserrat',sans-serif]">3</span>
                        <span>Technical Specifications</span>
                    </h2>
                    <a href="{{ route('admin.specifications.index') }}" target="_blank"
                        class="text-xs text-[#cb8665] hover:text-[#f5be9f] flex items-center gap-1 font-semibold transition-colors">
                        <span>Manage Spec Attributes ↗</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($specAttributes as $attr)
                        @php
                            $defaultVal = $productSpecs[$attr->id] ?? match($attr->slug) {
                                'movement' => $product->movement,
                                'case-size' => $product->case_size,
                                'case-material' => $product->case_material,
                                'strap-type' => $product->strap_type,
                                'crystal-glass' => $product->glass,
                                'water-resistance' => $product->water_resistance,
                                'warranty' => $product->warranty,
                                default => null,
                            };
                        @endphp
                        <div>
                            <label for="spec_{{ $attr->id }}" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                {{ $attr->name }}
                            </label>
                            <input type="text" id="spec_{{ $attr->id }}" name="specs[{{ $attr->id }}]"
                                list="spec_datalist_{{ $attr->id }}"
                                value="{{ old('specs.' . $attr->id, $defaultVal) }}"
                                placeholder="Choose option or type custom..."
                                class="veltra-auth-input w-full px-4 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#4e555d] outline-none">
                            <datalist id="spec_datalist_{{ $attr->id }}">
                                @foreach ($attr->values as $val)
                                    <option value="{{ $val->value }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    @endforeach

                    <div>
                        <label for="gender" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            Target Gender
                        </label>
                        <select id="gender" name="gender"
                            class="veltra-auth-input w-full px-4 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white outline-none">
                            <option value="Men's" @selected(old('gender', $product->gender) === "Men's")>Men's</option>
                            <option value="Women's" @selected(old('gender', $product->gender) === "Women's")>Women's</option>
                            <option value="Unisex" @selected(old('gender', $product->gender) === 'Unisex')>Unisex</option>
                        </select>
                    </div>

                    <div>
                        <label for="badge" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            Highlight Badge (Optional)
                        </label>
                        <input type="text" id="badge" name="badge" value="{{ old('badge', $product->badge) }}"
                            placeholder="e.g. Best Seller / New Arrival / Solar"
                            class="veltra-auth-input w-full px-4 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#4e555d] outline-none">
                    </div>
                </div>

                <!-- Custom Specifications Adder -->
                <div class="pt-4 border-t border-white/10 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] font-semibold">
                            Additional Custom Specifications:
                        </span>
                        <button type="button" @click="addCustomSpec"
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/40 text-xs font-semibold hover:bg-[#cb8665]/30 cursor-pointer">
                            <span>+ Add Custom Spec Field</span>
                        </button>
                    </div>

                    <div class="space-y-2.5" x-show="customSpecs.length > 0" style="display: none;">
                        <template x-for="(spec, cIdx) in customSpecs" :key="cIdx">
                            <div class="flex items-center gap-3">
                                <input type="text" :name="'custom_specs[' + cIdx + '][name]'" x-model="spec.name"
                                    placeholder="Spec Name (e.g. Lug Width, Dial Finish)"
                                    class="veltra-auth-input w-1/3 px-3.5 py-2 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#4e555d] outline-none">
                                <input type="text" :name="'custom_specs[' + cIdx + '][value]'" x-model="spec.value"
                                    placeholder="Spec Value (e.g. 20 mm, Sunburst Green)"
                                    class="veltra-auth-input flex-1 px-3.5 py-2 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#4e555d] outline-none">
                                <button type="button" @click="removeCustomSpec(cIdx)"
                                    class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                        Description & Story
                    </label>
                    <textarea id="description" name="description" rows="3"
                        class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#4e555d] outline-none leading-relaxed">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- Section 4: Photo Gallery Management -->
            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center text-xs font-bold font-['Montserrat',sans-serif]">4</span>
                        <span>Manage Photos & Gallery</span>
                    </h2>
                    <span class="text-xs text-[#8e969d]">Choose primary cover or remove images</span>
                </div>

                <!-- Existing Images Grid -->
                @if ($product->images->isNotEmpty())
                    <div class="space-y-3">
                        <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] font-semibold block">
                            Current Photos (Select radio button for Cover image):
                        </span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                            @foreach ($product->images as $img)
                                <div class="relative rounded-2xl border {{ $img->is_primary ? 'border-[#cb8665] bg-[#cb8665]/10' : 'border-white/15 bg-[#080b0e]' }} p-2.5 flex flex-col justify-between overflow-hidden">
                                    <div class="h-24 flex items-center justify-center overflow-hidden mb-2">
                                        <img src="{{ $img->url }}" alt="Watch image" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="pt-2 border-t border-white/10 flex items-center justify-between gap-1 text-[11px]">
                                        <!-- Primary radio -->
                                        <label class="flex items-center gap-1 cursor-pointer text-[#cb8665]">
                                            <input type="radio" name="primary_image_id" value="{{ $img->id }}" @checked($img->is_primary)
                                                class="text-[#cb8665] focus:ring-[#cb8665]">
                                            <span class="text-[10px] uppercase font-bold">Cover</span>
                                        </label>

                                        <!-- Delete checkbox -->
                                        <label class="flex items-center gap-1 cursor-pointer text-red-400 hover:text-red-300">
                                            <input type="checkbox" name="delete_image_ids[]" value="{{ $img->id }}"
                                                class="rounded text-red-500 bg-[#141b22] border-white/20 focus:ring-red-500">
                                            <span class="text-[10px] uppercase">Del</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upload Additional Photos -->
                <div class="space-y-4 pt-3 border-t border-white/10">
                    <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] font-semibold block">
                        Upload Additional Photos:
                    </span>
                    <div class="border-2 border-dashed border-white/15 hover:border-[#cb8665]/60 rounded-2xl p-6 text-center cursor-pointer transition-colors bg-[#080b0e]/50">
                        <input type="file" name="images[]" id="images" multiple accept="image/*" @change="handleNewFiles" class="hidden">
                        <label for="images" class="cursor-pointer block">
                            <div class="w-10 h-10 rounded-full bg-[#cb8665]/15 border border-[#cb8665]/35 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-[#cb8665]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-white block">Click to select additional photos</span>
                            <span class="text-[11px] text-[#8e969d] mt-0.5 block">PNG, JPG, or WEBP up to 5MB each</span>
                        </label>
                    </div>

                    <!-- New Files Live Preview -->
                    <div x-show="newPreviews.length > 0" class="space-y-2" style="display: none;">
                        <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] font-semibold block">
                            New Uploads Preview (<span x-text="newPreviews.length"></span>):
                        </span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                            <template x-for="(src, index) in newPreviews" :key="index">
                                <div class="relative rounded-xl border border-white/15 bg-[#080b0e] p-2 h-24 flex items-center justify-center overflow-hidden">
                                    <img :src="src" class="max-h-full max-w-full object-contain">
                                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[8.5px] font-bold uppercase bg-[#5ea391] text-[#0e1215]">
                                        New
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 5: Merchandising & Visibility Flags -->
            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-4">
                <h3 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal pb-2 border-b border-white/10">
                    Visibility & Merchandising
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <label class="veltra-dash-subcard flex items-start gap-3.5 p-4 rounded-xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/40 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))
                            class="w-5 h-5 rounded text-[#cb8665] bg-[#141b22] border-white/20 focus:ring-[#cb8665] mt-0.5">
                        <div>
                            <span class="text-sm font-semibold text-white block">Active in Online Store</span>
                            <span class="text-xs text-[#8e969d] mt-0.5 block">When unchecked, this timepiece is hidden from the shop catalog and search.</span>
                        </div>
                    </label>

                    <label class="veltra-dash-subcard flex items-start gap-3.5 p-4 rounded-xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/40 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))
                            class="w-5 h-5 rounded text-[#cb8665] bg-[#141b22] border-white/20 focus:ring-[#cb8665] mt-0.5">
                        <div>
                            <span class="text-sm font-semibold text-white block">Feature on Homepage / Top of Shop</span>
                            <span class="text-xs text-[#8e969d] mt-0.5 block">Prioritized in default sort and recommended on customer dashboards.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ route('admin.products.index') }}"
                    class="px-6 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#8e969d] hover:text-white transition-colors">
                    Cancel
                </a>

                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all shadow-[0_6px_25px_rgba(203,134,101,0.35)] cursor-pointer">
                    Save Changes →
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
