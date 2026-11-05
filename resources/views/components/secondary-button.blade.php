<button {{ $attributes->merge(['type' => 'button', 'class' => 'veltra-auth-secondary-btn inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-[#141b22] border border-[#cb8665]/35 font-[\'Montserrat\',sans-serif] font-semibold text-xs text-[#f5be9f] uppercase tracking-wider hover:border-[#cb8665] hover:text-white focus:outline-none disabled:opacity-25 transition-all cursor-pointer']) }}>
    {{ $slot }}
</button>
