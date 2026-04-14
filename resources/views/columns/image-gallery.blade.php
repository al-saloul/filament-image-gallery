@php
    $urls = $getImageUrls();
    $limit = $getLimit();
    $visibleUrls = $limit ? array_slice($urls, 0, $limit) : $urls;
    $remaining = $limit ? max(0, count($urls) - $limit) : 0;
    $width = $getThumbWidth() ?? 40;
    $height = $isSquare() && $width ? $width : $getThumbHeight() ?? 40;
    $isStacked = $isStacked();
    $stackedOverlap = $getStackedOverlap();
    $isSquare = $isSquare();
    $isCircular = $isCircular();
    $ringWidth = $getRingWidth();
    $ringColor = $getRingColor() ?? 'white';
    $galleryId = 'gallery-col-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());
    $viewerOptions = $getViewerOptions();

    // Determine border radius
    $borderRadius = $isCircular ? '9999px' : ($isSquare ? '0.5rem' : '0.25rem');

    // Ring/border style using box-shadow for better stacking appearance
    $ringStyle = $ringWidth > 0 ? "box-shadow: 0 0 0 {$ringWidth}px {$ringColor};" : '';

    // Calculate stacked margin - negative margin for overlap effect
    $stackedMarginPx = $stackedOverlap * 4; // 4px per overlap unit
@endphp

<div id="{{ $galleryId }}"
    style="display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; align-items: center;"
    data-viewer-options='@json($viewerOptions)'
    data-viewer-gallery wire:ignore.self>
    @foreach ($visibleUrls as $index => $src)
        <img src="{{ $src }}" loading="lazy" class="object-cover object-center shrink-0"
            style="
                width: {{ $width }}px;
                height: {{ $height }}px;
                border-radius: {{ $borderRadius }};
                {{ $ringStyle }}
                position: relative;
                z-index: {{ count($visibleUrls) - $index }};
                @if ($isStacked && $index > 0) margin-inline-start: -{{ $stackedMarginPx }}px; @endif
                cursor: pointer;
                transition: transform 0.15s ease-in-out;
            "
            onmouseover="this.style.transform='scale(1.1)'; this.style.zIndex='{{ count($visibleUrls) + 10 }}';"
            onmouseout="this.style.transform='scale(1)'; this.style.zIndex='{{ count($visibleUrls) - $index }}';"
            alt="image" />
    @endforeach

    @if ($remaining > 0 && $shouldShowRemainingText())
        @if ($shouldShowRemainingTextBadge())
            {{-- Badge style --}}
            <div
                style="position: relative; margin-inline-start: -0.5rem; align-self: flex-start; margin-top: -0.3rem; z-index: 99;">
                <x-filament::badge size="sm" color="primary"
                    class="!rounded-full !aspect-square !p-0 !min-w-6 !h-6 !justify-center"
                    style="height: 26px; border-radius: 50%;">
                    +{{ $remaining }}
                </x-filament::badge>
            </div>
        @else
            {{-- Plain text style --}}
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 shrink-0"
                style="margin-inline-start: 0.25rem;">
                +{{ $remaining }}
            </span>
        @endif
    @endif
</div>

{{-- Viewer.js assets are loaded dynamically via image-gallery.js --}}
