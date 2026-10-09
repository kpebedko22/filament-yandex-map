<?php

namespace Kpebedko22\FilamentYandexMap\Infolists\Components;

use Closure;
use Filament\Infolists\Components\Entry;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasApiKeys;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasCenter;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasHeight;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasLang;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasZoom;

/**
 * Several geo-objects on one map, for viewing only.
 *
 * Every layer is a regular YandexMapEntry: it keeps its own mode, state,
 * properties and options (e.g. colors). The map settings (API keys, language,
 * center, zoom, height) are taken from this entry, not from the layers.
 */
class YandexMapLayersEntry extends Entry
{
    use HasApiKeys;
    use HasCenter;
    use HasHeight;
    use HasLang;
    use HasZoom;

    protected string $view = 'filament-yandex-map::infolists.components.yandex-map-layers';

    protected function setUp(): void
    {
        parent::setUp();

        // The layers read the record by their own names, so the name of this entry
        // must not become a prefix of their state paths.
        $this->statePath(null);

        $this->apiKey = config('services.yandex_map.api_key');
        $this->suggestApiKey = config('services.yandex_map.suggest_api_key');
        $this->zoom = config('services.yandex_map.zoom');
        $this->center = config('services.yandex_map.center');
        $this->lang = config('services.yandex_map.lang');
        $this->height = '600px';
    }

    /**
     * @param  array<YandexMapEntry>|Closure  $layers
     */
    public function layers(array|Closure $layers): static
    {
        $this->childComponents($layers);

        return $this;
    }

    /**
     * Layers for the frontend. Hidden layers and layers without a geo-object are skipped.
     *
     * @return list<array{mode: string, state: mixed, properties: ?array, options: ?array}>
     */
    public function getLayers(): array
    {
        return collect($this->getChildSchema()->getComponents())
            ->filter(static fn (mixed $layer): bool => $layer instanceof YandexMapEntry)
            ->map(static fn (YandexMapEntry $layer): array => [
                'mode' => $layer->getMode()->value,
                'state' => $layer->getState(),
                'properties' => $layer->getGeoObjectProperties(),
                'options' => $layer->getGeoObjectOptions(),
            ])
            ->filter(static fn (array $layer): bool => filled($layer['state']))
            ->values()
            ->all();
    }
}
