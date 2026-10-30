<!-- Luxury Horology Cart Slide-Over Drawer Component -->
<div x-data>
    <!-- Darkened Blur Backdrop -->
    <div x-show="$store.cart.isOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.cart.close()"
         class="fixed inset-0 bg-black/80 backdrop-blur-md z-[120]"
         style="display: none;"
         aria-hidden="true">
    </div>

    <!-- Slide-Over Drawer Panel -->
    <div x-show="$store.cart.isOpen"
         x-transition:enter="transform transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         @keydown.window.escape="$store.cart.close()"
         class="fixed inset-y-0 right-0 max-w-full w-full sm:max-w-md md:max-w-lg bg-[#0e1215] border-l border-white/10 shadow-[0_0_60px_rgba(0,0,0,0.9)] z-[125] flex flex-col justify-between"
         style="display: none;"
         role="dialog"
         aria-modal="true"
         aria-labelledby="cart-drawer-title">

        <!-- Drawer Header -->
        <div class="px-6 py-5 border-b border-white/10 flex items-center justify-between bg-[#141b22]/90 backdrop-blur-xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-[#cb987e]/15 border border-[#cb987e]/40 flex items-center justify-center text-[#cb987e]">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                        <path d="M3 6h18" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                </div>
                <div>
                    <h2 id="cart-drawer-title" class="font-['Montserrat',sans-serif] text-sm uppercase tracking-[0.25em] text-white font-semibold">
                        Horology Bag
                    </h2>
                    <span class="text-[11px] text-[#8e969d] font-['Inter',sans-serif]"
                          x-text="$store.cart.count === 0 ? 'Empty bag' : ($store.cart.count + ($store.cart.count === 1 ? ' timepiece reserved' : ' timepieces reserved'))">
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span x-show="$store.cart.count > 0"
                      class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-[#5ea391]/15 text-[#5ea391] border border-[#5ea391]/30"
                      x-text="$store.cart.count">
                </span>

                <button type="button"
                        @click="$store.cart.close()"
                        class="p-2 rounded-xl text-[#8e969d] hover:text-white hover:bg-white/5 transition-all duration-200 cursor-pointer group"
                        aria-label="Close Horology Bag">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Armored Transit Notice Banner -->
        <div class="px-6 py-2.5 bg-[#090d10] border-b border-white/5 flex items-center justify-between text-[11px] font-['Inter',sans-serif] text-[#8e969d]">
            <span class="flex items-center gap-1.5 text-[#5ea391]">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Armored Courier & Escrow
            </span>
            <span class="text-[#cb987e] font-medium tracking-wide">Complimentary</span>
        </div>

        <!-- Scrollable Products Area -->
        <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
            
            <!-- Empty State -->
            <div x-show="$store.cart.items.length === 0"
                 class="h-full flex flex-col items-center justify-center text-center py-16 px-4">
                <div class="w-20 h-20 rounded-2xl bg-[#141b22] border border-[#cb987e]/25 flex items-center justify-center mb-5 shadow-[0_0_30px_rgba(203,152,126,0.15)]">
                    <svg class="w-9 h-9 text-[#cb987e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                        <path d="M3 6h18"/>
                        <path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <h3 class="font-['Cormorant_Garamond',serif] text-2xl text-white font-normal mb-2">
                    Your Horology Bag is Empty
                </h3>
                <p class="font-['Inter',sans-serif] text-xs text-[#8e969d] max-w-xs leading-relaxed mb-6 font-light">
                    No timepieces have been reserved yet. Explore our curated catalog of certified Swiss chronographs and haute horlogerie masterworks.
                </p>
                <a href="{{ route('shop') }}" @click="$store.cart.close()"
                   class="px-7 py-3 rounded-xl bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] text-[#0e1215] text-xs font-['Outfit',sans-serif] font-bold tracking-widest uppercase hover:brightness-110 shadow-[0_4px_20px_rgba(203,152,126,0.3)] transition-all">
                    Explore The Collection →
                </a>
            </div>

            <!-- Populated Cart Items List -->
            <div x-show="$store.cart.items.length > 0" class="space-y-3.5">
                <template x-for="item in $store.cart.items" :key="item.key">
                    <div class="p-3.5 rounded-2xl bg-[#12181e] border border-white/5 hover:border-white/15 transition-all flex gap-4 items-center group">
                        
                        <!-- Watch Thumbnail Image -->
                        <div class="w-20 h-20 rounded-xl bg-[#080b0e] border border-white/10 p-2 shrink-0 flex items-center justify-center overflow-hidden relative">
                            <img :src="item.image" :alt="item.title"
                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.src='{{ asset('images/timeyar-circle-emblem.png') }}'">
                        </div>

                        <!-- Product Information -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <span class="text-[10px] font-['Montserrat',sans-serif] uppercase tracking-wider text-[#dd9c7c] font-bold block truncate"
                                          x-text="item.brand">
                                    </span>
                                    <h4 class="font-['Outfit',sans-serif] text-sm text-white font-medium truncate mt-0.5"
                                        x-text="item.title">
                                    </h4>
                                </div>

                                <!-- Remove Button -->
                                <button type="button"
                                        @click="$store.cart.removeItem(item.key)"
                                        class="text-[#6c747c] hover:text-red-400 p-1 rounded-lg hover:bg-white/5 transition-colors shrink-0"
                                        title="Remove timepiece from bag">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Variant Pill -->
                            <div class="mt-1 flex items-center gap-2">
                                <span class="text-[11px] font-['Inter',sans-serif] text-[#8e969d]">
                                    Finish: <strong class="text-[#d8dee2] font-normal" x-text="item.variant || 'Signature Dial'"></strong>
                                </span>
                            </div>

                            <!-- Stepper & Dynamic Pricing -->
                            <div class="flex items-center justify-between mt-3">
                                <!-- Stepper -->
                                <div class="flex items-center border border-white/15 rounded-lg bg-[#080b0e] px-1.5 py-0.5">
                                    <button type="button"
                                            @click="$store.cart.decrement(item.key)"
                                            class="w-6 h-6 flex items-center justify-center text-xs text-[#8e969d] hover:text-white transition-colors cursor-pointer"
                                            aria-label="Decrease quantity">
                                        −
                                    </button>
                                    <span class="text-xs font-bold text-white px-2 font-mono" x-text="item.quantity"></span>
                                    <button type="button"
                                            @click="$store.cart.increment(item.key)"
                                            class="w-6 h-6 flex items-center justify-center text-xs text-[#8e969d] hover:text-white transition-colors cursor-pointer"
                                            aria-label="Increase quantity">
                                        +
                                    </button>
                                </div>

                                <!-- Dynamic Line Price -->
                                <div class="text-right">
                                    <span class="font-['Montserrat',sans-serif] font-bold text-white text-sm"
                                          x-text="'$' + ((parseFloat(item.price) || 0) * item.quantity).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})">
                                    </span>
                                    <span x-show="item.quantity > 1" class="block text-[10px] text-[#6b737b] font-mono"
                                          x-text="'$' + (parseFloat(item.price) || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ea'">
                                    </span>
                                </div>
                            </div>

                        </div>

                    </div>
                </template>
            </div>

        </div>

        <!-- Drawer Footer: Subtotal & Actions -->
        <div x-show="$store.cart.items.length > 0"
             class="p-6 bg-[#0a0e12] border-t border-white/10 space-y-4">
            
            <!-- Subtotal Row -->
            <div class="flex items-baseline justify-between pb-3 border-b border-white/5">
                <div>
                    <span class="text-xs font-['Montserrat',sans-serif] uppercase tracking-wider text-[#8e969d] font-semibold block">
                        Estimated Subtotal
                    </span>
                    <span class="text-[10.5px] text-[#5ea391] flex items-center gap-1 mt-0.5 font-light">
                        <span>✓</span> Armored Transit & VAT Included
                    </span>
                </div>
                <span class="font-['Montserrat',sans-serif] text-2xl font-bold text-white tracking-tight"
                      x-text="$store.cart.formattedSubtotal">
                </span>
            </div>

            <!-- Primary Checkout Button -->
            <div class="space-y-2">
                <a href="{{ route('checkout') }}"
                   class="w-full py-4 px-6 rounded-xl font-['Outfit',sans-serif] text-xs font-bold tracking-widest uppercase text-[#0e1215] bg-gradient-to-r from-[#e4ab91] via-[#cb8665] to-[#9a5a3f] hover:brightness-110 shadow-[0_4px_25px_rgba(203,152,126,0.35)] transition-all flex items-center justify-center gap-2 cursor-pointer text-center group">
                    <span>PROCEED TO ATELIER CHECKOUT</span>
                    <span class="transition-transform duration-300 group-hover:translate-x-1">→</span>
                </a>

                <!-- Auxiliary Controls -->
                <div class="flex items-center justify-between text-xs pt-1 px-1 font-['Inter',sans-serif]">
                    <button type="button"
                            @click="$store.cart.close()"
                            class="text-[#8e969d] hover:text-white transition-colors cursor-pointer flex items-center gap-1">
                        <span>←</span> Continue Exploring
                    </button>

                    <button type="button"
                            @click="if (confirm('Clear all timepieces from your Horology Bag?')) $store.cart.clear()"
                            class="text-[#8e969d] hover:text-red-400 transition-colors cursor-pointer text-[11.5px]">
                        Clear Bag
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Global Alpine Cart Store Definition Script -->
<script>
    (function () {
        function registerTimeyrahCartStore() {
            if (!window.Alpine || window.Alpine.store('cart')) {
                return;
            }

            let initialItems = [];
            try {
                const raw = localStorage.getItem('timeyrah_cart');
                if (raw) {
                    const parsed = JSON.parse(raw);
                    if (Array.isArray(parsed)) {
                        initialItems = parsed;
                    }
                }
            } catch (err) {
                initialItems = [];
            }

            window.Alpine.store('cart', {
                items: initialItems,
                isOpen: false,
                animating: false,
                toastMessage: '',
                showToast: false,
                toastTimer: null,

                open() {
                    this.isOpen = true;
                },

                close() {
                    this.isOpen = false;
                },

                toggle() {
                    this.isOpen = !this.isOpen;
                },

                get count() {
                    return this.items.reduce(function (sum, item) {
                        return sum + (parseInt(item.quantity, 10) || 1);
                    }, 0);
                },

                get subtotal() {
                    return this.items.reduce(function (sum, item) {
                        const price = parseFloat(item.price) || 0;
                        const qty = parseInt(item.quantity, 10) || 1;
                        return sum + (price * qty);
                    }, 0);
                },

                get formattedSubtotal() {
                    return '$' + this.subtotal.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },

                addItem(product, quantity, variant) {
                    const qty = Math.max(1, parseInt(quantity, 10) || 1);
                    const chosenVariant = variant || 'Signature Dial';

                    let numericPrice = product.price;
                    if (typeof numericPrice === 'string') {
                        numericPrice = parseFloat(numericPrice.replace(/[^0-9.]/g, '')) || 0;
                    }

                    const key = String(product.id) + '_' + chosenVariant.replace(/\s+/g, '-').toLowerCase();
                    const existingIndex = this.items.findIndex(function (i) {
                        return i.key === key;
                    });

                    if (existingIndex > -1) {
                        this.items[existingIndex].quantity += qty;
                    } else {
                        this.items.push({
                            key: key,
                            id: product.id,
                            title: product.title || product.name || 'Master Timepiece',
                            brand: product.brand || 'TIMEYRAH',
                            price: numericPrice,
                            image: product.image || (product.images && product.images[0]) || '{{ asset("images/timeyar-circle-emblem.png") }}',
                            variant: chosenVariant,
                            quantity: qty
                        });
                    }

                    this.persist();
                    this.animateBadge();
                    this.notify('Added ' + qty + ' × ' + (product.title || product.name || 'timepiece') + ' to bag');

                    // Smoothly slide open the bag drawer so the user immediately sees the added piece
                    this.open();
                },

                updateQuantity(key, quantity) {
                    const qty = parseInt(quantity, 10);
                    if (qty <= 0) {
                        this.removeItem(key);
                        return;
                    }
                    const item = this.items.find(function (i) {
                        return i.key === key;
                    });
                    if (item) {
                        item.quantity = qty;
                        this.persist();
                    }
                },

                increment(key) {
                    const item = this.items.find(function (i) {
                        return i.key === key;
                    });
                    if (item) {
                        this.updateQuantity(key, (item.quantity || 1) + 1);
                    }
                },

                decrement(key) {
                    const item = this.items.find(function (i) {
                        return i.key === key;
                    });
                    if (item) {
                        this.updateQuantity(key, (item.quantity || 1) - 1);
                    }
                },

                removeItem(key) {
                    this.items = this.items.filter(function (i) {
                        return i.key !== key;
                    });
                    this.persist();
                },

                clear() {
                    this.items = [];
                    this.persist();
                },

                persist() {
                    try {
                        localStorage.setItem('timeyrah_cart', JSON.stringify(this.items));
                    } catch (e) {}

                    // Server session synchronization
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                        fetch('{{ route("cart.sync") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ items: this.items })
                        }).catch(function () {});
                    } catch (err) {}
                },

                animateBadge() {
                    this.animating = true;
                    const self = this;
                    setTimeout(function () {
                        self.animating = false;
                    }, 650);
                },

                notify(msg) {
                    this.toastMessage = msg;
                    this.showToast = true;
                    if (this.toastTimer) {
                        clearTimeout(this.toastTimer);
                    }
                    const self = this;
                    this.toastTimer = setTimeout(function () {
                        self.showToast = false;
                    }, 3500);
                }
            });
        }

        document.addEventListener('alpine:init', registerTimeyrahCartStore);
        if (window.Alpine) {
            registerTimeyrahCartStore();
        }
    })();
</script>
