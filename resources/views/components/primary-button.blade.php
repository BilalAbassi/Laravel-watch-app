<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] font-[\'Montserrat\',sans-serif] font-bold text-xs uppercase tracking-wider hover:brightness-110 focus:outline-none transition-all shadow-[0_6px_20px_rgba(203,134,101,0.3)] cursor-pointer']) }}>
    {{ $slot }}
</button>
