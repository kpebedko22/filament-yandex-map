<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components;

use Filament\Forms\Components\Field;
use Kpebedko22\FilamentYandexMap\DTOs\Buttons\ButtonData;
use Kpebedko22\FilamentYandexMap\DTOs\Buttons\ButtonOptions;
use Kpebedko22\FilamentYandexMap\Enums\Buttons\ButtonFloat;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasApiKeys;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasCenter;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasControlButtons;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasGeoObjectOptions;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasGeoObjectProperties;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasHeight;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasLang;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasMode;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasZoom;
use Kpebedko22\FilamentYandexMap\Rules\PolygonRule;
use Kpebedko22\FilamentYandexMap\Rules\PolylineRule;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\StateHandlerFactory;

final class YandexMap extends Field
{
    use HasApiKeys,
        HasCenter,
        HasControlButtons,
        HasGeoObjectOptions,
        HasGeoObjectProperties,
        HasHeight,
        HasLang,
        HasMode,
        HasZoom;

    protected string $view = 'filament-yandex-map::forms.components.yandex-map';

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKey = config('services.yandex_map.api_key');
        $this->suggestApiKey = config('services.yandex_map.suggest_api_key');
        $this->zoom = config('services.yandex_map.zoom');
        $this->center = config('services.yandex_map.center');
        $this->lang = config('services.yandex_map.lang');
        $this->height = '600px';

        $this->rule(new PolylineRule, static function (YandexMap $component) {
            return $component->getMode() === YandexMapMode::Polyline;
        });
        $this->rule(new PolygonRule, static function (YandexMap $component) {
            return $component->getMode() === YandexMapMode::Polygon;
        });

        $this->deleteBtnParameters(
            new ButtonData(__('filament-yandex-map::control-buttons.delete')),
            new ButtonOptions(
                float: ButtonFloat::Right,
                selectOnClick: false
            ),
        );
        $this->drawBtnParameters(
            new ButtonData(__('filament-yandex-map::control-buttons.draw')),
            new ButtonOptions(float: ButtonFloat::Right),
        );
        $this->editBtnParameters(
            new ButtonData(__('filament-yandex-map::control-buttons.edit')),
            new ButtonOptions(float: ButtonFloat::Right),
        );
    }

    public function usingArray(int|string $latAttr = 0, int|string $lngAttr = 1): YandexMap
    {
        $this->formatStateUsing(static function (YandexMap $component, mixed $state) use ($latAttr, $lngAttr) {
            $handler = (new StateHandlerFactory)
                ->getHandler($component->getMode())
                ->usingLatLngAttributes($latAttr, $lngAttr);

            if ($state === null) {
                return null;
            }

            return $handler->formatJsonState($state);
        });

        $this->dehydrateStateUsing(static function (YandexMap $component, mixed $state) use ($latAttr, $lngAttr) {
            $handler = (new StateHandlerFactory)
                ->getHandler($component->getMode())
                ->usingLatLngAttributes($latAttr, $lngAttr);

            if ($state === null) {
                return null;
            }

            return $handler->dehydrateJsonState($state);
        });

        return $this;
    }

    public function usingMagellan(): YandexMap
    {
        $this->formatStateUsing(static function (YandexMap $component, mixed $state) {
            $handler = (new StateHandlerFactory)->getHandler($component->getMode());

            if ($state === null) {
                return null;
            }

            return $handler->formatMagellanState($state);
        });

        $this->dehydrateStateUsing(function (YandexMap $component, mixed $state) {
            $handler = (new StateHandlerFactory)->getHandler($component->getMode());

            if ($state === null) {
                return null;
            }

            return $handler->dehydrateMagellanState($state);
        });

        return $this;
    }
}
