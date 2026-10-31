<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password — TIMEYRAH Watches</title>

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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-[#0b0f12] text-[#d1d6da] min-h-screen selection:bg-[#cb8665]/30 selection:text-white flex flex-col font-['Inter',sans-serif]">

    <!-- Top Navigation -->
    <x-navbar />

    <!-- Main Auth Section -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12 sm:py-20 relative overflow-hidden">

        <!-- Subtle Ambient Glows -->
        <div
            class="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-[#cb8665]/10 rounded-full blur-[140px] pointer-events-none">
        </div>

        <!-- Auth Card Container -->
        <div class="veltra-auth-card relative w-full max-w-[480px] z-10">

            <!-- Card Shell -->
            <div
                class="veltra-auth-panel rounded-3xl p-7 sm:p-10 bg-[#0e1419]/90 border border-[#cb8665]/35 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.85),0_0_40px_rgba(203,134,101,0.12)] backdrop-blur-xl transition-all">

                <!-- Atelier Monogram Crest -->
                <div class="text-center mb-7">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-[#2a170e] to-[#120a06] border border-[#cb8665]/50 shadow-[0_4px_20px_rgba(203,134,101,0.25)] mb-3">
                        <span
                            class="font-['Cormorant_Garamond',serif] italic font-bold text-2xl text-[#f5be9f] tracking-wider">TY</span>
                    </div>
                    <div class="flex items-center justify-center gap-2 mb-1.5">
                        <span class="w-4 h-[1px] bg-[#cb8665]/50"></span>
                        <span
                            class="text-[10px] tracking-[0.25em] uppercase text-[#cb8665] font-semibold font-['Montserrat',sans-serif]">
                            PASSWORD RECOVERY
                        </span>
                        <span class="w-4 h-[1px] bg-[#cb8665]/50"></span>
                    </div>
                    <h1
                        class="font-['Cormorant_Garamond',serif] text-3xl sm:text-[34px] font-normal text-white tracking-wide">
                        Forgot Your Password?
                    </h1>
                    <p class="text-xs sm:text-[13px] text-[#8e969d] mt-1 font-light leading-relaxed">
                        Enter your registered email address and we will send you a link to reset your password.
                    </p>
                </div>

                <!-- Status / Success Message -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-[#5ea391]/15 border border-[#5ea391]/40 text-[#a3decb] text-xs">
                        <div class="font-medium flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-[#5ea391] shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>Reset Link Sent</span>
                        </div>
                        <p class="text-[11.5px] leading-relaxed text-[#c3ede0]">
                            {{ session('status') }}
                        </p>

                        <!-- Local Testing Quick Link Flash -->
                        @if (session('dev_reset_link'))
                            <div class="mt-3 pt-3 border-t border-[#5ea391]/30">
                                <span
                                    class="text-[10.5px] font-semibold uppercase tracking-wider text-[#f5be9f] block mb-1">
                                    Test Link:
                                </span>
                                <a href="{{ session('dev_reset_link') }}"
                                    class="inline-flex items-center gap-1.5 text-xs text-white underline hover:text-[#f5be9f] transition-colors break-all">
                                    <span>Click here to open the Password Reset Form &rarr;</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Validation Errors Box -->
                @if ($errors->any())
                    <div class="mb-6 p-3.5 rounded-xl bg-red-950/40 border border-red-500/40 text-red-200 text-xs">
                        <div class="font-medium text-red-300 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                            <span>Please Check Your Email</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-[11.5px] text-red-200/90 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Request Form -->
                <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email"
                            class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#b8a295] mb-2 font-medium">
                            Your Email Address
                        </label>
                        <div class="relative">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                autofocus placeholder="name@example.com"
                                class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#090d10] border {{ $errors->has('email') ? 'border-red-500/60' : 'border-white/10' }} text-white text-sm placeholder-[#6b737b] focus:outline-none focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] transition-all pl-10">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#73828c]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3.5 px-6 rounded-xl font-['Outfit',sans-serif] text-sm font-semibold tracking-wider uppercase text-[#0d0906] bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] hover:brightness-110 active:scale-[0.99] shadow-[0_6px_20px_rgba(203,134,101,0.35)] transition-all flex items-center justify-center gap-2 cursor-pointer mt-2">
                        <span>Send Password Reset Link</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-7">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-[10px] uppercase tracking-widest text-[#7a858f]">
                        <span class="px-3 bg-[#0e1419] rounded-full">Remember Password?</span>
                    </div>
                </div>

                <!-- Back to Sign In Link -->
                <div class="text-center">
                    <a href="{{ route('login') }}"
                        class="veltra-auth-secondary-btn w-full inline-flex items-center justify-center py-2.5 px-4 rounded-xl text-xs font-semibold tracking-wider uppercase text-[#f5be9f] bg-transparent border border-[#cb8665]/50 hover:bg-[#cb8665]/10 hover:border-[#cb8665] transition-all">
                        &larr; Back to Login
                    </a>
                </div>

            </div>

        </div>

    </main>

    <!-- Footer -->
    <x-footer />

</body>

</html>
