<?php

namespace Alsaloul\ImageGallery\Tables\Columns;

use Closure;
use Filament\Tables\Columns\Column;
use Illuminate\Support\Facades\Storage;

class ImageGalleryColumn extends Column
{
    protected string $view = 'image-gallery::columns.image-gallery';

    protected int | Closure | null $thumbWidth = null;

    protected int | Closure | null $thumbHeight = null;

    protected int | Closure | null $limit = 3;

    protected bool | Closure $isStacked = false;

    protected int | Closure $stackedOverlap = 2;

    protected bool | Closure $isSquare = false;

    protected bool | Closure $isCircular = false;

    protected int | Closure $ringWidth = 1;

    protected string | Closure | null $ringColor = null;

    protected bool | Closure $showRemainingText = true;

    protected string | Closure $emptyText = 'No images';

    protected string | Closure | null $disk = null;

    protected string | Closure $visibility = 'public';

    protected function setUp(): void
    {
        parent::setUp();

        // Disable the anchor tag wrapper so clicks can reach Viewer.js
        $this->disabledClick();
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

    public function limit(int | Closure | null $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->evaluate($this->limit);
    }

    public function stacked(int | bool | Closure $overlap = true): static
    {
        if (is_int($overlap)) {
            $this->isStacked = true;
            $this->stackedOverlap = $overlap;
        } else {
            $this->isStacked = $overlap;
        }

        return $this;
    }

    public function isStacked(): bool
    {
        return $this->evaluate($this->isStacked);
    }

    public function getStackedOverlap(): int
    {
        return $this->evaluate($this->stackedOverlap);
    }

    public function square(bool | Closure $condition = true): static
    {
        $this->isSquare = $condition;

        return $this;
    }

    public function isSquare(): bool
    {
        return $this->evaluate($this->isSquare);
    }

    public function circular(bool | Closure $condition = true): static
    {
        $this->isCircular = $condition;

        return $this;
    }

    public function isCircular(): bool
    {
        return $this->evaluate($this->isCircular);
    }

    public function ring(int | Closure $width = 2, string | Closure | null $color = null): static
    {
        $this->ringWidth = $width;

        if ($color !== null) {
            $this->ringColor = $color;
        }

        return $this;
    }

    public function ringColor(string | Closure | null $color): static
    {
        $this->ringColor = $color;

        return $this;
    }

    public function getRingWidth(): int
    {
        return $this->evaluate($this->ringWidth);
    }

    public function getRingColor(): ?string
    {
        return $this->evaluate($this->ringColor);
    }

    public function limitedRemainingText(bool | Closure $condition = true): static
    {
        $this->showRemainingText = $condition;

        return $this;
    }

    public function shouldShowRemainingText(): bool
    {
        return $this->evaluate($this->showRemainingText);
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
