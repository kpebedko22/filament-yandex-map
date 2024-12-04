<?php

namespace Kpebedko22\FilamentYandexMap\Forms\Components;

use Filament\Forms\Components\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Kpebedko22\FilamentYandexMap\Buttons\ButtonData;
use Kpebedko22\FilamentYandexMap\Buttons\ButtonOptions;
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
use Kpebedko22\FilamentYandexMap\StateHandlers\StateHandlerFactory;
use Kpebedko22\FilamentYandexMap\Support\Point;

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

    protected string $view = 'support.filament.yandex-map.forms.components.yandex-map';

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

    /**
     * Пример использования: в базе данных точка представлена массивом ["lat": 35, "lng": 58].
     * Линия - массив точек. Полигон - массив линий.
     *
     * @param  int|string  $latAttr  Название атрибута "широта" в представлении точки
     * @param  int|string  $lngAttr  Название атрибуета "долгота" в представлении точки
     * @return $this
     */
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

    public function usingString(string $separator = ',', int $latIndex = 0, int $lngIndex = 1): YandexMap
    {
        $this->formatStateUsing(static function (YandexMap $component, ?Model $record) use ($separator, $latIndex, $lngIndex) {
            if (! $record) {
                return $component->getCenter();
            }

            $statePath = $component->getStatePath(false);

            $data = $record->{$statePath};
            $data = explode($separator, $data);

            $lat = Arr::get($data, $latIndex);
            $lng = Arr::get($data, $lngIndex);

            return (new Point($lat, $lng))->toArray();
        });

        $this->dehydrateStateUsing(static function ($state) use ($separator, $latIndex, $lngIndex) {
            $lat = Arr::get($state, 'lat');
            $lng = Arr::get($state, 'lng');

            $data = [
                $latIndex => $lat,
                $lngIndex => $lng,
            ];

            ksort($data);

            return implode($separator, $data);
        });

        return $this;
    }

    /**
     * FIXME: не работает в полной мере и треубет ручной мутации данных
     *
     * @return $this
     */
    public function usingTwoColumns(string $latColumn = 'lat', string $lngColumn = 'lng'): YandexMap
    {
        $this->formatStateUsing(static function (YandexMap $component, ?Model $record) use ($latColumn, $lngColumn) {
            if (! $record) {
                return $component->getCenter();
            }

            $lat = $record->{$latColumn};
            $lng = $record->{$lngColumn};

            return (new Point($lat, $lng))->toArray();
        });

        $this->dehydrateStateUsing(function ($state) use ($latColumn, $lngColumn) {
            return [
                $latColumn => $state['lat'] ?? null,
                $lngColumn => $state['lng'] ?? null,
            ];
        });

        return $this;
    }
}
