@props([
    'images' => [],
    'emptyText' => null,
    'thumbWidth' => 128,
    'thumbHeight' => 128,
    'rounded' => 'rounded-lg',
    'gap' => 'gap-4',
    'wrapperClass' => '',
    'zoomCursor' => true,
    'id' => null,
    'viewerOptions' => [],
])

@php
    $galleryId = $id ?? 'gallery-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());
    $urls = collect($images)
        ->map(function ($item) {
            if (is_string($item)) {
                return $item;
            }
            if (is_array($item)) {
                return $item['image'] ?? ($item['url'] ?? null);
            }
            if (is_object($item)) {
                return $item->image ?? ($item->url ?? null);
            }
            return null;
        })
        ->filter()
        ->values();
    $emptyTextDisplay = $emptyText ?? __('image-gallery::messages.empty');
@endphp

<div id="{{ $galleryId }}"
    class="fi-in-image image-gallery {{ $gap }} my-4 pb-2 select-none {{ $wrapperClass }}"
    style="display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; overflow-x: auto; max-width: 100%; scrollbar-width: thin; cursor: pointer;"
    data-viewer-options='@json($viewerOptions)'
    data-viewer-gallery>
    @forelse($urls as $src)
        <img src="{{ $src }}" loading="lazy"
            class="{{ $rounded }} shadow object-cover border border-gray-200 dark:border-gray-700 hover:scale-105 transition {{ $zoomCursor ? 'cursor-zoom-in' : '' }}"
            style="width: {{ (int) $thumbWidth }}px; height: {{ (int) $thumbHeight }}px; flex-shrink: 0;"
            alt="image" />
    @empty
        <span class="text-gray-400 dark:text-gray-500">{{ $emptyTextDisplay }}</span>
    @endforelse
</div>

{{-- Viewer.js assets are loaded dynamically via image-gallery.js --}}
