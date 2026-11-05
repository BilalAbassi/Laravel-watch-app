<x-admin-layout title="Edit Category — {{ $category->name }}">
    <div class="max-w-3xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <nav class="flex items-center gap-2 text-xs text-[#8e969d] mb-2">
                    <a href="{{ route('admin.categories.index') }}" class="hover:text-[#cb8665] transition-colors">Categories</a>
                    <span>/</span>
                    <span class="text-[#cb8665] font-medium">Edit Category</span>
                </nav>
                <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl font-normal text-white tracking-tight">
                    Edit Category: {{ $category->name }}
                </h1>
                <p class="text-xs sm:text-sm text-[#8e969d] mt-1">
                    Currently linked to <span class="text-[#cb8665] font-semibold">{{ $category->products_count }}</span> watches.
                </p>
            </div>

            <div class="flex items-center gap-3 self-start sm:self-auto">
                <a href="{{ route('admin.products.index', ['category_id' => $category->id]) }}"
                    class="veltra-auth-secondary-btn inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#141b22] border border-white/15 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-white hover:border-[#cb8665] transition-all">
                    <span>View Watches ({{ $category->products_count }}) ↗</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="veltra-auth-secondary-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#141b22] border border-[#cb8665]/35 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#f5be9f] hover:border-[#cb8665] hover:text-white transition-all">
                    <span>← Back</span>
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="veltra-dash-card p-6 sm:p-8 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)] space-y-5">
                <div>
                    <label for="name" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                        Category Name *
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                        class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] outline-none">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                        URL Slug *
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required
                        class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] outline-none font-mono">
                    @error('slug')
                        <p class="mt-1.5 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <label class="veltra-dash-subcard flex items-start gap-3.5 p-4 rounded-xl bg-[#080b0e] border border-white/10 hover:border-[#cb8665]/40 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))
                            class="w-5 h-5 rounded text-[#cb8665] bg-[#141b22] border-white/20 focus:ring-[#cb8665] mt-0.5">
                        <div>
                            <span class="text-sm font-semibold text-white block">Active in Shop Filter Sidebar</span>
                            <span class="text-xs text-[#8e969d] mt-0.5 block">Visible in the public watch catalog filter list and watch curation forms.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 pt-2">
                <a href="{{ route('admin.categories.index') }}"
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

