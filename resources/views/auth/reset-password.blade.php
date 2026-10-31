<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Set New Password — TIMEYRAH Watches</title>

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
        <div class="veltra-auth-card relative w-full max-w-[480px] z-10" x-data="{ showPassword: false, showConfirmPassword: false }">

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
                            PASSWORD RESET
                        </span>
                        <span class="w-4 h-[1px] bg-[#cb8665]/50"></span>
                    </div>
                    <h1
                        class="font-['Cormorant_Garamond',serif] text-3xl sm:text-[34px] font-normal text-white tracking-wide">
                        Create New Password
                    </h1>
                    <p class="text-xs sm:text-[13px] text-[#8e969d] mt-1 font-light leading-relaxed">
                        Enter your email address and choose a new password for your account.
                    </p>
                </div>

                <!-- Errors Box -->
                @if ($errors->any())
                    <div class="mb-6 p-3.5 rounded-xl bg-red-950/40 border border-red-500/40 text-red-200 text-xs">
                        <div class="font-medium text-red-300 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                            <span>Security Alert</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-[11.5px] text-red-200/90 pl-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Password Reset Form -->
                <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Hidden Token -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email Field -->
                    <div>
                        <label for="email"
                            class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#b8a295] mb-2 font-medium">
                            Email Address
                        </label>
                        <div class="relative">
                            <input type="email" id="email" name="email" value="{{ old('email', $email) }}"
                                required placeholder="name@example.com"
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

                    <!-- New Password Field -->
                    <div>
                        <label for="password"
                            class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#b8a295] mb-2 font-medium">
                            New Password
                        </label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                autofocus placeholder="Min. 8 characters"
                                class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#090d10] border {{ $errors->has('password') ? 'border-red-500/60' : 'border-white/10' }} text-white text-sm placeholder-[#6b737b] focus:outline-none focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] transition-all pl-10 pr-10">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#73828c]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                    </rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#73828c] hover:text-[#f5be9f] transition-colors cursor-pointer">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor"
                                    stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg x-show="showPassword" style="display: none;" class="w-4 h-4" fill="none"
                                    stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation"
                            class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#b8a295] mb-2 font-medium">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation"
                                name="password_confirmation" required placeholder="Repeat new Password"
                                class="veltra-auth-input w-full px-4 py-3 rounded-xl bg-[#090d10] border border-white/10 text-white text-sm placeholder-[#6b737b] focus:outline-none focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] transition-all pl-10 pr-10">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#73828c]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8"
                                    viewBox="0 0 24 24">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            </div>
                            <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#73828c] hover:text-[#f5be9f] transition-colors cursor-pointer">
                                <svg x-show="!showConfirmPassword" class="w-4 h-4" fill="none"
                                    stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg x-show="showConfirmPassword" style="display: none;" class="w-4 h-4"
                                    fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24">
                                    </path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3.5 px-6 rounded-xl font-['Outfit',sans-serif] text-sm font-semibold tracking-wider uppercase text-[#0d0906] bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] hover:brightness-110 active:scale-[0.99] shadow-[0_6px_20px_rgba(203,134,101,0.35)] transition-all flex items-center justify-center gap-2 cursor-pointer mt-2">
                        <span>Save New Password</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-7">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-[10px] uppercase tracking-widest text-[#7a858f]">
                        <span class="px-3 bg-[#0e1419] rounded-full">Return</span>
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
