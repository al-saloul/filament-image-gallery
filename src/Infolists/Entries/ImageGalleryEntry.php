<?php

namespace Alsaloul\ImageGallery\Infolists\Entries;

use Closure;
use Filament\Infolists\Components\Entry;
use Illuminate\Support\Facades\Storage;

class ImageGalleryEntry extends Entry
{
    protected string $view = 'image-gallery::entries.image-gallery';

    protected int | Closure | null $thumbWidth = null;

    protected int | Closure | null $thumbHeight = null;

    protected string | Closure $imageGap = 'gap-4';

    protected string | Closure $rounded = 'rounded-lg';

    protected bool | Closure $zoomCursor = true;

    protected string | Closure $emptyText = 'No images';

    protected string | Closure | null $wrapperClass = null;

    protected string | Closure | null $disk = null;

    protected string | Closure $visibility = 'public';

    protected array | Closure $viewerOptions = [];

    public function viewerOptions(array | Closure $options): static
    {
        $this->viewerOptions = $options;

        return $this;
    }

    public function getViewerOptions(): array
    {
        return $this->evaluate($this->viewerOptions);
    }

    public function thumbWidth(int | Closure | null $width): static
    {
        $this->thumbWidth = $width;

        return $this;
    }

    public function getThumbWidth(): ?int
    {
        return $this->evaluate($this->thumbWidth);
    }

    public function thumbHeight(int | Closure | null $height): static
    {
        $this->thumbHeight = $height;

        return $this;
    }

    public function getThumbHeight(): ?int
    {
        return $this->evaluate($this->thumbHeight);
    }

    public function imageGap(string | Closure $gap): static
    {
        $this->imageGap = $gap;

        return $this;
    }

    public function getImageGap(): Closure|string
    {
        return $this->evaluate($this->imageGap);
    }

    public function rounded(string | Closure $rounded): static
    {
        $this->rounded = $rounded;

        return $this;
    }

    public function getRounded(): Closure|string
    {
        return $this->evaluate($this->rounded);
    }

    public function zoomCursor(bool | Closure $condition = true): static
    {
        $this->zoomCursor = $condition;

        return $this;
    }

    public function hasZoomCursor(): bool
    {
        return $this->evaluate($this->zoomCursor);
    }

    public function emptyText(string | Closure $text): static
    {
        $this->emptyText = $text;

        return $this;
    }

    public function getEmptyText(): Closure|string
    {
        return $this->evaluate($this->emptyText);
    }

    public function wrapperClass(string | Closure | null $class): static
    {
        $this->wrapperClass = $class;

        return $this;
    }

    public function getWrapperClass(): ?string
    {
        return $this->evaluate($this->wrapperClass);
    }

    public function disk(string | Closure | null $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function getDisk(): ?string
    {
        return $this->evaluate($this->disk);
    }

    public function visibility(string | Closure $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getVisibility(): Closure|string
    {
        return $this->evaluate($this->visibility);
    }

    /**
     * Get normalized image URLs from state
     */
    public function getImageUrls(): array
    {
        $state = $this->getState();

        if (empty($state)) {
            return [];
        }

        $disk = $this->getDisk();
        $visibility = $this->getVisibility();

        return collect($state)->map(function ($item) use ($disk, $visibility) {
            $path = null;

            if (is_string($item)) {
                $path = $item;
            } elseif (is_array($item)) {
                $path = $item['image'] ?? $item['url'] ?? $item['path'] ?? null;
            } elseif (is_object($item)) {
                $path = $item->image ?? $item->url ?? $item->path ?? null;
            }

            if (empty($path)) {
                return null;
            }

            // If it's already a full URL, return as-is
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }

            // If disk is specified, generate URL from storage
            if ($disk) {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
                $storage = Storage::disk($disk);

                if ($visibility === 'private') {
                    return $storage->temporaryUrl($path, now()->addMinutes(5));
                }

                return $storage->url($path);
            }

            // Default: return path as-is (might be relative URL)
            return $path;
        })->filter()->values()->toArray();
    }
}
