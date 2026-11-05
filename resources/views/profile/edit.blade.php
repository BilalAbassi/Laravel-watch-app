<x-app-layout title="Account Profile">
    <x-slot name="header">
        <nav class="flex items-center gap-2 text-xs text-[#8e969d] mb-3">
            <a href="{{ route('home') }}" class="hover:text-[#cb8665] transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('dashboard') }}" class="hover:text-[#cb8665] transition-colors">My Dashboard</a>
            <span>/</span>
            <span class="text-[#cb8665] font-medium">Account Settings</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-5 h-[1px] bg-[#cb8665]/60"></span>
                    <span class="text-[10.5px] font-['Montserrat',sans-serif] uppercase tracking-[0.26em] text-[#cb8665] font-semibold">
                        Personal & Security Settings
                    </span>
                </div>
                <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl font-normal text-white tracking-tight">
                    Account Profile
                </h1>
                <p class="text-xs sm:text-sm text-[#8e969d] mt-1">
                    Update your contact details, change your password, or manage your account.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
                class="veltra-auth-secondary-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#141b22] border border-[#cb8665]/35 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-[#f5be9f] hover:border-[#cb8665] hover:text-white transition-all self-start sm:self-auto">
                <span>← Back to Dashboard</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-[1100px] mx-auto px-4 sm:px-6 lg:px-12 py-10 lg:py-14 space-y-8">
        <div class="veltra-dash-card p-6 sm:p-9 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)]">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="veltra-dash-card p-6 sm:p-9 rounded-3xl bg-[#0e1419]/90 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.65)]">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="veltra-dash-card p-6 sm:p-9 rounded-3xl bg-[#0e1419]/90 border border-red-500/20 shadow-[0_20px_50px_rgba(0,0,0,0.65)]">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
