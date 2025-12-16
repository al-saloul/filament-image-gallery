
<p align="center">
    <img src="https://raw.githubusercontent.com/al-saloul/images/refs/heads/main/filament-image-gallery.png" alt="filament-image-gallery">
</p>

# Filament Image Gallery

[![Latest Version on Packagist](https://img.shields.io/packagist/v/al-saloul/filament-image-gallery.svg?style=flat-square)](https://packagist.org/packages/al-saloul/filament-image-gallery)
[![Total Downloads](https://img.shields.io/packagist/dt/al-saloul/filament-image-gallery.svg?style=flat-square)](https://packagist.org/packages/al-saloul/filament-image-gallery)

A Filament plugin for displaying image galleries with zoom, rotate, flip, and fullscreen capabilities using [Viewer.js](https://fengyuanchen.github.io/viewerjs/).

## Demo
<p align="center">
    <img src="https://raw.githubusercontent.com/al-saloul/images/refs/heads/main/filament-image-gallery.gif" alt="filament-image-gallery">
</p>

## Screenshots

### Table Column
| Thumbnails | Gallery Viewer |
|:----------:|:--------------:|
| ![Table Column - Thumbnails](https://raw.githubusercontent.com/al-saloul/images/refs/heads/main/onClickTable.jpg) | ![Table Column - Gallery Viewer](https://raw.githubusercontent.com/al-saloul/images/refs/heads/main/aferClickTable.png) |

### Infolist Entry
| Thumbnails | Gallery Viewer |
|:----------:|:--------------:|
| ![Infolist Entry - Thumbnails](https://raw.githubusercontent.com/al-saloul/images/refs/heads/main/onClickInfolist.jpg) | ![Infolist Entry - Gallery Viewer](https://raw.githubusercontent.com/al-saloul/images/refs/heads/main/aferClicknfolist.png) |

---

## Requirements

| Version | Filament | PHP | Laravel |
|---------|----------|-----|---------|
| 2.x | 3.x \| 4.x | ^8.2 | ^10.0 \| ^11.0 \| ^12.0 |
| 1.x | 3.x | ^8.1 | ^10.0 \| ^11.0 |

## Features

- 📊 **Table Column** - Display image galleries in table rows with stacked thumbnails
- 📋 **Infolist Entry** - Show image galleries in infolists with horizontal scrolling
- 🧩 **Blade Component** - Use standalone in any Blade view
- 🔍 **Viewer.js Integration** - Zoom, rotate, flip, and fullscreen image viewing
- 💾 **Storage Disk Support** - Works with any Laravel filesystem disk
- 🌙 **Dark Mode Support** - Works seamlessly with dark mode
- 🌐 **RTL Support** - Full right-to-left language support

## Installation

```bash
composer require al-saloul/filament-image-gallery
```


## Quick Usage

You can use the `imageGallery()` method on any standard Filament `ImageColumn` or `ImageEntry` to enable the gallery viewer.

### Table Column

```php
use Filament\Tables\Columns\ImageColumn;

ImageColumn::make('images')
    ->circular()
    ->stacked()
    ->limit(3)
    ->imageGallery() // Enables the gallery viewer
```

### Infolist Entry

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('images')
    ->imageGallery() // Enables the gallery viewer
```

## Usage

### Table Column

```php
use Alsaloul\ImageGallery\Tables\Columns\ImageGalleryColumn;

ImageGalleryColumn::make('images')
    ->getStateUsing(fn ($record) => $record->images->pluck('image')->toArray())
    ->disk(config('filesystems.default'))
    ->circular()
    ->stacked(3)
    ->ring(2, '#3b82f6')
    ->limit(3)
    ->limitedRemainingText(),
```

#### Available Methods

| Method | Description | Default |
|--------|-------------|---------|
| `disk(string)` | Storage disk for images | `null` |
| `visibility(string)` | `'public'` or `'private'` (for temporary URLs) | `'public'` |
| `thumbWidth(int)` | Thumbnail width in pixels | `40` |
| `thumbHeight(int)` | Thumbnail height in pixels | `40` |
| `limit(int\|null)` | Maximum images to show | `3` |
| `stacked(int\|bool)` | Stack thumbnails. Pass `int` for custom spacing | `false` |
| `square(bool)` | Square shape with rounded corners | `false` |
| `circular(bool)` | Circular shape | `false` |
| `ring(int, string)` | Border ring with width and color | `1, null` |
| `ringColor(string)` | Set ring color separately | `null` |
| `limitedRemainingText(bool)` | Show "+N" badge for remaining | `true` |

---

### Infolist Entry

```php
use Alsaloul\ImageGallery\Infolists\Entries\ImageGalleryEntry;

ImageGalleryEntry::make('images')
    ->disk(config('filesystems.default'))
    ->thumbWidth(128)
    ->thumbHeight(128)
    ->imageGap('gap-4'),
```

#### Available Methods

| Method | Description | Default |
|--------|-------------|---------|
| `disk(string)` | Storage disk for images | `null` |
| `visibility(string)` | `'public'` or `'private'` | `'public'` |
| `thumbWidth(int)` | Thumbnail width in pixels | `null` (natural size) |
| `thumbHeight(int)` | Thumbnail height in pixels | `null` (natural size) |
| `imageGap(string)` | Tailwind gap class | `'gap-4'` |
| `rounded(string)` | Tailwind rounded class | `'rounded-lg'` |
| `wrapperClass(string)` | Additional wrapper classes | `null` |

---

### Blade Component

```blade
<x-image-gallery::image-gallery 
    :images="$model->images" 
    :thumb-width="150"
    :thumb-height="150"
    rounded="rounded-xl"
    gap="gap-6"
/>
```

---

## Examples

### With Storage Disk
```php
ImageGalleryColumn::make('images')
    ->disk('s3')
    ->circular()
    ->stacked(3)
    ->limit(3)

// For private files
ImageGalleryColumn::make('images')
    ->disk('s3')
    ->visibility('private')  // Generates temporary URLs
    ->limit(3)
```

### Circular Stacked with Ring
```php
ImageGalleryColumn::make('images')
    ->circular()
    ->stacked(3)
    ->ring(2, '#3b82f6')
    ->limit(3)
```

### Natural Size (No Thumbnail Dimensions)
```php
// Images display at their natural size
ImageGalleryEntry::make('images')
    ->disk(config('filesystems.default'))
    ->imageGap('gap-4'),
```

---

## Configuration (Optional)

Optionally, publish the config file:

```bash
php artisan vendor:publish --tag=image-gallery-config
```

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information.

## Credits

- [Mohammed Alsaloul](https://github.com/al-saloul)
- [Husam Almiyah](https://github.com/Husam-Almiyah)
- [Viewer.js](https://fengyuanchen.github.io/viewerjs/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
