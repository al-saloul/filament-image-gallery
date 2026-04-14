<?php

namespace Alsaloul\ImageGallery;

use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\ImageColumn;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ImageGalleryServiceProvider extends PackageServiceProvider
{
    public static string $name = 'image-gallery';

    public static string $viewNamespace = 'image-gallery';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasViews(static::$viewNamespace)
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        FilamentAsset::registerScriptData([
            'GalleryViewerDefaultOptions' => config('image-gallery.viewer_options'),
        ], $this->getAssetPackageName());

        // Register assets
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        $this->imageColumnMacro();
        $this->imageEntryMacro();
    }

    protected function imageColumnMacro(): void
    {
        ImageColumn::macro('imageGallery', function () {
            /** @var ImageColumn $this */
            $this->view('image-gallery::columns.image-column-gallery');
            $this->disabledClick();

            return $this;
        });

        // Add remainingTextBadge macro to ImageColumn
        ImageColumn::macro('remainingTextBadge', function (bool $condition = true) {
            /** @var ImageColumn $this */
            $this->extraAttributes([
                'data-remaining-text-badge' => $condition ? 'true' : 'false',
            ], merge: true);

            return $this;
        });

        ImageColumn::macro('viewerOptions', function (array $options = []) {
            /** @var ImageEntry $this */
            $this->extraAttributes([
                'data-viewer-options' => $options,
            ], merge: true);

            return $this;
        });
    }

    private function imageEntryMacro(): void
    {
        ImageEntry::macro('imageGallery', function () {
            /** @var ImageEntry $this */
            $this->view('image-gallery::infolists.entries.image-entry-gallery');

            return $this;
        });

        // Add remainingTextBadge macro to ImageEntry
        ImageEntry::macro('remainingTextBadge', function (bool $condition = true) {
            /** @var ImageEntry $this */
            $this->extraAttributes([
                'data-remaining-text-badge' => $condition ? 'true' : 'false',
            ], merge: true);

            return $this;
        });

        ImageEntry::macro('viewerOptions', function (array $options = []) {
            /** @var ImageEntry $this */
            $this->extraAttributes([
                'data-viewer-options' => $options,
            ], merge: true);

            return $this;
        });
    }

    protected function getAssetPackageName(): ?string
    {
        return 'al-saloul/filament-image-gallery';
    }

    /**
     * @return array<\Filament\Support\Assets\Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('image-gallery-styles', __DIR__ . '/../resources/dist/image-gallery.css'),
            Js::make('image-gallery-scripts', __DIR__ . '/../resources/dist/image-gallery.js'),
        ];
    }
}
