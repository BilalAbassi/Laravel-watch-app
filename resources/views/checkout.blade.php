<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Secure Checkout — TIMEYRAH Watches</title>

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
    <meta name="description" content="Secure checkout and free delivery across Pakistan for 100% original TIMEYRAH watches.">

    <!-- Fonts: Cormorant Garamond, Montserrat, Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js Checkout Logic Handler -->
    <script>
        function checkoutHandler() {
            return {
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

                init() {
                    try {
                        var urlParams = new URLSearchParams(window.location.search);
                        if (!urlParams.get('product_id')) {
                            var localRaw = localStorage.getItem('timeyrah_cart');
                            if (localRaw) {
                                var parsed = JSON.parse(localRaw);
                                if (Array.isArray(parsed) && parsed.length > 0) {
                                    this.cartItems = parsed.map(function(item) {
                                        var p = parseFloat(item.price) || 0;
                                        var q = parseInt(item.quantity, 10) || 1;
                                        return {
                                            id: item.id,
                                            key: item.key || (item.id + '_' + (item.variant || 'Standard')),
                                            title: item.title || 'Original Watch',
                                            brand: item.brand || 'TIMEYRAH',
                                            image: item.image || '{{ asset('watches/astra-black.png') }}',
                                            variant: item.variant || 'Signature Dial',
                                            unit_price: p,
                                            quantity: q,
                                            line_total: p * q,
                                            watch: {
                                                id: item.id,
                                                title: item.title || 'Original Watch',
                                                brand: item.brand || 'TIMEYRAH',
                                                image: item.image || '{{ asset('watches/astra-black.png') }}',
                                                price: p
                                            }
                                        };
                                    });
                                }
                            }
                        }
                    } catch (err) {
                        console.error('Cart sync error:', err);
                    }
                },

                getItemImage(item) {
                    if (item && item.watch && item.watch.image) {
                        return item.watch.image;
                    }
                    return (item && item.image) ? item.image : '{{ asset('watches/astra-black.png') }}';
                },

                getItemTitle(item) {
                    if (item && item.watch && item.watch.title) {
                        return item.watch.title;
                    }
                    return (item && item.title) ? item.title : 'Original Watch';
                },

                getItemBrand(item) {
                    if (item && item.watch && item.watch.brand) {
                        return item.watch.brand;
                    }
                    return (item && item.brand) ? item.brand : 'TIMEYRAH';
                },

                getItemPrice(item) {
                    if (!item) return 0;
                    if (item.unit_price !== undefined) return parseFloat(item.unit_price) || 0;
                    if (item.price !== undefined) return parseFloat(item.price) || 0;
                    if (item.watch && item.watch.price !== undefined) return parseFloat(item.watch.price) || 0;
                    return 0;
                },

                getItemQuantity(item) {
                    return (item && item.quantity) ? (parseInt(item.quantity, 10) || 1) : 1;
                },

                get hasItems() {
                    return Array.isArray(this.cartItems) && this.cartItems.length > 0;
                },

                get totalItemCount() {
                    if (!this.hasItems) return 0;
                    var self = this;
                    return this.cartItems.reduce(function(acc, i) {
                        return acc + self.getItemQuantity(i);
                    }, 0);
                },

                get subtotal() {
                    if (!this.hasItems) return 0;
                    var self = this;
                    return this.cartItems.reduce(function(acc, item) {
                        return acc + (self.getItemPrice(item) * self.getItemQuantity(item));
                    }, 0);
                },

                get total() {
                    var totalVal = this.subtotal - this.discount + (this.shippingMethod === 'concierge' ? 150 : 0);
                    return Math.max(0, totalVal);
                },

                updateQuantity(index, newQty) {
                    if (newQty < 1) {
                        this.removeItem(index);
                        return;
                    }
                    this.cartItems[index].quantity = newQty;
                    this.cartItems[index].line_total = this.getItemPrice(this.cartItems[index]) * newQty;
                    this.syncWithLocalStorage();
                },

                removeItem(index) {
                    this.cartItems.splice(index, 1);
                    this.syncWithLocalStorage();
                },

                syncWithLocalStorage() {
                    try {
                        if (window.Alpine && window.Alpine.store && window.Alpine.store('cart')) {
                            var self = this;
                            var mapped = this.cartItems.map(function(item) {
                                return {
                                    key: item.key || (item.id + '_' + (item.variant || 'Standard')),
                                    id: item.id || (item.watch ? item.watch.id : 1),
                                    title: self.getItemTitle(item),
                                    brand: self.getItemBrand(item),
                                    price: self.getItemPrice(item),
                                    image: self.getItemImage(item),
                                    variant: item.variant || 'Signature Dial',
                                    quantity: self.getItemQuantity(item)
                                };
                            });
                            window.Alpine.store('cart').items = mapped;
                            window.Alpine.store('cart').persist();
                        }
                    } catch (e) {}
                },

                updateShipping(method) {
                    this.shippingMethod = method;
                    this.shippingCost = method === 'concierge' ? 150 : 0;
                },

                applyPromo() {
                    this.promoError = '';
                    this.promoMessage = '';
                    var code = (this.promoInput || '').trim().toUpperCase();

                    if (code === 'TIMEYRAH10') {
                        this.appliedPromo = code;
                        this.discount = Math.round(this.subtotal * 0.10 * 100) / 100;
                        this.promoMessage = '10% Discount Applied Successfully!';
                    } else if (code === 'VIP2026') {
                        this.appliedPromo = code;
                        this.discount = Math.min(this.subtotal, 200.00);
                        this.promoMessage = '$200 Discount Voucher Applied!';
                    } else if (code === '') {
                        this.promoError = 'Please enter a coupon code.';
                    } else {
                        this.promoError = 'Invalid or expired coupon code.';
                    }
                },

                removePromo() {
                    this.appliedPromo = '';
                    this.discount = 0;
                    this.promoInput = '';
                    this.promoMessage = '';
                    this.promoError = '';
                }
            };
        }

        if (typeof window !== 'undefined') {
            window.checkoutHandler = checkoutHandler;
            document.addEventListener('alpine:init', function() {
                if (window.Alpine && window.Alpine.data) {
                    Alpine.data('checkoutHandler', checkoutHandler);
                }
            });
        }
    </script>
