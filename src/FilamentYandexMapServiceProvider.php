<?php

namespace Kpebedko22\FilamentYandexMap;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\AssetManager;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentYandexMapServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-yandex-map')
            ->hasViews()
            ->hasTranslations();
    }

    public function packageRegistered(): void
    {
        $this->app->resolving(AssetManager::class, function () {
            FilamentAsset::register(
                assets: $this->getAssets(),
                package: $this->getAssetPackageName(),
            );
        });
    }

    protected function getAssets(): array
    {
        return [
            AlpineComponent::make('filament-yandex-map', __DIR__ . '/../resources/js/dist/filament-yandex-map.js'),
            AlpineComponent::make('filament-yandex-map-entry', __DIR__ . '/../resources/js/dist/filament-yandex-map-entry.js'),
        ];
    }

    protected function getAssetPackageName(): string
    {
        return 'kpebedko22/filament-yandex-map';
    }
}
