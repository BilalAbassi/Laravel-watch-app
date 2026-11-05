<x-admin-layout title="Add Specification Attribute">
    <div class="max-w-3xl mx-auto space-y-8"
        x-data="{
            options: [''],
            addOption() {
                this.options.push('');
            },
            removeOption(index) {
                if (this.options.length > 1) {
                    this.options.splice(index, 1);
                } else {
                    this.options = [''];
                }
            }
        }">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#8e969d] mb-2">
                    <a href="{{ route('admin.specifications.index') }}" class="hover:text-[#cb8665] transition-colors">Specifications</a>
                    <span>/</span>
                    <span class="text-[#cb8665] font-medium">Add Attribute</span>
                </nav>
                <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl font-normal text-white tracking-tight">
                    Add Specification Attribute
                </h1>
                <p class="text-xs sm:text-sm text-[#8e969d] mt-1">
                    Define technical watch attributes like "Power Reserve", "Bezel Material", "Lug Width" and reusable options.
                </p>
            </div>

            <a href="{{ route('admin.specifications.index') }}"
                class="veltra-auth-secondary-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#141b22] border border-[#cb8665]/35 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#f5be9f] hover:border-[#cb8665] hover:text-white transition-all self-start sm:self-auto">
                <span>← Back</span>
            </a>
        </div>

        <!-- Form Card -->
        <form method="POST" action="{{ route('admin.specifications.store') }}" class="space-y-6">
            @csrf

            <!-- Section 1: Attribute Details -->
            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-5">
                <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal pb-3 border-b border-white/10">
                    Attribute Identity
                </h2>

                <div>
                    <label for="name" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                        Attribute Name *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        placeholder="e.g. Power Reserve, Bezel Material, Calibre, Dial Finish"
                        class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] outline-none">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="slug" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            URL Slug <span class="text-[#8e969d] lowercase">(optional)</span>
                        </label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                            placeholder="e.g. power-reserve"
                            class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] outline-none font-mono">
                    </div>

                    <div>
                        <label for="sort_order" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            Display Sort Order
                        </label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                            class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white outline-none font-mono">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="veltra-dash-subcard flex items-start gap-3.5 p-4 rounded-xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/40 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))
                            class="w-5 h-5 rounded text-[#cb8665] bg-[#141b22] border-white/20 focus:ring-[#cb8665] mt-0.5">
                        <div>
                            <span class="text-sm font-semibold text-white block">Active & Available in Watch Forms</span>
                            <span class="text-xs text-[#8e969d] mt-0.5 block">When active, this specification appears as a specification field when curating timepieces.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Section 2: Predefined Reusable Options -->
            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <div>
                        <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal">
                            Predefined Suggested Options
                        </h2>
                        <p class="text-xs text-[#8e969d] mt-0.5">
                            Options that appear as quick one-click dropdown suggestions in the watch form.
                        </p>
                    </div>

                    <button type="button" @click="addOption"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/40 text-xs font-['Montserrat',sans-serif] uppercase font-semibold hover:bg-[#cb8665]/30 cursor-pointer">
                        <span>+ Add Option</span>
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(opt, idx) in options" :key="idx">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-mono text-[#8e969d] w-6 text-right" x-text="(idx + 1) + '.'"></span>
                            <input type="text" name="values[]" x-model="options[idx]"
                                placeholder="e.g. 70 Hours / Powermatic 80 / 316L Stainless Steel"
                                class="veltra-auth-input flex-1 px-4 py-2.5 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#4e555d] outline-none">
                            <button type="button" @click="removeOption(idx)"
                                class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors cursor-pointer"
                                title="Remove Option">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 pt-2">
                <a href="{{ route('admin.specifications.index') }}"
                    class="px-6 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#8e969d] hover:text-white transition-colors">
                    Cancel
                </a>

                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all shadow-[0_6px_25px_rgba(203,134,101,0.35)] cursor-pointer">
                    Save Specification Attribute →
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

