<?php

namespace Kpebedko22\FilamentYandexMap\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Illuminate\Database\Eloquent\Model;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasApiKeys;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasCenter;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasGeoObjectOptions;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasGeoObjectProperties;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasHeight;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasLang;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasMode;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasZoom;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\StateHandlerFactory;

class YandexMapEntry extends Entry
{
    use HasApiKeys,
        HasCenter,
        HasGeoObjectOptions,
        HasGeoObjectProperties,
        HasHeight,
        HasLang,
        HasMode,
        HasZoom;

    protected string $view = 'filament-yandex-map::infolists.components.yandex-map';

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKey = config('services.yandex_map.api_key');
        $this->suggestApiKey = config('services.yandex_map.suggest_api_key');
        $this->zoom = config('services.yandex_map.zoom');
        $this->center = config('services.yandex_map.center');
        $this->lang = config('services.yandex_map.lang');
        $this->height = '600px';
    }

    public function usingArray(int|string $latAttr = 0, int|string $lngAttr = 1): static
    {
        $this->getStateUsing(static function (YandexMapEntry $component, Model $record) use ($latAttr, $lngAttr) {
            $state = $component->getConstantStateFromRecord($record);

            if ($state === null) {
                return null;
            }

            return (new StateHandlerFactory)
                ->getHandler($component->getMode())
                ->usingLatLngAttributes($latAttr, $lngAttr)
                ->formatJsonState($state);
        });

        return $this;
    }

    public function usingMagellan(): static
    {
        $this->getStateUsing(static function (YandexMapEntry $component, Model $record) {
            $state = $component->getConstantStateFromRecord($record);

            if ($state === null) {
                return null;
            }

            return (new StateHandlerFactory)
                ->getHandler($component->getMode())
                ->formatMagellanState($state);
        });

        return $this;
    }
}
