@php
    $state = $getState();

    if ($state instanceof \Illuminate\Support\Collection) {
        $state = $state->all();
    }

    $state = \Illuminate\Support\Arr::wrap($state);

    $limit = $getLimit();
    $limitedState = $limit ? array_slice($state, 0, $limit) : $state;
    $remaining = $limit ? max(0, count($state) - $limit) : 0;

    $isCircular = $isCircular();
    $isSquare = $isSquare();
    $isStacked = $isStacked();
    $overlap = $isStacked ? $getOverlap() ?? 2 : 0;

    $defaultWidth = $getWidth();
    $defaultHeight = $getHeight();

    $defaultWidth = $defaultWidth ? (is_numeric($defaultWidth) ? $defaultWidth . 'px' : $defaultWidth) : 'auto';
    $defaultHeight = $defaultHeight ? (is_numeric($defaultHeight) ? $defaultHeight . 'px' : $defaultHeight) : '40px';

    $galleryId = 'gallery-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());

    // Calculate margin for stacked images using inline styles (Tailwind-safe)
    // Each unit = 0.25rem (4px)
    $stackedMarginValue = $overlap * 0.25;
    $stackedMargin = $isStacked && $overlap > 0 ? "-{$stackedMarginValue}rem" : '0';
@endphp

<div id="{{ $galleryId }}"
    {{ $attributes->merge($getExtraAttributes(), escape: false)->except('data-viewer-options')->class(['fi-ta-image', 'flex items-center', 'gap-1.5' => !$isStacked]) }}
    data-viewer-options='@json($getExtraAttributes()['data-viewer-options'] ?? [])'
    data-viewer-gallery wire:ignore.self>
    @foreach ($limitedState as $index => $stateItem)
        <img src="{{ $getImageUrl($stateItem) }}"
            style="
                height: {{ $defaultHeight }};
                width: {{ $defaultWidth }};
                cursor: pointer;
                @if ($isCircular) aspect-ratio: 1 / 1;
                    border-radius: 50%; @endif
                @if ($isStacked && $index > 0) margin-inline-start: {{ $stackedMargin }}; @endif
                @if ($isStacked) ring-color: rgb(var(--primary-500)); box-shadow: 0 0 0 3px rgb(var(--primary-500)); @endif
            "
            {{ $getExtraImgAttributeBag()->class([
                'max-w-none object-cover object-center',
                'rounded-full' => $isCircular,
                'rounded-lg' => $isSquare,
            ]) }} />
    @endforeach

    @if ($remaining > 0 && ($limitedRemainingText ?? true))
        @php
            $showBadge = ($getExtraAttributes()['data-remaining-text-badge'] ?? 'false') === 'true';
        @endphp

        @if ($showBadge)
            {{-- Badge style --}}
            <div
                style="position: relative; margin-inline-start: -1rem; align-self: flex-start; margin-top: -0.3rem; z-index: 99;">
                <x-filament::badge size="sm" color="primary"
                    class="!rounded-full !aspect-square !p-0 !min-w-6 !h-6 !justify-center"
                    style="height: 26px; border-radius: 50%;">
                    +{{ $remaining }}
                </x-filament::badge>
            </div>
        @else
            {{-- Plain text style --}}
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 shrink-0">
                +{{ $remaining }}
            </span>
        @endif
    @endif
</div>

{{-- Viewer.js assets are loaded dynamically via image-gallery.js --}}
