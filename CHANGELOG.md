# Changelog

All notable changes to `filament-image-gallery` will be documented in this file.

## v2.1.3 - 2025-12-16

### Changed
- Renamed `circle()` to `circular()`

## v2.1.0 - 2025-12-13

### Added
- `imageGallery()` macro for `Filament\Tables\Columns\ImageColumn`
- `imageGallery()` macro for `Filament\Infolists\Components\ImageEntry`
- Support for using standard Filament Image components with the gallery viewer

## v2.0.6 - 2024-12-12

### Fixed
- Minor bug fixes

## v2.0.5 - 2024-12-11

### Fixed
- Image clicks no longer trigger Filament's table row navigation (`recordUrl`). Users no longer need to add `->recordUrl(null)` to their tables to use the image preview feature.

## v2.0.1 - 2024-12-11

### Added
- `disk()` method for Storage integration (both Column and Entry)
- `visibility()` method for private files with temporary URLs
- Cursor pointer on image hover

### Changed
- Renamed `gap()` to `imageGap()` in Entry to avoid Filament v4 conflict
- Removed empty text display when no images

### Fixed
- Filament v4 compatibility for ImageGalleryEntry

## v2.0.0 - 2024-12-11

### Added
- Support for Filament v4.x
- `circle()` method for circular image thumbnails
- `ring(width, color)` method for customizable borders
- `stacked(overlap)` method with configurable overlap spacing

### Changed
- PHP requirement updated to `^8.2`
- Compatible with both Filament v3.x and v4.x

## v1.0.0 - 2024-12-11

### Added
- Initial release
- `ImageGalleryColumn` for Filament tables
- `ImageGalleryEntry` for Filament infolists
- `x-image-gallery::image-gallery` Blade component
- Viewer.js integration for image manipulation
- Dark mode support
- RTL support
- English and Arabic translations
