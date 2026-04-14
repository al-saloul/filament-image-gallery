@php
    $urls = $getImageUrls();
    $width = $getThumbWidth();
    $height = $getThumbHeight();
    $gap = $getImageGap();
    $rounded = $getRounded();
    $zoomCursor = $hasZoomCursor();
    $wrapperClass = $getWrapperClass() ?? '';
    $galleryId = 'gallery-entry-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());
    $viewerOptions = $getViewerOptions();

    // Size styles - only add if width/height specified
    $sizeStyle = '';
    if ($width) {
        $sizeStyle .= "width: {$width}px;";
    }
    if ($height) {
        $sizeStyle .= " height: {$height}px;";
    }
    if ($width || $height) {
        $sizeStyle .= ' flex-shrink: 0;';
    }
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div id="{{ $galleryId }}" class="fi-in-image image-gallery {{ $gap }} my-2 pb-2 select-none {{ $wrapperClass }}"
        style="display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; overflow-x: auto; max-width: 100%; scrollbar-width: thin; cursor: pointer;"
        data-viewer-options='@json($viewerOptions)'
        data-viewer-gallery>
        @foreach ($urls as $src)
            <img src="{{ $src }}" loading="lazy"
                class="{{ $rounded }} shadow object-cover border border-gray-200 dark:border-gray-700 hover:scale-105 transition cursor-pointer"
                style="{{ $sizeStyle }} flex-shrink: 0;" alt="image" />
        @endforeach
    </div>
</x-dynamic-component>

{{-- Viewer.js assets are loaded dynamically via image-gallery.js --}}
