<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acquisition Confirmed #{{ $order['order_number'] }} — TIMEYRAH Horology</title>
    <meta name="description" content="Official certificate of horological acquisition and receipt from TIMEYRAH Atelier.">

    <!-- Fonts: Cormorant Garamond, Montserrat, Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .no-print {
                display: none !important;
            }
            .print-border {
                border: 1px solid #ccc !important;
            }
        }
    </style>
</head>

<body class="bg-[#0b0f12] text-[#d1d6da] min-h-screen selection:bg-[#cb8665]/30 selection:text-white">

    <!-- Top Secure Navigation Header -->
    <header class="border-b border-white/10 bg-[#0e1317]/90 backdrop-blur-xl no-print">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-12 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full border border-[#cb8665]/40 bg-[#141b22] flex items-center justify-center p-1">
                    <svg class="w-5 h-5 text-[#cb8665]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div>
                    <span class="font-['Montserrat',sans-serif] text-sm tracking-[0.3em] font-semibold text-white uppercase block">
                        TIMEYRAH
                    </span>
                    <span class="text-[9.5px] font-['Montserrat',sans-serif] tracking-[0.2em] text-[#8e969d] uppercase block">
                        Certificate of Authenticity
                    </span>
                </div>
            </a>

            <div class="flex items-center gap-4 text-xs font-['Inter',sans-serif]">
                <a href="{{ route('shop') }}" class="text-[#cb8665] hover:text-[#e4ab91] transition-colors text-xs font-semibold flex items-center gap-1">
                    <span>Explore More Watches</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Certificate & Confirmation Sheet -->
    <main class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-12 py-10 lg:py-16">

        <!-- Top Celebration Crest -->
        <div class="text-center max-w-2xl mx-auto mb-10 no-print">
            <div class="w-16 h-16 rounded-full bg-[#cb8665]/15 border border-[#cb8665]/50 mx-auto mb-4 flex items-center justify-center shadow-[0_0_30px_rgba(203,152,126,0.3)]">
                <svg class="w-8 h-8 text-[#e4ab91]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.3em] text-[#dd9c7c] font-bold block mb-1">
                Acquisition Confirmed
            </span>
            <h1 class="font-['Cormorant_Garamond',serif] text-3xl sm:text-4xl lg:text-5xl font-normal text-white tracking-tight">
                Welcome to the Atelier Collection
            </h1>
            <p class="text-xs sm:text-sm text-[#8e969d] font-['Inter',sans-serif] mt-3 leading-relaxed">
                Thank you, <strong class="text-white">{{ $order['customer']['first_name'] }} {{ $order['customer']['last_name'] }}</strong>. Your timepiece has been officially reserved and transferred to our master watchmakers for pre-dispatch chronometric calibration.
            </p>
        </div>

        <!-- The Certificate of Authenticity Document -->
        <div class="relative rounded-3xl bg-[#0e1419] border border-white/15 p-6 sm:p-10 lg:p-14 shadow-[0_30px_80px_rgba(0,0,0,0.9)] overflow-hidden">
            
            <!-- Metallic Decorative Frame Borders -->
            <div class="absolute inset-2 border border-[#cb8665]/20 rounded-2xl pointer-events-none"></div>
            <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-[#cb8665]/5 blur-3xl pointer-events-none"></div>

            <!-- Certificate Header Bar -->
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between pb-8 border-b border-white/10 gap-4">
                <div>
                    <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-[0.25em] text-[#dd9c7c] font-bold block">
                        Official Atelier Acquisition Document
                    </span>
                    <h2 class="font-['Cormorant_Garamond',serif] text-2xl sm:text-3xl font-medium text-white tracking-tight mt-1">
                        TIMEYRAH Certificate of Origin & Ownership
                    </h2>
                </div>

                <div class="text-left sm:text-right">
                    <span class="text-[10px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] block">
                        Atelier Order Reference
                    </span>
                    <span class="text-sm sm:text-base font-mono font-bold text-[#e4ab91] tracking-wider block">
                        {{ $order['order_number'] }}
                    </span>
                    <span class="text-[11px] text-[#6b737b] block mt-0.5">
                        Issued: {{ $order['placed_at'] }}
                    </span>
                </div>
            </div>

            <!-- Vault Dispatch Tracking Timeline -->
            <div class="relative z-10 py-8 border-b border-white/10">
                <h3 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-widest text-[#8e969d] font-semibold mb-6">
                    Transit & Dispatch Progress
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 sm:gap-2 relative">
                    <!-- Step 1 -->
                    <div class="flex sm:flex-col items-center sm:items-start gap-3 sm:gap-2">
                        <div class="w-7 h-7 rounded-full bg-[#cb8665] text-[#0b0f12] flex items-center justify-center text-xs font-bold shrink-0">
                            ✓
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-white block">Escrow Confirmed</span>
                            <span class="text-[10.5px] text-[#8e969d]">Payment authorized</span>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex sm:flex-col items-center sm:items-start gap-3 sm:gap-2">
                        <div class="w-7 h-7 rounded-full bg-[#cb8665]/20 border border-[#cb8665] text-[#e4ab91] flex items-center justify-center text-xs font-bold shrink-0 animate-pulse">
                            2
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-[#e4ab91] block">Atelier Calibration</span>
                            <span class="text-[10.5px] text-[#8e969d]">Regulation & inspection</span>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex sm:flex-col items-center sm:items-start gap-3 sm:gap-2 opacity-60">
                        <div class="w-7 h-7 rounded-full bg-white/5 border border-white/15 text-[#8e969d] flex items-center justify-center text-xs font-bold shrink-0">
                            3
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-white block">Armored Vault Case</span>
                            <span class="text-[10.5px] text-[#8e969d]">Tamper-evident sealing</span>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex sm:flex-col items-center sm:items-start gap-3 sm:gap-2 opacity-60">
                        <div class="w-7 h-7 rounded-full bg-white/5 border border-white/15 text-[#8e969d] flex items-center justify-center text-xs font-bold shrink-0">
                            4
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-white block">Vault Delivery</span>
                            <span class="text-[10.5px] text-[#8e969d]">{{ $order['estimated_delivery'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Details & Specifications Recap -->
            <div class="relative z-10 py-8 border-b border-white/10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Watch Image & Main Details -->
                <div class="lg:col-span-7 flex flex-col sm:flex-row gap-6 items-center sm:items-start">
                    <div class="w-32 h-32 rounded-2xl bg-[#080b0e] border border-white/10 p-3 shrink-0 flex items-center justify-center">
                        <img src="{{ $order['watch']['image'] }}" alt="{{ $order['watch']['title'] }}" class="w-full h-full object-contain">
                    </div>

                    <div class="space-y-2 text-center sm:text-left">
                        <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-[0.2em] text-[#dd9c7c] font-bold">
                            {{ $order['watch']['brand'] }} Atelier
                        </span>
                        <h3 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal">
                            {{ $order['watch']['title'] }}
                        </h3>
                        <p class="text-xs text-[#8e969d] font-['Inter',sans-serif]">
                            Selected Dial: <strong class="text-white">{{ $order['variant'] }}</strong> • Quantity: <strong class="text-white">{{ $order['quantity'] }}</strong>
                        </p>

                        @if (!empty($order['engraving']))
                            <div class="p-2 rounded-lg bg-[#080b0e] border border-[#cb8665]/30 text-xs text-[#e4ab91] inline-flex items-center gap-2 mt-2">
                                <span class="font-bold text-[10px] uppercase tracking-wider">Custom Engraving:</span>
                                <span class="font-mono">"{{ $order['engraving'] }}"</span>
                            </div>
                        @endif

                        @if (!empty($order['wrist_size']) && $order['wrist_size'] !== 'factory')
                            <p class="text-[11px] text-[#8e969d]">
                                Watchmaker Bracelet Sizing: <span class="text-white font-medium">{{ $order['wrist_size'] }} in wrist specification</span>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Financial Calculation & Payment Breakdown -->
                <div class="lg:col-span-5 p-5 rounded-2xl bg-[#080b0e]/80 border border-white/10 space-y-2.5 text-xs font-['Inter',sans-serif]">
                    <div class="flex justify-between text-[#8e969d]">
                        <span>Timepiece Price ({{ $order['quantity'] }}x)</span>
                        <span class="text-white font-medium">${{ number_format($order['subtotal'], 2) }}</span>
                    </div>

                    <div class="flex justify-between text-[#8e969d]">
                        <span>Vault Transit ({{ ucfirst($order['shipping_method']) }})</span>
                        <span class="{{ $order['shipping_cost'] > 0 ? 'text-white' : 'text-[#5ea391]' }} font-medium">
                            {{ $order['shipping_cost'] > 0 ? '$' . number_format($order['shipping_cost'], 2) : 'Complimentary' }}
                        </span>
                    </div>

                    @if ($order['discount'] > 0)
                        <div class="flex justify-between text-[#dd9c7c] font-medium">
                            <span>Collector Privilege ({{ $order['promo_code'] }})</span>
                            <span>−${{ number_format($order['discount'], 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-[#8e969d]">
                        <span>Payment Method</span>
                        <span class="text-white uppercase font-semibold">{{ $order['payment_method'] }} (•••• {{ $order['card_last_four'] }})</span>
                    </div>

                    <div class="pt-3 border-t border-white/10 flex justify-between items-baseline">
                        <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-white font-bold">
                            Total Settled
                        </span>
                        <span class="font-['Montserrat',sans-serif] text-2xl font-bold text-[#e4ab91]">
                            ${{ number_format($order['total'], 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer Destination & Atelier Attestation -->
            <div class="relative z-10 pt-8 grid grid-cols-1 md:grid-cols-2 gap-8 text-xs font-['Inter',sans-serif]">
                <div class="space-y-1.5">
                    <h4 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-widest text-[#dd9c7c] font-bold">
                        Delivery Destination & Client Dossier
                    </h4>
                    <p class="text-white font-semibold">{{ $order['customer']['first_name'] }} {{ $order['customer']['last_name'] }}</p>
                    <p class="text-[#8e969d]">{{ $order['customer']['address'] }} @if(!empty($order['customer']['apartment'])), {{ $order['customer']['apartment'] }} @endif</p>
                    <p class="text-[#8e969d]">{{ $order['customer']['city'] }}, {{ $order['customer']['postal_code'] }}, {{ $order['customer']['country'] }}</p>
                    <p class="text-[#8e969d]">Contact: {{ $order['customer']['phone'] }} • {{ $order['customer']['email'] }}</p>
                </div>

                <div class="space-y-1.5 p-4 rounded-xl bg-[#080b0e] border border-white/5">
                    <h4 class="text-xs font-['Montserrat',sans-serif] uppercase tracking-widest text-[#5ea391] font-bold flex items-center gap-1.5">
                        <span>✓</span>
                        <span>Certified Atelier Seal & Warranty</span>
                    </h4>
                    <p class="text-[11.5px] text-[#8e969d] leading-relaxed">
                        This timepiece is backed by TIMEYRAH's 2-Year International Horological Warranty. All mechanical chronometry, water seals, and factory documentation are certified authentic.
                    </p>
                    <div class="pt-2 text-[10px] font-mono text-[#6b737b] tracking-wider uppercase">
                        ATELIER SECURITY HASH: {{ strtoupper(substr(hash('sha256', $order['order_number']), 0, 24)) }}
                    </div>
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-wrap items-center justify-center gap-4 no-print">
            <button onclick="window.print()" type="button"
                class="px-6 py-3 rounded-xl bg-[#141b22] border border-white/20 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-white hover:border-[#cb8665] hover:text-[#dd9c7c] transition-all flex items-center gap-2 cursor-pointer shadow-lg">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect width="12" height="8" x="6" y="14"></rect>
                </svg>
                <span>Print Certificate of Origin</span>
            </button>

            <a href="{{ route('shop') }}"
                class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-bold hover:brightness-110 transition-all flex items-center gap-2 shadow-[0_4px_25px_rgba(203,152,126,0.35)]">
                <span>Continue Exploring Atelier Collection</span>
                <span>→</span>
            </a>
        </div>

    </main>

    <!-- Global Website Footer -->
    <div class="no-print">
        <x-footer />
    </div>

    <script>
        try {
            localStorage.removeItem('timeyrah_cart');
        } catch (e) {}
    </script>
</body>

</html>
