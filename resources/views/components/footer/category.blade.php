@props([
    'cards' => [],
    'categoryIds' => [],
])

@php
    $accentColors = ['#9eb5b3', '#d5a48e', '#5cb9a5', '#e4ab91'];

    $defaultCards = [
        [
            'title' => 'Automatic Watches',
            'button_text' => 'Shop Now',
            'link_url' => route('shop', ['category' => 'automatic']),
            'image_url' => asset('images/footer/cat_sports.jpg'),
            'accent_color' => '#9eb5b3',
        ],
        [
            'title' => 'Leather & Strap',
            'button_text' => 'Shop Now',
            'link_url' => route('shop', ['category' => 'strap']),
            'image_url' => asset('images/footer/cat_limited.jpg'),
            'accent_color' => '#d5a48e',
        ],
        [
            'title' => 'Steel & Titanium',
            'button_text' => 'Shop Now',
            'link_url' => route('shop', ['category' => 'metal-chain']),
            'image_url' => asset('images/footer/cat_mens.jpg'),
            'accent_color' => '#5cb9a5',
        ],
        [
            'title' => 'Precision Quartz',
            'button_text' => 'Shop Now',
            'link_url' => route('shop', ['category' => 'quartz']),
            'image_url' => asset('images/footer/cat_womens.jpg'),
            'accent_color' => '#e4ab91',
        ],
    ];

    $displayCards = [];

    if (! empty($cards) && is_array($cards)) {
        foreach ($cards as $idx => $card) {
            $rawImg = $card['image'] ?? null;
            $fallback = $defaultCards[$idx % count($defaultCards)];
            $imgUrl = ! empty($rawImg) ? (str_starts_with($rawImg, 'http') ? $rawImg : asset($rawImg)) : $fallback['image_url'];
            $displayCards[] = [
                'title' => ! empty($card['title']) ? $card['title'] : $fallback['title'],
                'button_text' => ! empty($card['button_text']) ? $card['button_text'] : $fallback['button_text'],
                'link_url' => ! empty($card['link_url']) ? $card['link_url'] : $fallback['link_url'],
                'image_url' => $imgUrl,
                'accent_color' => ! empty($card['accent_color']) ? $card['accent_color'] : ($accentColors[$idx % count($accentColors)]),
            ];
        }
    }

    if (empty($displayCards)) {
        $displayCards = $defaultCards;
    }
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 lg:gap-5">
    @foreach ($displayCards as $card)
        <a href="{{ $card['link_url'] }}"
            class="veltra-card-item group relative h-[210px] min-[480px]:h-[230px] sm:h-[260px] lg:h-[290px] xl:h-[320px] rounded-[18px] overflow-hidden flex flex-col justify-end p-4 sm:p-5 lg:p-6 transition-all duration-300">

            <img src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}"
                class="absolute inset-0 w-full h-full object-cover object-right sm:object-center transition-transform duration-700 ease-out group-hover:scale-105">

            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/50 to-transparent pointer-events-none">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent pointer-events-none">
            </div>

            <div class="relative z-10 max-w-[90%]">
                <h4 class="font-['Montserrat',sans-serif] text-white font-medium text-[14px] sm:text-[15px] lg:text-[16px] mb-1.5 drop-shadow">
                    {{ $card['title'] }}
                </h4>

                <div
                    class="inline-flex items-center gap-1.5 text-[11.5px] sm:text-[12px] font-medium transition-colors duration-300 group-hover:text-white"
                    style="color: {{ $card['accent_color'] }}">
                    <span>{{ $card['button_text'] }}</span>
                    <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </div>
            </div>
        </a>
    @endforeach
</div>
