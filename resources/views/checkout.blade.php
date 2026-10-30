<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Encrypted Atelier Checkout — TIMEYRAH Horology</title>
    <meta name="description" content="Secure luxury checkout and armored dispatch for certified TIMEYRAH horology timepieces.">

    <!-- Fonts: Cormorant Garamond, Montserrat, Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#0b0f12] text-[#d1d6da] min-h-screen selection:bg-[#cb8665]/30 selection:text-white"
    x-data="{
        cartItems: @json($cartItems),
        shippingMethod: 'armored',
        shippingCost: 0,
        paymentMethod: 'card',
        promoInput: '',
        appliedPromo: '',
        discount: 0,
        promoMessage: '',
        promoError: '',
        hasEngraving: false,
        engravingText: '',
        cardNumber: '',
        cardName: '',
        cardExp: '',
        cardCvv: '',
        isSubmitting: false,

        get subtotal() {
            return this.cartItems.reduce((acc, item) => acc + ((parseFloat(item.unit_price) || 0) * (parseInt(item.quantity) || 1)), 0);
        },

        get total() {
            let totalVal = this.subtotal - this.discount + (this.shippingMethod === 'concierge' ? 150 : 0);
            return Math.max(0, totalVal);
        },

        updateQuantity(index, newQty) {
            if (newQty < 1) {
                if (this.cartItems.length > 1) {
                    this.cartItems.splice(index, 1);
                }
                return;
            }
            this.cartItems[index].quantity = newQty;
            this.cartItems[index].line_total = (parseFloat(this.cartItems[index].unit_price) || 0) * newQty;
        },

        updateShipping(method) {
            this.shippingMethod = method;
            this.shippingCost = method === 'concierge' ? 150 : 0;
        },

        applyPromo() {
            this.promoError = '';
            this.promoMessage = '';
            let code = this.promoInput.trim().toUpperCase();

            if (code === 'TIMEYRAH10') {
                this.appliedPromo = code;
                this.discount = Math.round(this.subtotal * 0.10 * 100) / 100;
                this.promoMessage = '10% Collector Privilege Applied';
            } else if (code === 'VIP2026') {
                this.appliedPromo = code;
                this.discount = Math.min(this.subtotal, 200.00);
                this.promoMessage = '$200 Private Salon Voucher Applied';
            } else if (code === '') {
                this.promoError = 'Please enter an invitation voucher code.';
            } else {
                this.promoError = 'Voucher code invalid or expired for this catalog.';
            }
        },

        removePromo() {
            this.appliedPromo = '';
            this.discount = 0;
            this.promoInput = '';
            this.promoMessage = '';
            this.promoError = '';
        }
    }">

    <!-- Top Secure Atelier Checkout Header -->
    <header class="border-b border-white/10 bg-[#0e1317]/90 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-12 py-4 flex items-center justify-between">
            <!-- Brand Link & Emblem -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-full border border-[#cb8665]/40 bg-[#141b22] flex items-center justify-center p-1 group-hover:border-[#cb8665] transition-colors">
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
                        Haute Horlogerie Atelier
                    </span>
                </div>
            </a>

            <!-- Encrypted Trust Indicator -->
            <div class="flex items-center gap-4 text-xs font-['Inter',sans-serif]">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#141b22] border border-white/10 text-[#8e969d]">
                    <svg class="w-3.5 h-3.5 text-[#5ea391]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <span class="text-[11px] text-[#e8e4df]">256-Bit Armored Encryption</span>
                </div>
                <a href="{{ route('shop') }}" class="text-[#cb8665] hover:text-[#e4ab91] transition-colors text-xs font-semibold flex items-center gap-1">
                    <span>Return to Shop</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Checkout Steps Breadcrumb Ribbon -->
    <section class="border-b border-white/5 bg-[#090d10]/60 py-2.5 px-4 sm:px-6 lg:px-12 text-center text-xs font-['Inter',sans-serif]">
        <div class="max-w-[1500px] mx-auto flex items-center justify-center gap-4 sm:gap-8 text-[11px] uppercase tracking-wider text-[#6b737b]">
            <span class="text-[#e4ab91] font-semibold flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-[#cb8665]/20 text-[#e4ab91] flex items-center justify-center text-[10px] border border-[#cb8665]/40">1</span>
                <span>Client & Vault Address</span>
            </span>
            <span>—</span>
            <span class="text-[#e4ab91] font-semibold flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-[#cb8665]/20 text-[#e4ab91] flex items-center justify-center text-[10px] border border-[#cb8665]/40">2</span>
                <span>Transit & Escrow</span>
            </span>
            <span>—</span>
            <span class="text-[#6b737b] flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-white/5 text-[#6b737b] flex items-center justify-center text-[10px] border border-white/10">3</span>
                <span>Certificate & Receipt</span>
            </span>
        </div>
    </section>

    <!-- Main Checkout Container -->
    <main class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-12 py-10 lg:py-14">
        
        @if ($errors->any())
            <div class="mb-8 p-4 rounded-xl bg-red-950/40 border border-red-500/40 text-red-200 text-xs space-y-1">
                <p class="font-bold uppercase tracking-wider text-red-400">Please review the following details:</p>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" @submit="isSubmitting = true">
            @csrf

            <!-- Hidden Product & Pricing Inputs -->
            <input type="hidden" name="product_id" value="{{ $selectedWatch['id'] ?? '' }}">
            <input type="hidden" name="quantity" :value="cartItems[0] ? cartItems[0].quantity : 1">
            <input type="hidden" name="variant" value="{{ $variant }}">
            <input type="hidden" name="shipping_method" :value="shippingMethod">
            <input type="hidden" name="payment_method" :value="paymentMethod">
            <input type="hidden" name="promo_code" :value="appliedPromo">
            <input type="hidden" name="cart_items_json" :value="JSON.stringify(cartItems)">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-14 items-start">

                <!-- Left Column: Checkout Form (7 cols on LG, 7 on XL) -->
                <div class="lg:col-span-7 xl:col-span-7 space-y-8">

                    <!-- Section 1: Client Identity & Dossier -->
                    <div class="p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">1</span>
                                <span>Client Dossier & Contact</span>
                            </h2>
                            <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#6b737b]">
                                Confidential
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Email Address (For Order Tracking & Certificate) *
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', 'collector@horology.com') }}" required
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                    placeholder="your.email@domain.com">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        First Name *
                                    </label>
                                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="Alexander">
                                </div>
                                <div>
                                    <label for="last_name" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Last Name *
                                    </label>
                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="Sterling">
                                </div>
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Mobile Phone Number (Required For Courier Dispatch & Signature) *
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                    placeholder="+1 (555) 234-5678">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Vault Delivery Address -->
                    <div class="p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">2</span>
                                <span>Vault Delivery Destination</span>
                            </h2>
                            <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#5ea391] flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#5ea391]"></span>
                                Insured
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="country" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Country / Sovereign Territory *
                                </label>
                                <select id="country" name="country" required
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white transition-colors outline-none font-['Inter',sans-serif]">
                                    <option value="United States" selected>United States</option>
                                    <option value="Switzerland">Switzerland (Geneva / Zurich)</option>
                                    <option value="United Kingdom">United Kingdom (London)</option>
                                    <option value="United Arab Emirates">United Arab Emirates (Dubai / Abu Dhabi)</option>
                                    <option value="France">France (Paris)</option>
                                    <option value="Germany">Germany (Munich / Frankfurt)</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Japan">Japan (Tokyo)</option>
                                    <option value="Pakistan">Pakistan (Karachi / Lahore / Islamabad)</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Australia">Australia</option>
                                </select>
                            </div>

                            <div>
                                <label for="address" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Street Address *
                                </label>
                                <input type="text" id="address" name="address" value="{{ old('address') }}" required
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                    placeholder="740 Park Avenue">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="apartment" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Suite / Floor
                                    </label>
                                    <input type="text" id="apartment" name="apartment" value="{{ old('apartment') }}"
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="Penthouse 14B">
                                </div>
                                <div>
                                    <label for="city" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        City *
                                    </label>
                                    <input type="text" id="city" name="city" value="{{ old('city') }}" required
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="New York">
                                </div>
                                <div>
                                    <label for="postal_code" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Postal / ZIP *
                                    </label>
                                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="10021">
                                </div>
                            </div>

                            <div>
                                <label for="delivery_notes" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Special Delivery & Security Gate Instructions (Optional)
                                </label>
                                <textarea id="delivery_notes" name="delivery_notes" rows="2"
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-xs text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                    placeholder="e.g. Ring private concierge desk, mandatory identity inspection upon arrival."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Transit Speed & Tier Selection -->
                    <div class="p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">3</span>
                                <span>Vault Transit Protocol</span>
                            </h2>
                            <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#dd9c7c]">
                                Tamper Sealed
                            </span>
                        </div>

                        <div class="space-y-3">
                            <!-- Option 1: Complimentary Armored Courier -->
                            <div @click="updateShipping('armored')"
                                class="p-4 rounded-xl border transition-all cursor-pointer flex items-center justify-between gap-4"
                                :class="shippingMethod === 'armored' ? 'border-[#cb8665] bg-[#cb8665]/10 shadow-[0_0_20px_rgba(203,152,126,0.15)]' : 'border-white/10 bg-[#080b0e] hover:border-white/20'">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                                        :class="shippingMethod === 'armored' ? 'border-[#cb8665] bg-[#cb8665]' : 'border-white/20'">
                                        <div class="w-2 h-2 rounded-full bg-[#0b0f12]" x-show="shippingMethod === 'armored'"></div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-['Montserrat',sans-serif] font-bold text-white uppercase tracking-wider">
                                            Complimentary Armored Courier Transit
                                        </h4>
                                        <p class="text-[11.5px] text-[#8e969d] mt-0.5">
                                            3–5 Business Days • Tamper-evident holographic sealed case • Full Escrow Insurance
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold text-[#5ea391] uppercase tracking-wider">
                                    FREE
                                </span>
                            </div>

                            <!-- Option 2: Express White-Glove Concierge -->
                            <div @click="updateShipping('concierge')"
                                class="p-4 rounded-xl border transition-all cursor-pointer flex items-center justify-between gap-4"
                                :class="shippingMethod === 'concierge' ? 'border-[#cb8665] bg-[#cb8665]/10 shadow-[0_0_20px_rgba(203,152,126,0.15)]' : 'border-white/10 bg-[#080b0e] hover:border-white/20'">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                                        :class="shippingMethod === 'concierge' ? 'border-[#cb8665] bg-[#cb8665]' : 'border-white/20'">
                                        <div class="w-2 h-2 rounded-full bg-[#0b0f12]" x-show="shippingMethod === 'concierge'"></div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xs font-['Montserrat',sans-serif] font-bold text-white uppercase tracking-wider">
                                                White-Glove Concierge Air Transit
                                            </h4>
                                            <span class="px-2 py-0.5 rounded text-[9.5px] font-bold bg-[#cb8665] text-[#0e1215] uppercase">
                                                Fastest
                                            </span>
                                        </div>
                                        <p class="text-[11.5px] text-[#8e969d] mt-0.5">
                                            Next Business Day by 12:00 PM • Dedicated hand-courier escort • Personal wrist fitting
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold text-white tracking-wider">
                                    +$150.00
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Bespoke Atelier Services (Complimentary) -->
                    <div class="p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">4</span>
                                <span>Bespoke Atelier Touches</span>
                            </h2>
                            <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#5ea391]">
                                Complimentary
                            </span>
                        </div>

                        <div class="space-y-4">
                            <!-- Custom Laser Engraving Checkbox -->
                            <div class="p-4 rounded-xl bg-[#080b0e] border border-white/10 space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" x-model="hasEngraving"
                                        class="w-4 h-4 rounded text-[#cb8665] bg-[#141b22] border-white/20 focus:ring-[#cb8665]">
                                    <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-white font-semibold">
                                        Complimentary Caseback Laser Engraving
                                    </span>
                                </label>

                                <div x-show="hasEngraving" x-transition class="pt-2" style="display: none;">
                                    <input type="text" name="engraving" maxlength="25" x-model="engravingText"
                                        class="w-full px-4 py-2.5 rounded-lg bg-[#11171d] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#555e67] outline-none"
                                        placeholder="e.g. A.S. • 2026 or TO FOREVER">
                                    <div class="flex justify-between items-center text-[10.5px] text-[#8e969d] mt-1.5">
                                        <span>Max 25 Characters • Precision Diamond Cut</span>
                                        <span x-text="engravingText.length + ' / 25'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bracelet Sizing Measurement -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="wrist_size" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Complimentary Bracelet Sizing
                                    </label>
                                    <select id="wrist_size" name="wrist_size"
                                        class="w-full px-4 py-2.5 rounded-lg bg-[#080b0e] border border-white/15 text-xs text-white outline-none">
                                        <option value="factory">Factory Standard Length (Includes extra links)</option>
                                        <option value="6.5">Fitted for 6.5 in (16.5 cm) wrist</option>
                                        <option value="7.0">Fitted for 7.0 in (17.8 cm) wrist</option>
                                        <option value="7.5">Fitted for 7.5 in (19.0 cm) wrist</option>
                                        <option value="8.0">Fitted for 8.0 in (20.3 cm) wrist</option>
                                    </select>
                                </div>

                                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#080b0e] border border-white/10">
                                    <svg class="w-6 h-6 stroke-[#dd9c7c] shrink-0" viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                    </svg>
                                    <div>
                                        <span class="text-xs font-semibold text-white block">Collector Lacquered Presentation Box</span>
                                        <span class="text-[10.5px] text-[#8e969d]">Hand-crafted walnut timber & velvet travel pouch included</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Payment Escrow Method -->
                    <div class="p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">5</span>
                                <span>Payment & Escrow Authorization</span>
                            </h2>
                            <div class="flex items-center gap-1.5 text-xs text-[#8e969d]">
                                <span class="text-[11px] text-[#5ea391]">SSL SECURE</span>
                            </div>
                        </div>

                        <!-- Payment Method Radios / Selector -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <button type="button" @click="paymentMethod = 'card'"
                                class="p-3 rounded-xl border text-center transition-all cursor-pointer"
                                :class="paymentMethod === 'card' ? 'border-[#cb8665] bg-[#cb8665]/15 text-white' : 'border-white/10 bg-[#080b0e] text-[#8e969d] hover:border-white/20'">
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold uppercase tracking-wider block">Credit Card</span>
                                <span class="text-[10px] opacity-70 block mt-0.5">Visa / MC / AMEX</span>
                            </button>

                            <button type="button" @click="paymentMethod = 'wire'"
                                class="p-3 rounded-xl border text-center transition-all cursor-pointer"
                                :class="paymentMethod === 'wire' ? 'border-[#cb8665] bg-[#cb8665]/15 text-white' : 'border-white/10 bg-[#080b0e] text-[#8e969d] hover:border-white/20'">
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold uppercase tracking-wider block">Bank Wire</span>
                                <span class="text-[10px] opacity-70 block mt-0.5">Swiss Escrow</span>
                            </button>

                            <button type="button" @click="paymentMethod = 'crypto'"
                                class="p-3 rounded-xl border text-center transition-all cursor-pointer"
                                :class="paymentMethod === 'crypto' ? 'border-[#cb8665] bg-[#cb8665]/15 text-white' : 'border-white/10 bg-[#080b0e] text-[#8e969d] hover:border-white/20'">
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold uppercase tracking-wider block">Crypto</span>
                                <span class="text-[10px] opacity-70 block mt-0.5">BTC / USDT / ETH</span>
                            </button>

                            <button type="button" @click="paymentMethod = 'cod'"
                                class="p-3 rounded-xl border text-center transition-all cursor-pointer"
                                :class="paymentMethod === 'cod' ? 'border-[#cb8665] bg-[#cb8665]/15 text-white' : 'border-white/10 bg-[#080b0e] text-[#8e969d] hover:border-white/20'">
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold uppercase tracking-wider block">COD / Inspect</span>
                                <span class="text-[10px] opacity-70 block mt-0.5">Pay on Hand-off</span>
                            </button>
                        </div>

                        <!-- Card Form Inputs -->
                        <div x-show="paymentMethod === 'card'" class="space-y-4 pt-2">
                            <div>
                                <label class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Cardholder Name
                                </label>
                                <input type="text" name="card_name" x-model="cardName"
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] outline-none"
                                    placeholder="ALEXANDER STERLING">
                            </div>

                            <div>
                                <label class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Card Number
                                </label>
                                <div class="relative">
                                    <input type="text" name="card_number" x-model="cardNumber" maxlength="19"
                                        class="w-full pl-4 pr-12 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white font-mono placeholder-[#4e555d] outline-none"
                                        placeholder="4532 •••• •••• 8892">
                                    <div class="absolute right-4 top-3.5 text-xs text-[#8e969d] font-semibold">
                                        CARD
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Expiration Date
                                    </label>
                                    <input type="text" name="card_exp" x-model="cardExp" maxlength="5"
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white font-mono placeholder-[#4e555d] outline-none"
                                        placeholder="MM/YY">
                                </div>
                                <div>
                                    <label class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Security Code (CVV)
                                    </label>
                                    <input type="password" name="card_cvv" x-model="cardCvv" maxlength="4"
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white font-mono placeholder-[#4e555d] outline-none"
                                        placeholder="•••">
                                </div>
                            </div>
                        </div>

                        <!-- Wire Transfer Info -->
                        <div x-show="paymentMethod === 'wire'" class="p-4 rounded-xl bg-[#080b0e] border border-white/10 space-y-2 text-xs" style="display: none;">
                            <h4 class="font-['Montserrat',sans-serif] uppercase text-white font-bold">Swiss Private Escrow Account</h4>
                            <p class="text-[#8e969d] leading-relaxed">
                                Upon order authorization, your timepiece is reserved in our Geneva atelier vault for 48 hours. Comprehensive wire transfer coordinates and escrow authentication keys will be dispatched to your email.
                            </p>
                        </div>

                        <!-- Crypto Info -->
                        <div x-show="paymentMethod === 'crypto'" class="p-4 rounded-xl bg-[#080b0e] border border-white/10 space-y-2 text-xs" style="display: none;">
                            <h4 class="font-['Montserrat',sans-serif] uppercase text-white font-bold">Direct Blockchain Settlement</h4>
                            <p class="text-[#8e969d] leading-relaxed">
                                We accept Bitcoin (BTC), Ethereum (ETH), and Tether (USDT-ERC20). Real-time exchange rate is locked for 60 minutes upon order confirmation.
                            </p>
                        </div>

                        <!-- COD Info -->
                        <div x-show="paymentMethod === 'cod'" class="p-4 rounded-xl bg-[#080b0e] border border-white/10 space-y-2 text-xs" style="display: none;">
                            <h4 class="font-['Montserrat',sans-serif] uppercase text-white font-bold">White-Glove Physical Inspection on Hand-off</h4>
                            <p class="text-[#8e969d] leading-relaxed">
                                Inspect your certified timepiece, authenticity certificate, and packaging before releasing payment directly to our armored security courier.
                            </p>
                        </div>

                    </div>

                </div>

                <!-- Right Column: Sticky Horology Order Summary (5 cols on LG, 5 on XL) -->
                <div class="lg:col-span-5 xl:col-span-5 sticky top-24 space-y-6">

                    <!-- Order Summary Card -->
                    <div class="p-6 sm:p-8 rounded-2xl bg-[#0e1419]/95 border border-white/15 shadow-[0_20px_50px_rgba(0,0,0,0.85)] space-y-6">
                        
                        <div class="flex items-center justify-between pb-4 border-b border-white/10">
                            <h3 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal">
                                Horology Order Summary
                            </h3>
                            <span class="text-xs font-mono text-[#cb8665] bg-[#cb8665]/10 px-2.5 py-1 rounded-full border border-[#cb8665]/30"
                                  x-text="cartItems.reduce((acc, i) => acc + (parseInt(i.quantity) || 1), 0) + (cartItems.reduce((acc, i) => acc + (parseInt(i.quantity) || 1), 0) === 1 ? ' Piece' : ' Pieces')">
                                {{ count($cartItems) }} Pieces
                            </span>
                        </div>

                        <!-- Watch Items List -->
                        <div class="space-y-4 max-h-[380px] overflow-y-auto pr-1 divide-y divide-white/5">
                            <template x-for="(item, idx) in cartItems" :key="idx">
                                <div class="flex gap-4 items-start pt-4 first:pt-0 pb-2">
                                    <div class="w-16 h-16 rounded-xl bg-[#080b0e] border border-white/10 p-2 shrink-0 flex items-center justify-center overflow-hidden">
                                        <img :src="item.watch.image" :alt="item.watch.title" class="w-full h-full object-contain">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[10px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#dd9c7c] font-bold block"
                                              x-text="item.watch.brand">
                                        </span>
                                        <h4 class="font-['Outfit',sans-serif] text-sm text-white font-medium truncate mt-0.5"
                                            x-text="item.watch.title">
                                        </h4>
                                        <p class="text-xs text-[#8e969d] mt-0.5 font-['Inter',sans-serif]" x-show="item.variant">
                                            Variant: <span class="text-white" x-text="item.variant"></span>
                                        </p>

                                        <!-- Quantity Controller in Summary -->
                                        <div class="flex items-center justify-between mt-2.5">
                                            <div class="flex items-center border border-white/15 rounded-lg bg-[#080b0e] px-1.5 py-0.5">
                                                <button type="button" @click="updateQuantity(idx, (item.quantity || 1) - 1)" class="text-xs text-[#8e969d] hover:text-white px-1.5">−</button>
                                                <span class="text-xs font-bold text-white px-2 font-mono" x-text="item.quantity"></span>
                                                <button type="button" @click="updateQuantity(idx, (item.quantity || 1) + 1)" class="text-xs text-[#8e969d] hover:text-white px-1.5">+</button>
                                            </div>
                                            <span class="font-['Montserrat',sans-serif] font-bold text-white text-sm"
                                                  x-text="'$' + ((parseFloat(item.unit_price) || 0) * (parseInt(item.quantity) || 1)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Promo / Collector Privilege Code Input -->
                        <div>
                            <label class="block text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                Collector Privilege / Invitation Code
                            </label>
                            <div class="flex gap-2">
                                <input type="text" x-model="promoInput" placeholder="Try 'TIMEYRAH10' or 'VIP2026'"
                                    class="flex-1 px-3.5 py-2 rounded-xl bg-[#080b0e] border border-white/15 text-xs text-white uppercase placeholder-[#555e67] outline-none font-mono">
                                <button type="button" @click="applyPromo()"
                                    class="px-4 py-2 rounded-xl bg-[#141b22] border border-white/20 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-white hover:border-[#cb8665] hover:text-[#dd9c7c] transition-colors">
                                    Apply
                                </button>
                            </div>

                            <p x-show="promoMessage" class="text-xs text-[#5ea391] mt-1.5 flex items-center justify-between" style="display: none;">
                                <span x-text="promoMessage"></span>
                                <button type="button" @click="removePromo()" class="text-[#8e969d] hover:text-white underline text-[10.5px]">Remove</button>
                            </p>
                            <p x-show="promoError" class="text-xs text-red-400 mt-1.5" x-text="promoError" style="display: none;"></p>
                        </div>

                        <!-- Price Tiers Calculation -->
                        <div class="space-y-2.5 pt-3 border-t border-white/10 text-xs font-['Inter',sans-serif]">
                            <div class="flex justify-between text-[#8e969d]">
                                <span>Subtotal</span>
                                <span class="text-white font-medium" x-text="'$' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                            </div>

                            <div class="flex justify-between text-[#8e969d]">
                                <span>Armored Vault Courier Transit</span>
                                <span :class="shippingMethod === 'concierge' ? 'text-white font-medium' : 'text-[#5ea391] font-semibold'"
                                      x-text="shippingMethod === 'concierge' ? '$150.00' : 'Complimentary'"></span>
                            </div>

                            <div class="flex justify-between text-[#8e969d]">
                                <span>Escrow Authentication & Insurance</span>
                                <span class="text-[#5ea391] font-semibold">Included ($0.00)</span>
                            </div>

                            <div x-show="discount > 0" class="flex justify-between text-[#dd9c7c] font-medium" style="display: none;">
                                <span>Collector Discount</span>
                                <span x-text="'−$' + discount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                            </div>

                            <!-- Total -->
                            <div class="pt-4 border-t border-white/15 flex items-baseline justify-between">
                                <div>
                                    <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-widest text-[#8e969d] font-bold block">
                                        Total Acquisition Amount
                                    </span>
                                    <span class="text-[10px] text-[#6b737b] block mt-0.5 font-light">
                                        VAT and import duties guaranteed fully covered
                                    </span>
                                </div>
                                <span class="font-['Montserrat',sans-serif] text-2xl sm:text-3xl font-bold text-white tracking-tight"
                                      x-text="'$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                            </div>
                        </div>

                        <!-- Submit Order Primary Button -->
                        <button type="submit"
                            :disabled="isSubmitting"
                            class="w-full py-4 px-8 rounded-xl font-['Outfit',sans-serif] text-sm font-semibold tracking-widest uppercase text-[#0e1215] bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] hover:brightness-110 shadow-[0_6px_30px_rgba(203,152,126,0.4)] transition-all flex items-center justify-center gap-3 cursor-pointer disabled:opacity-50">
                            <svg class="w-4 h-4 text-[#0e1215]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <span x-text="isSubmitting ? 'AUTHORIZING ESCROW TRANSIT...' : 'AUTHORIZE PAYMENT & CONFIRM ORDER'"></span>
                        </button>

                        <!-- Trust Guarantees -->
                        <div class="pt-2 text-center space-y-2">
                            <p class="text-[11px] text-[#8e969d]">
                                By placing this order, you agree to TIMEYRAH's Atelier Terms of Sale and Escrow Protocol.
                            </p>
                            <div class="flex items-center justify-center gap-4 text-[10.5px] text-[#6b737b]">
                                <span>✓ 30-Day Return</span>
                                <span>•</span>
                                <span>✓ Guaranteed Authentic</span>
                                <span>•</span>
                                <span>✓ Insured Transit</span>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </form>

    </main>

    <!-- Global Website Footer -->
    <x-footer />

</body>

</html>
