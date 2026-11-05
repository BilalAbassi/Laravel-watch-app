@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' — TIMEYRAH Watches' : 'My Account — TIMEYRAH Watches' }}</title>

    <!-- Immediate Theme Initialization Script (Prevents Theme Flash) -->
    <script>
        (function() {
            try {
                var theme = localStorage.getItem('timeyrah_theme') || 'dark';
                if (theme === 'light') {
                    document.documentElement.classList.add('theme-light');
                    document.documentElement.setAttribute('data-theme', 'light');
                } else {
                    document.documentElement.classList.remove('theme-light');
                    document.documentElement.setAttribute('data-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>

    <!-- Fonts: Cormorant Garamond, Montserrat, Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-[#0b0f12] text-[#d1d6da] min-h-screen selection:bg-[#cb8665]/30 selection:text-white flex flex-col font-['Inter',sans-serif] antialiased">

    <!-- Top Store Navigation with Theme Toggle & Cart Drawer -->
    <x-navbar />

    <!-- Optional Page Banner Slot -->
    @isset($header)
        <section class="veltra-shop-header relative border-b border-white/5 bg-gradient-to-b from-[#141a21]/60 to-transparent py-8 sm:py-10 px-4 sm:px-6 lg:px-12">
            <div class="max-w-[1500px] mx-auto">
                {{ $header }}
            </div>
        </section>
    @endisset

    <!-- Main Content Shell -->
    <main class="flex-1 relative overflow-hidden">
        <!-- Subtle Ambient Copper Glows -->
        <div class="veltra-ambient-glow absolute -top-28 left-1/3 w-[520px] h-[280px] bg-[#cb8665]/8 rounded-full blur-[130px] pointer-events-none"></div>
        <div class="veltra-ambient-glow absolute bottom-10 right-1/4 w-[420px] h-[240px] bg-[#1a0f09]/35 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="relative z-10">
            {{ $slot }}
        </div>
    </main>

    <!-- Store Footer -->
    <x-footer />
</body>

</html>
