@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[11px] font-[\'Montserrat\',sans-serif] uppercase tracking-[0.16em] text-[#8e969d] font-semibold']) }}>
    {{ $value ?? $slot }}
</label>
