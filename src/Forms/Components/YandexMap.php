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
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasHeight;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasLang;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasMode;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasPlacemark;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasPolygon;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasPolyline;
use Kpebedko22\FilamentYandexMap\Forms\Components\Concerns\HasZoom;
use Kpebedko22\FilamentYandexMap\Rules\PolygonRule;
use Kpebedko22\FilamentYandexMap\Rules\PolylineRule;
use Kpebedko22\FilamentYandexMap\Services\StateHandlers\StateHandlerFactory;

class YandexMap extends Field
{
    use HasApiKeys;
    use HasCenter;
    use HasControlButtons;
    use HasHeight;
    use HasLang;
    use HasMode;
    use HasPlacemark;
    use HasPolygon;
    use HasPolyline;
    use HasZoom;

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

        $this->rule(new PolylineRule, static function (YandexMap $component): bool {
            return $component->getMode() === YandexMapMode::Polyline;
        });

        $this->rule(new PolygonRule, static function (YandexMap $component): bool {
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

    public function usingArray(int|string $latAttr = 0, int|string $lngAttr = 1): static
    {
        $this->formatStateUsing(static function (YandexMap $component, mixed $state) use ($latAttr, $lngAttr): ?array {
            if ($state === null) {
                return null;
            }

            return (new StateHandlerFactory)
                ->getHandler($component->getMode())
                ->usingLatLngAttributes($latAttr, $lngAttr)
                ->formatJsonState($state);
        });

        $this->dehydrateStateUsing(static function (YandexMap $component, mixed $state) use ($latAttr, $lngAttr): ?array {
            if ($state === null) {
                return null;
            }

            return (new StateHandlerFactory)
                ->getHandler($component->getMode())
                ->usingLatLngAttributes($latAttr, $lngAttr)
                ->dehydrateJsonState($state);
        });

        return $this;
    }

    public function usingMagellan(): static
    {
        $this->formatStateUsing(static function (YandexMap $component, mixed $state): ?array {
            if ($state === null) {
                return null;
            }

            return (new StateHandlerFactory)
                ->getHandler($component->getMode())
                ->formatMagellanState($state);
        });

        $this->dehydrateStateUsing(static function (YandexMap $component, mixed $state): mixed {
            if ($state === null) {
                return null;
            }

            return (new StateHandlerFactory)
                ->getHandler($component->getMode())
                ->dehydrateMagellanState($state);
        });

        return $this;
    }
}
