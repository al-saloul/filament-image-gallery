# Filament Image Gallery

[![Latest Version on Packagist](https://img.shields.io/packagist/v/al-saloul/filament-image-gallery.svg?style=flat-square)](https://packagist.org/packages/al-saloul/filament-image-gallery)
[![Total Downloads](https://img.shields.io/packagist/dt/al-saloul/filament-image-gallery.svg?style=flat-square)](https://packagist.org/packages/al-saloul/filament-image-gallery)

A Filament plugin for displaying image galleries with zoom, rotate, flip, and fullscreen capabilities using [Viewer.js](https://fengyuanchen.github.io/viewerjs/).

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
- 🌙 **Dark Mode Support** - Works seamlessly with dark mode
- 🌐 **RTL Support** - Full right-to-left language support
- 🌍 **Translations** - English and Arabic translations included

## Installation

```bash
composer require al-saloul/filament-image-gallery
```

Optionally, publish the config file:

```bash
php artisan vendor:publish --tag=image-gallery-config
```

## Usage

### Table Column

```php
use Alsaloul\ImageGallery\Tables\Columns\ImageGalleryColumn;

ImageGalleryColumn::make('images')
    ->getStateUsing(fn ($record) => $record->images->pluck('image')->toArray())
    ->circle()
    ->stacked(3)
    ->ring(2, '#3b82f6')
    ->limit(3)
    ->limitedRemainingText(),
```

#### Available Methods

| Method | Description | Default |
|--------|-------------|---------|
| `thumbWidth(int)` | Thumbnail width in pixels | `40` |
| `thumbHeight(int)` | Thumbnail height in pixels | `40` |
| `limit(int\|null)` | Maximum images to show | `3` |
| `stacked(int\|bool)` | Stack thumbnails with overlap. Pass `int` for custom spacing (e.g., `stacked(5)`) | `false` |
| `square(bool)` | Square shape with rounded corners | `false` |
| `circle(bool)` | Circular shape | `false` |
| `ring(int, string)` | Add border ring with width and optional color | `1, null` |
| `ringColor(string)` | Set ring color separately | `null` |
| `limitedRemainingText(bool)` | Show "+N" badge for remaining | `true` |
| `emptyText(string)` | Text when no images | `'No images'` |

---

### Infolist Entry

```php
use Alsaloul\ImageGallery\Infolists\Entries\ImageGalleryEntry;

ImageGalleryEntry::make('images')
    ->thumbWidth(128)
    ->thumbHeight(128)
    ->gap('gap-4')
    ->emptyText('No images available'),
```

#### Available Methods

| Method | Description | Default |
|--------|-------------|---------|
| `thumbWidth(int)` | Thumbnail width in pixels | `128` |
| `thumbHeight(int)` | Thumbnail height in pixels | `128` |
| `gap(string)` | Tailwind gap class | `'gap-4'` |
| `rounded(string)` | Tailwind rounded class | `'rounded-lg'` |
| `zoomCursor(bool)` | Show zoom cursor on hover | `true` |
| `emptyText(string)` | Text when no images | `'No images'` |
| `wrapperClass(string)` | Additional wrapper classes | `null` |

---

### Blade Component

```blade
<x-image-gallery::image-gallery 
    :images="$trip->images" 
    empty-text="No images for this trip"
    :thumb-width="150"
    :thumb-height="150"
    rounded="rounded-xl"
    gap="gap-6"
/>
```

#### Available Props

| Prop | Description | Default |
|------|-------------|---------|
| `images` | Array of image URLs or objects with `image` property | `[]` |
| `emptyText` | Text when no images | Translated message |
| `thumbWidth` | Thumbnail width in pixels | `128` |
| `thumbHeight` | Thumbnail height in pixels | `128` |
| `rounded` | Tailwind rounded class | `'rounded-lg'` |
| `gap` | Tailwind gap class | `'gap-4'` |
| `wrapperClass` | Additional wrapper classes | `''` |
| `zoomCursor` | Show zoom cursor on hover | `true` |
| `id` | Custom gallery ID | Auto-generated |

---

## Examples

### Circular Stacked Images with Blue Ring
```php
ImageGalleryColumn::make('images')
    ->circle()
    ->stacked(3)
    ->ring(2, '#3b82f6')
    ->limit(3)
```

### Square Images Without Stacking
```php
ImageGalleryColumn::make('images')
    ->square()
    ->limit(5)
```

### Custom Overlap Spacing
```php
ImageGalleryColumn::make('images')
    ->circle()
    ->stacked(5)  // -space-x-5
    ->limit(4)
```

---

## Image Data Format

All components accept images in multiple formats:

```php
// Array of URLs
$images = ['https://example.com/image1.jpg', 'https://example.com/image2.jpg'];

// Array of objects with 'image' property
$images = [['image' => 'https://example.com/image1.jpg']];

// Eloquent collection with 'image' attribute
$images = $trip->images;
```

## Upgrading

### From 1.x to 2.x

Version 2.x adds support for Filament v4 while maintaining backward compatibility with v3:

- PHP requirement updated to `^8.2`
- No breaking changes in API

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Al-Saloul](https://github.com/al-saloul)
- [Viewer.js](https://fengyuanchen.github.io/viewerjs/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
