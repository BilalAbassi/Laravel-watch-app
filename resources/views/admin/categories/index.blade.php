<x-admin-layout title="Categories Management">
    <div class="space-y-8">
        <!-- Header Banner & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-[0.25em] text-[#cb8665] font-semibold block mb-1">
                    Store Taxonomy
                </span>
                <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl font-normal text-white tracking-tight">
                    Collection & Filter Categories
                </h1>
                <p class="text-xs sm:text-sm text-[#8e969d] mt-1 font-light">
                    Manage watch categories shown in filter sidebars, search tags, and product assignment.
                </p>
            </div>

            <a href="{{ route('admin.categories.create') }}"
                class="veltra-auth-primary-btn inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all shadow-[0_6px_25px_rgba(203,134,101,0.35)] shrink-0">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>+ Add New Category</span>
            </a>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="veltra-dash-card p-4 sm:p-5 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_35px_rgba(0,0,0,0.4)]">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#8e969d]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" name="q" value="{{ $search }}"
                        placeholder="Search category name or slug..."
                        class="veltra-auth-input w-full pl-10 pr-4 py-2.5 rounded-xl bg-[#080b0e] border border-white/10 text-xs text-white placeholder-[#5a626a] focus:border-[#cb8665] outline-none">
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#141b22] border border-[#cb8665]/40 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#f5be9f] hover:bg-[#cb8665]/20 hover:text-white transition-all cursor-pointer">
                        Filter
                    </button>
                    @if ($search)
                        <a href="{{ route('admin.categories.index') }}"
                            class="px-4 py-2.5 rounded-xl bg-white/5 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#8e969d] hover:text-white transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Categories Table -->
        <div class="veltra-dash-card rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.6)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-white/10 bg-[#080b0e]/80 text-[#8e969d] font-['Montserrat',sans-serif] uppercase tracking-wider text-[10px]">
                            <th class="py-4 px-6 font-semibold">Category Name</th>
                            <th class="py-4 px-6 font-semibold">URL Slug</th>
                            <th class="py-4 px-6 font-semibold text-center">Linked Watches</th>
                            <th class="py-4 px-6 font-semibold text-center">Status</th>
                            <th class="py-4 px-6 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($categories as $cat)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2 h-2 rounded-full {{ $cat->is_active ? 'bg-[#5ea391]' : 'bg-[#8e969d]' }}"></span>
                                        <span class="font-semibold text-sm text-white group-hover:text-[#cb8665] transition-colors">
                                            {{ $cat->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-mono text-[11px] text-[#f5be9f]/80">
                                    {{ $cat->slug }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="{{ route('admin.products.index', ['category_id' => $cat->id]) }}"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#141b22] border border-white/10 text-white font-mono hover:border-[#cb8665] transition-colors"
                                        title="View watches in this category">
                                        <span>{{ $cat->products_count }}</span>
                                        <span class="text-[10px] text-[#8e969d]">watches</span>
                                    </a>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if ($cat->is_active)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[#5ea391]/15 text-[#5ea391] border border-[#5ea391]/30">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-white/5 text-[#8e969d] border border-white/10">
                                            Hidden
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $cat) }}"
                                            class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-[#cb8665]/20 hover:text-white text-[#d1d6da] transition-colors"
                                            title="Edit Category">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                                            onsubmit="return confirm('Are you sure you want to delete category \'{{ $cat->name }}\'? Watches will remain intact.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-300 transition-colors cursor-pointer"
                                                title="Delete Category">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-[#8e969d]">
                                    <p class="text-sm">No categories found matching your query.</p>
                                    <a href="{{ route('admin.categories.create') }}" class="inline-block mt-3 text-xs text-[#cb8665] hover:underline uppercase tracking-wider font-semibold">
                                        + Create first category
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if ($categories->hasPages())
                <div class="p-4 sm:p-6 border-t border-white/10 bg-[#080b0e]/40">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

