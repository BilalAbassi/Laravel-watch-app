<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-red-600/90 border border-red-500/40 font-[\'Montserrat\',sans-serif] font-semibold text-xs text-white uppercase tracking-wider hover:bg-red-500 active:bg-red-700 focus:outline-none transition-all cursor-pointer']) }}>
    {{ $slot }}
</button>
