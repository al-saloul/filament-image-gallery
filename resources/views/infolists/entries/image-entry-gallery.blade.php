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
    $defaultHeight = $defaultHeight ? (is_numeric($defaultHeight) ? $defaultHeight . 'px' : $defaultHeight) : '150px';

    $galleryId = 'gallery-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());

    // Calculate margin for stacked images using inline styles (Tailwind-safe)
    $stackedMarginValue = $overlap * 0.25;
    $stackedMargin = $isStacked && $overlap > 0 ? "-{$stackedMarginValue}rem" : '0';
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div id="{{ $galleryId }}"
        {{ $attributes->merge($getExtraAttributes(), escape: false)->except('data-viewer-options')->class(['fi-in-image', 'flex items-center', 'gap-1.5' => !$isStacked]) }}
        style="display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; overflow-x: auto; max-width: 100%; scrollbar-width: thin; cursor: pointer;"
        data-viewer-options='@json($getExtraAttributes()['data-viewer-options'] ?? [])'
        data-viewer-gallery wire:ignore.self>
        @foreach ($limitedState as $index => $stateItem)
            <img src="{{ $getImageUrl($stateItem) }}"
                style="
                    height: {{ $defaultHeight }};
                    width: {{ $defaultWidth }};
                    @if ($isStacked && $index > 0) margin-inline-start: {{ $stackedMargin }}; @endif
                "
                {{ $getExtraImgAttributeBag()->class([
                    'max-w-none object-cover object-center',
                    'rounded-full' => $isCircular,
                    'rounded-lg' => $isSquare,
                    'ring-white dark:ring-gray-900' => $isStacked,
                    'ring-2' => $isStacked && $overlap > 0,
                ]) }} />
        @endforeach

        @if ($remaining > 0 && ($limitedRemainingText ?? true))
            @php
                $showBadge = ($getExtraAttributes()['data-remaining-text-badge'] ?? 'false') === 'true';
            @endphp

            @if ($showBadge)
                <div
                    style="position: relative; margin-inline-start: -1rem; align-self: flex-start; margin-top: -0.3rem; z-index: 99;">
                    <x-filament::badge size="sm" color="primary"
                        class="!rounded-full !aspect-square !p-0 !min-w-6 !h-6 !justify-center"
                        style="height: 26px; border-radius: 50%;">
                        +{{ $remaining }}
                    </x-filament::badge>
                </div>
            @else
                <div style="
                        min-height: {{ $defaultHeight }};
                        min-width: {{ $defaultWidth }};
                        height: {{ $defaultHeight }};
                        width: {{ $defaultWidth }};
                        @if ($isStacked) margin-inline-start: {{ $stackedMargin }}; @endif
                    "
                    @class([
                        'flex items-center justify-center bg-gray-100 font-medium text-gray-500 ring-white dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-900',
                        'rounded-full' => $isCircular,
                        'rounded-lg' => $isSquare,
                        'ring-2' => $isStacked && $overlap > 0,
                    ])>
                    <span class="-ms-0.5 text-xs">
                        +{{ $remaining }}
                    </span>
                </div>
            @endif
        @endif
    </div>

    {{-- Viewer.js assets are loaded dynamically via image-gallery.js --}}
</x-dynamic-component>
