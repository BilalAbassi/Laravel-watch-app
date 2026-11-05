<x-admin-layout title="Edit Specification — {{ $specification->name }}">
    <div class="max-w-3xl mx-auto space-y-8"
        x-data="{
            newOptions: [],
            addNewOption() {
                this.newOptions.push('');
            },
            removeNewOption(index) {
                this.newOptions.splice(index, 1);
            }
        }">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#8e969d] mb-2">
                    <a href="{{ route('admin.specifications.index') }}" class="hover:text-[#cb8665] transition-colors">Specifications</a>
                    <span>/</span>
                    <span class="text-[#cb8665] font-medium">Edit Attribute</span>
                </nav>
                <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl font-normal text-white tracking-tight">
                    Edit Specification: {{ $specification->name }}
                </h1>
                <p class="text-xs sm:text-sm text-[#8e969d] mt-1">
                    Currently configured across <span class="text-[#cb8665] font-semibold">{{ $specification->products_count }}</span> watches.
                </p>
            </div>

            <a href="{{ route('admin.specifications.index') }}"
                class="veltra-auth-secondary-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#141b22] border border-[#cb8665]/35 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#f5be9f] hover:border-[#cb8665] hover:text-white transition-all self-start sm:self-auto">
                <span>← Back</span>
            </a>
        </div>

        <!-- Form Card -->
        <form method="POST" action="{{ route('admin.specifications.update', $specification) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Attribute Details -->
            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-5">
                <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal pb-3 border-b border-white/10">
                    Attribute Identity
                </h2>

                <div>
                    <label for="name" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                        Attribute Name *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $specification->name) }}" required
                        class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] outline-none">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="slug" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            URL Slug *
                        </label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug', $specification->slug) }}" required
                            class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] outline-none font-mono">
                    </div>

                    <div>
                        <label for="sort_order" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                            Display Sort Order
                        </label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $specification->sort_order) }}" min="0"
                            class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white outline-none font-mono">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="veltra-dash-subcard flex items-start gap-3.5 p-4 rounded-xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/40 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $specification->is_active))
                            class="w-5 h-5 rounded text-[#cb8665] bg-[#141b22] border-white/20 focus:ring-[#cb8665] mt-0.5">
                        <div>
                            <span class="text-sm font-semibold text-white block">Active & Available in Watch Forms</span>
                            <span class="text-xs text-[#8e969d] mt-0.5 block">When active, this specification appears as a field when curating timepieces.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Section 2: Manage Existing Predefined Options -->
            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-white/10">
                    <div>
                        <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal">
                            Predefined Suggested Options ({{ $specification->values->count() }})
                        </h2>
                        <p class="text-xs text-[#8e969d] mt-0.5">
                            Modify existing options, check "Delete" to remove an option, or add new ones below.
                        </p>
                    </div>

                    <button type="button" @click="addNewOption"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#cb8665]/20 text-[#f5be9f] border border-[#cb8665]/40 text-xs font-['Montserrat',sans-serif] uppercase font-semibold hover:bg-[#cb8665]/30 cursor-pointer">
                        <span>+ Add New Option</span>
                    </button>
                </div>

                @if ($specification->values->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($specification->values as $v)
                            <div class="flex items-center gap-3 p-2 rounded-xl bg-[#080b0e] border border-white/10">
                                <span class="text-xs font-mono text-[#cb8665] w-6 text-center">#{{ $loop->iteration }}</span>
                                <input type="text" name="values[{{ $v->id }}]" value="{{ old('values.' . $v->id, $v->value) }}"
                                    class="veltra-auth-input flex-1 px-3 py-1.5 rounded-lg bg-[#141b22] border border-white/10 focus:border-[#cb8665] text-xs text-white outline-none">
                                <label class="flex items-center gap-1.5 text-xs text-red-400 hover:text-red-300 cursor-pointer px-2 py-1 rounded bg-red-500/10">
                                    <input type="checkbox" name="delete_value_ids[]" value="{{ $v->id }}"
                                        class="rounded text-red-500 bg-[#080b0e] border-white/20 focus:ring-red-500">
                                    <span class="text-[11px] font-semibold uppercase">Delete</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-[#8e969d] italic py-2">No predefined options have been configured for this attribute yet.</p>
                @endif

                <!-- New Options Dynamic Adder -->
                <div x-show="newOptions.length > 0" class="pt-4 border-t border-white/10 space-y-3" style="display: none;">
                    <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#5ea391] font-semibold block">
                        New Options to Add:
                    </span>
                    <template x-for="(opt, idx) in newOptions" :key="idx">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-mono text-[#5ea391] w-6 text-center">+</span>
                            <input type="text" name="new_values[]" x-model="newOptions[idx]"
                                placeholder="Enter new option value..."
                                class="veltra-auth-input flex-1 px-4 py-2 rounded-xl bg-[#080b0e] border border-[#5ea391]/40 focus:border-[#5ea391] text-xs text-white outline-none">
                            <button type="button" @click="removeNewOption(idx)"
                                class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors cursor-pointer"
                                title="Remove">
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
                    Save Changes →
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>