</head>

<body class="bg-[#0b0f12] text-[#d1d6da] min-h-screen selection:bg-[#cb8665]/30 selection:text-white"
    x-data="checkoutHandler()">

    <!-- Top Navigation Component (Includes Theme Toggle, Dock & Brand Menu) -->
    <x-navbar />

    <!-- Checkout Steps Breadcrumb Ribbon -->
    <section class="veltra-checkout-ribbon border-b border-white/5 bg-[#090d10]/60 py-3 px-4 sm:px-6 lg:px-12 text-center text-xs font-['Inter',sans-serif]">
        <div class="max-w-[1500px] mx-auto flex items-center justify-center gap-4 sm:gap-8 text-[11px] uppercase tracking-wider text-[#6b737b]">
            <span class="active-step-text text-[#e4ab91] font-semibold flex items-center gap-1.5">
                <span class="active-step-num w-4 h-4 rounded-full bg-[#cb8665]/20 text-[#e4ab91] flex items-center justify-center text-[10px] border border-[#cb8665]/40">1</span>
                <span>Customer & Address</span>
            </span>
            <span>—</span>
            <span class="active-step-text text-[#e4ab91] font-semibold flex items-center gap-1.5">
                <span class="active-step-num w-4 h-4 rounded-full bg-[#cb8665]/20 text-[#e4ab91] flex items-center justify-center text-[10px] border border-[#cb8665]/40">2</span>
                <span>Shipping & Payment</span>
            </span>
            <span>—</span>
            <span class="text-[#6b737b] flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-full bg-white/5 text-[#6b737b] flex items-center justify-center text-[10px] border border-white/10">3</span>
                <span>Confirmation</span>
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
            <input type="hidden" name="quantity" :value="cartItems[0] ? getItemQuantity(cartItems[0]) : 1">
            <input type="hidden" name="variant" value="{{ $variant }}">
            <input type="hidden" name="shipping_method" :value="shippingMethod">
            <input type="hidden" name="payment_method" :value="paymentMethod">
            <input type="hidden" name="promo_code" :value="appliedPromo">
            <input type="hidden" name="cart_items_json" :value="JSON.stringify(cartItems)">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-14 items-start">

                <!-- Left Column: Checkout Form (7 cols on LG, 7 on XL) -->
                <div class="lg:col-span-7 xl:col-span-7 space-y-8">

                    <!-- Section 1: Customer Identity & Contact -->
                    <div class="veltra-checkout-card p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="veltra-checkout-card-header flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">1</span>
                                <span>Personal Details & Contact</span>
                            </h2>
                            <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#6b737b]">
                                Safe & Secure
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Email Address (For Order Tracking & Updates) *
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()?->email ?? 'customer@example.com') }}" required
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                    placeholder="your.email@domain.com">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        First Name *
                                    </label>
                                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', Auth::user()?->name ? explode(' ', Auth::user()->name)[0] : '') }}" required
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="Ali">
                                </div>
                                <div>
                                    <label for="last_name" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Last Name *
                                    </label>
                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', (Auth::user()?->name && count(explode(' ', Auth::user()->name)) > 1) ? explode(' ', Auth::user()->name, 2)[1] : '') }}" required
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="Khan">
                                </div>
                            </div>

                            <div>
                                <label for="phone" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Mobile Phone Number (For Courier Delivery) *
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                    placeholder="+92 300 1234567">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Delivery Address -->
                    <div class="veltra-checkout-card p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="veltra-checkout-card-header flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">2</span>
                                <span>Delivery Address</span>
                            </h2>
                            <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#5ea391] flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#5ea391]"></span>
                                Free Delivery Across Pakistan
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="country" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Country *
                                </label>
                                <select id="country" name="country" required
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white transition-colors outline-none font-['Inter',sans-serif]">
                                    <option value="Pakistan" selected>Pakistan (All Cities & Districts)</option>
                                    <option value="United Arab Emirates">United Arab Emirates (Dubai / Abu Dhabi)</option>
                                    <option value="Saudi Arabia">Saudi Arabia (Riyadh / Jeddah)</option>
                                    <option value="United States">United States</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Australia">Australia</option>
                                </select>
                            </div>

                            <div>
                                <label for="address" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Complete Street Address *
                                </label>
                                <input type="text" id="address" name="address" value="{{ old('address') }}" required
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] focus:ring-1 focus:ring-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                    placeholder="House / Flat / Street address">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="apartment" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Apartment / Suite (Optional)
                                    </label>
                                    <input type="text" id="apartment" name="apartment" value="{{ old('apartment') }}"
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="Floor / Unit #">
                                </div>
                                <div>
                                    <label for="city" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        City *
                                    </label>
                                    <input type="text" id="city" name="city" value="{{ old('city') }}" required
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="Karachi, Lahore, Islamabad, etc.">
                                </div>
                                <div>
                                    <label for="postal_code" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Postal / ZIP Code *
                                    </label>
                                    <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required
                                        class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-sm text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                        placeholder="75500">
                                </div>
                            </div>

                            <div>
                                <label for="delivery_notes" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                    Special Delivery Notes / Nearest Landmark (Optional)
                                </label>
                                <textarea id="delivery_notes" name="delivery_notes" rows="2"
                                    class="w-full px-4 py-3 rounded-xl bg-[#080b0e] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#4e555d] transition-colors outline-none font-['Inter',sans-serif]"
                                    placeholder="e.g. Near main square, please call on mobile before arrival."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Shipping Method Selection -->
                    <div class="veltra-checkout-card p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="veltra-checkout-card-header flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">3</span>
                                <span>Shipping Method</span>
                            </h2>
                            <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#dd9c7c]">
                                Safe & Insured
                            </span>
                        </div>

                        <div class="space-y-3">
                            <!-- Option 1: Standard Courier Delivery -->
                            <div @click="updateShipping('armored')"
                                class="veltra-checkout-shipping-option p-4 rounded-xl border transition-all cursor-pointer flex items-center justify-between gap-4"
                                :class="shippingMethod === 'armored' ? 'is-active border-[#cb8665] bg-[#cb8665]/10 shadow-[0_0_20px_rgba(203,152,126,0.15)]' : 'border-white/10 bg-[#080b0e] hover:border-white/20'">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                                        :class="shippingMethod === 'armored' ? 'border-[#cb8665] bg-[#cb8665]' : 'border-white/20'">
                                        <div class="w-2 h-2 rounded-full bg-[#0b0f12]" x-show="shippingMethod === 'armored'"></div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-['Montserrat',sans-serif] font-bold text-white uppercase tracking-wider">
                                            Standard Courier Delivery (Free Delivery Across Pakistan)
                                        </h4>
                                        <p class="text-[11.5px] text-[#8e969d] mt-0.5">
                                            3–5 Business Days • Secure bubble-sealed packaging • Official Warranty Card
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold text-[#5ea391] uppercase tracking-wider">
                                    FREE
                                </span>
                            </div>

                            <!-- Option 2: Express Urgent Delivery -->
                            <div @click="updateShipping('concierge')"
                                class="veltra-checkout-shipping-option p-4 rounded-xl border transition-all cursor-pointer flex items-center justify-between gap-4"
                                :class="shippingMethod === 'concierge' ? 'is-active border-[#cb8665] bg-[#cb8665]/10 shadow-[0_0_20px_rgba(203,152,126,0.15)]' : 'border-white/10 bg-[#080b0e] hover:border-white/20'">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-5 h-5 rounded-full border flex items-center justify-center"
                                        :class="shippingMethod === 'concierge' ? 'border-[#cb8665] bg-[#cb8665]' : 'border-white/20'">
                                        <div class="w-2 h-2 rounded-full bg-[#0b0f12]" x-show="shippingMethod === 'concierge'"></div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xs font-['Montserrat',sans-serif] font-bold text-white uppercase tracking-wider">
                                                Express Urgent Delivery
                                            </h4>
                                            <span class="px-2 py-0.5 rounded text-[9.5px] font-bold bg-[#cb8665] text-[#0e1215] uppercase">
                                                Fastest
                                            </span>
                                        </div>
                                        <p class="text-[11.5px] text-[#8e969d] mt-0.5">
                                            Next Business Day Delivery • Priority Courier Dispatch
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold text-white tracking-wider">
                                    +$150.00
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Free Customization & Packaging -->
                    <div class="veltra-checkout-card p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="veltra-checkout-card-header flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">4</span>
                                <span>Free Customization & Packaging</span>
                            </h2>
                            <span class="text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#5ea391]">
                                Included Free
                            </span>
                        </div>

                        <div class="space-y-4">
                            <!-- Custom Laser Engraving Checkbox -->
                            <div class="veltra-checkout-inner-box p-4 rounded-xl bg-[#080b0e] border border-white/10 space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" x-model="hasEngraving"
                                        class="w-4 h-4 rounded text-[#cb8665] bg-[#141b22] border-white/20 focus:ring-[#cb8665]">
                                    <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-white font-semibold">
                                        Free Name or Message Engraving on Watch Back
                                    </span>
                                </label>

                                <div x-show="hasEngraving" x-transition class="pt-2" style="display: none;">
                                    <input type="text" name="engraving" maxlength="25" x-model="engravingText"
                                        class="w-full px-4 py-2.5 rounded-lg bg-[#11171d] border border-white/15 focus:border-[#cb8665] text-xs text-white placeholder-[#555e67] outline-none"
                                        placeholder="e.g. Ali Khan • 2026">
                                    <div class="flex justify-between items-center text-[10.5px] text-[#8e969d] mt-1.5">
                                        <span>Max 25 Characters (Optional)</span>
                                        <span x-text="engravingText.length + ' / 25'"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bracelet Sizing Measurement -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="wrist_size" class="block text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                        Free Strap / Bracelet Sizing
                                    </label>
                                    <select id="wrist_size" name="wrist_size"
                                        class="w-full px-4 py-2.5 rounded-lg bg-[#080b0e] border border-white/15 text-xs text-white outline-none">
                                        <option value="factory">Standard Size (Includes extra links)</option>
                                        <option value="6.5">Fitted for small wrist (6.5 in / 16.5 cm)</option>
                                        <option value="7.0">Fitted for medium wrist (7.0 in / 17.8 cm)</option>
                                        <option value="7.5">Fitted for standard wrist (7.5 in / 19.0 cm)</option>
                                        <option value="8.0">Fitted for large wrist (8.0 in / 20.3 cm)</option>
                                    </select>
                                </div>

                                <div class="veltra-checkout-inner-box flex items-center gap-3 p-3 rounded-xl bg-[#080b0e] border border-white/10">
                                    <svg class="w-6 h-6 stroke-[#dd9c7c] shrink-0" viewBox="0 0 24 24" fill="none" stroke-width="1.8">
                                        <rect width="20" height="14" x="2" y="7" rx="2" ry="2"/>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                    </svg>
                                    <div>
                                        <span class="text-xs font-semibold text-white block">Official Brand Box Included</span>
                                        <span class="text-[10.5px] text-[#8e969d]">Includes original watch box, warranty card & travel pouch</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Payment Method -->
                    <div class="veltra-checkout-card p-6 sm:p-8 rounded-2xl bg-[#0e1419]/90 border border-white/10 shadow-[0_15px_40px_rgba(0,0,0,0.6)] space-y-5">
                        <div class="veltra-checkout-card-header flex items-center justify-between pb-3 border-b border-white/5">
                            <h2 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal flex items-center gap-2.5">
                                <span class="text-sm font-['Montserrat',sans-serif] w-6 h-6 rounded-full bg-[#cb8665]/20 text-[#dd9c7c] border border-[#cb8665]/40 flex items-center justify-center font-bold">5</span>
                                <span>Select Payment Method</span>
                            </h2>
                            <div class="flex items-center gap-1.5 text-xs text-[#8e969d]">
                                <span class="text-[11px] text-[#5ea391]">100% SECURE</span>
                            </div>
                        </div>

                        <!-- Payment Method Radios / Selector -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <button type="button" @click="paymentMethod = 'card'"
                                class="veltra-checkout-pay-btn p-3 rounded-xl border text-center transition-all cursor-pointer"
                                :class="paymentMethod === 'card' ? 'is-active border-[#cb8665] bg-[#cb8665]/15 text-white' : 'border-white/10 bg-[#080b0e] text-[#8e969d] hover:border-white/20'">
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold uppercase tracking-wider block">Credit Card</span>
                                <span class="text-[10px] opacity-70 block mt-0.5">Visa / MC / AMEX</span>
                            </button>

                            <button type="button" @click="paymentMethod = 'wire'"
                                class="veltra-checkout-pay-btn p-3 rounded-xl border text-center transition-all cursor-pointer"
                                :class="paymentMethod === 'wire' ? 'is-active border-[#cb8665] bg-[#cb8665]/15 text-white' : 'border-white/10 bg-[#080b0e] text-[#8e969d] hover:border-white/20'">
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold uppercase tracking-wider block">Bank Transfer</span>
                                <span class="text-[10px] opacity-70 block mt-0.5">Online / IBAN</span>
                            </button>

                            <button type="button" @click="paymentMethod = 'crypto'"
                                class="veltra-checkout-pay-btn p-3 rounded-xl border text-center transition-all cursor-pointer"
                                :class="paymentMethod === 'crypto' ? 'is-active border-[#cb8665] bg-[#cb8665]/15 text-white' : 'border-white/10 bg-[#080b0e] text-[#8e969d] hover:border-white/20'">
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold uppercase tracking-wider block">Crypto</span>
                                <span class="text-[10px] opacity-70 block mt-0.5">USDT / BTC / ETH</span>
                            </button>

                            <button type="button" @click="paymentMethod = 'cod'"
                                class="veltra-checkout-pay-btn p-3 rounded-xl border text-center transition-all cursor-pointer"
                                :class="paymentMethod === 'cod' ? 'is-active border-[#cb8665] bg-[#cb8665]/15 text-white' : 'border-white/10 bg-[#080b0e] text-[#8e969d] hover:border-white/20'">
                                <span class="text-xs font-['Montserrat',sans-serif] font-bold uppercase tracking-wider block">Cash on Delivery</span>
                                <span class="text-[10px] opacity-70 block mt-0.5">Pay on Delivery</span>
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
                                    placeholder="ALI KHAN">
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
                        <div x-show="paymentMethod === 'wire'" class="veltra-checkout-inner-box p-4 rounded-xl bg-[#080b0e] border border-white/10 space-y-2 text-xs" style="display: none;">
                            <h4 class="font-['Montserrat',sans-serif] uppercase text-white font-bold">Direct Bank Transfer</h4>
                            <p class="text-[#8e969d] leading-relaxed">
                                Your watch will be reserved immediately upon placing the order. Complete account number and IBAN transfer instructions will be shown on your confirmation page and sent to your email.
                            </p>
                        </div>

                        <!-- Crypto Info -->
                        <div x-show="paymentMethod === 'crypto'" class="veltra-checkout-inner-box p-4 rounded-xl bg-[#080b0e] border border-white/10 space-y-2 text-xs" style="display: none;">
                            <h4 class="font-['Montserrat',sans-serif] uppercase text-white font-bold">Cryptocurrency Payment</h4>
                            <p class="text-[#8e969d] leading-relaxed">
                                We accept Tether (USDT), Bitcoin (BTC), and Ethereum (ETH). Wallet address and exact QR code will be provided upon order submission.
                            </p>
                        </div>

                        <!-- COD Info -->
                        <div x-show="paymentMethod === 'cod'" class="veltra-checkout-inner-box p-4 rounded-xl bg-[#080b0e] border border-white/10 space-y-2 text-xs" style="display: none;">
                            <h4 class="font-['Montserrat',sans-serif] uppercase text-white font-bold">Cash on Delivery (Available Across Pakistan)</h4>
                            <p class="text-[#8e969d] leading-relaxed">
                                Pay cash directly to the courier rider when your parcel is delivered to your doorstep. You can inspect the package before handing over payment.
                            </p>
                        </div>

                    </div>

                </div>

                <!-- Right Column: Sticky Order Summary (5 cols on LG, 5 on XL) -->
                <div class="lg:col-span-5 xl:col-span-5 sticky top-24 space-y-6">

                    <!-- Order Summary Card -->
                    <div class="veltra-checkout-card p-6 sm:p-8 rounded-2xl bg-[#0e1419]/95 border border-white/15 shadow-[0_20px_50px_rgba(0,0,0,0.85)] space-y-6">
                        
                        <div class="veltra-checkout-card-header flex items-center justify-between pb-4 border-b border-white/10">
                            <h3 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal">
                                Order Summary
                            </h3>
                            <span class="text-xs font-mono text-[#cb8665] bg-[#cb8665]/10 px-2.5 py-1 rounded-full border border-[#cb8665]/30"
                                  x-text="totalItemCount + (totalItemCount === 1 ? ' Watch' : ' Watches')">
                                {{ count($cartItems) }} Watches
                            </span>
                        </div>

                        <!-- Empty Cart State -->
                        <div x-show="!hasItems" class="py-8 text-center space-y-4" style="display: none;">
                            <div class="w-14 h-14 rounded-full bg-[#cb8665]/15 border border-[#cb8665]/40 mx-auto flex items-center justify-center text-[#cb8665]">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                                    <path d="M3 6h18" />
                                </svg>
                            </div>
                            <h4 class="font-['Montserrat',sans-serif] text-sm text-white font-semibold">Your cart is currently empty</h4>
                            <p class="text-xs text-[#8e969d]">Select a watch to proceed with checkout.</p>
                            <a href="{{ route('shop') }}" class="inline-block px-5 py-2.5 rounded-xl bg-[#cb8665] text-[#0e1215] text-xs font-bold uppercase tracking-wider">
                                Explore Watches →
                            </a>
                        </div>

                        <!-- Watch Items List -->
                        <div x-show="hasItems" class="space-y-4 max-h-[380px] overflow-y-auto pr-1 divide-y divide-white/5 veltra-checkout-divider">
                            <template x-for="(item, idx) in cartItems" :key="item.key || idx">
                                <div class="flex gap-4 items-start pt-4 first:pt-0 pb-2">
                                    <!-- Watch Image -->
                                    <div class="veltra-checkout-thumb w-16 h-16 rounded-xl bg-[#080b0e] border border-white/10 p-2 shrink-0 flex items-center justify-center overflow-hidden">
                                        <img :src="getItemImage(item)" :alt="getItemTitle(item)" class="w-full h-full object-contain">
                                    </div>

                                    <!-- Item Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-1">
                                            <span class="text-[10px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#dd9c7c] font-bold block truncate"
                                                  x-text="getItemBrand(item)">
                                            </span>
                                            <!-- Remove item button -->
                                            <button type="button" @click="removeItem(idx)" class="text-[#8e969d] hover:text-red-400 p-0.5 transition-colors" title="Remove watch">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>

                                        <h4 class="font-['Outfit',sans-serif] text-sm text-white font-medium truncate mt-0.5"
                                            x-text="getItemTitle(item)">
                                        </h4>
                                        <p class="text-xs text-[#8e969d] mt-0.5 font-['Inter',sans-serif]" x-show="item.variant">
                                            Option: <span class="text-white" x-text="item.variant"></span>
                                        </p>

                                        <!-- Quantity Controller in Summary -->
                                        <div class="flex items-center justify-between mt-2.5">
                                            <div class="veltra-checkout-stepper flex items-center border border-white/15 rounded-lg bg-[#080b0e] px-1.5 py-0.5">
                                                <button type="button" @click="updateQuantity(idx, getItemQuantity(item) - 1)" class="text-xs text-[#8e969d] hover:text-white px-1.5 cursor-pointer">−</button>
                                                <span class="text-xs font-bold text-white px-2 font-mono" x-text="getItemQuantity(item)"></span>
                                                <button type="button" @click="updateQuantity(idx, getItemQuantity(item) + 1)" class="text-xs text-[#8e969d] hover:text-white px-1.5 cursor-pointer">+</button>
                                            </div>
                                            <span class="font-['Montserrat',sans-serif] font-bold text-white text-sm"
                                                  x-text="'$' + (getItemPrice(item) * getItemQuantity(item)).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Promo / Coupon Code Input -->
                        <div x-show="hasItems">
                            <label class="block text-[11px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] mb-1.5 font-semibold">
                                Discount Coupon / Promo Code
                            </label>
                            <div class="flex gap-2">
                                <input type="text" x-model="promoInput" placeholder="Try 'TIMEYRAH10' or 'VIP2026'"
                                    class="flex-1 px-3.5 py-2 rounded-xl bg-[#080b0e] border border-white/15 text-xs text-white uppercase placeholder-[#555e67] outline-none font-mono">
                                <button type="button" @click="applyPromo()"
                                    class="veltra-checkout-promo-btn px-4 py-2 rounded-xl bg-[#141b22] border border-white/20 text-xs font-['Montserrat',sans-serif] uppercase tracking-wider font-semibold text-white hover:border-[#cb8665] hover:text-[#dd9c7c] transition-colors cursor-pointer">
                                    Apply
                                </button>
                            </div>

                            <p x-show="promoMessage" class="text-xs text-[#5ea391] mt-1.5 flex items-center justify-between" style="display: none;">
                                <span x-text="promoMessage"></span>
                                <button type="button" @click="removePromo()" class="text-[#8e969d] hover:text-white underline text-[10.5px] cursor-pointer">Remove</button>
                            </p>
                            <p x-show="promoError" class="text-xs text-red-400 mt-1.5" x-text="promoError" style="display: none;"></p>
                        </div>

                        <!-- Price Tiers Calculation -->
                        <div class="space-y-2.5 pt-3 border-t border-white/10 veltra-checkout-divider text-xs font-['Inter',sans-serif]" x-show="hasItems">
                            <div class="flex justify-between text-[#8e969d]">
                                <span>Subtotal</span>
                                <span class="text-white font-medium" x-text="'$' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                            </div>

                            <div class="flex justify-between text-[#8e969d]">
                                <span>Courier Delivery</span>
                                <span :class="shippingMethod === 'concierge' ? 'text-white font-medium' : 'text-[#5ea391] font-semibold'"
                                      x-text="shippingMethod === 'concierge' ? '$150.00' : 'Free'"></span>
                            </div>

                            <div class="flex justify-between text-[#8e969d]">
                                <span>Official Brand Warranty & Verification</span>
                                <span class="text-[#5ea391] font-semibold">Included Free</span>
                            </div>

                            <div x-show="discount > 0" class="flex justify-between text-[#dd9c7c] font-medium" style="display: none;">
                                <span>Coupon Discount</span>
                                <span x-text="'−$' + discount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                            </div>

                            <!-- Total -->
                            <div class="pt-4 border-t border-white/15 veltra-checkout-divider flex items-baseline justify-between">
                                <div>
                                    <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-widest text-[#8e969d] font-bold block">
                                        Total Amount
                                    </span>
                                    <span class="text-[10px] text-[#6b737b] block mt-0.5 font-light">
                                        All taxes & delivery charges included
                                    </span>
                                </div>
                                <span class="font-['Montserrat',sans-serif] text-2xl sm:text-3xl font-bold text-white tracking-tight"
                                      x-text="'$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
                            </div>
                        </div>

                        <!-- Submit Order Primary Button -->
                        <button type="submit"
                            :disabled="isSubmitting || !hasItems"
                            class="w-full py-4 px-8 rounded-xl font-['Outfit',sans-serif] text-sm font-semibold tracking-widest uppercase text-[#0e1215] bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] hover:brightness-110 shadow-[0_6px_30px_rgba(203,152,126,0.4)] transition-all flex items-center justify-center gap-3 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 text-[#0e1215]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <span x-text="isSubmitting ? 'PROCESSING YOUR ORDER...' : 'PLACE ORDER NOW'"></span>
                        </button>

                        <!-- Trust Guarantees -->
                        <div class="pt-2 text-center space-y-2">
                            <p class="text-[11px] text-[#8e969d]">
                                By placing this order, you agree to TIMEYRAH's Terms of Service and Return Policy.
                            </p>
                            <div class="flex items-center justify-center gap-4 text-[10.5px] text-[#6b737b]">
                                <span>✓ 30-Day Return</span>
                                <span>•</span>
                                <span>✓ 100% Original</span>
                                <span>•</span>
                                <span>✓ Safe Delivery</span>
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
