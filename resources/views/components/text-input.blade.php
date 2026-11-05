@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'veltra-auth-input px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none']) }}>
