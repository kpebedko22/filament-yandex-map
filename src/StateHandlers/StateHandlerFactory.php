<?php

namespace Kpebedko22\FilamentYandexMap\StateHandlers;

use InvalidArgumentException;
use Kpebedko22\FilamentYandexMap\Enums\YandexMapMode;

final readonly class StateHandlerFactory
{
    public function getHandler(YandexMapMode $mode): StateHandler
    {
        return match ($mode) {
            YandexMapMode::Placemark => new PlacemarkStateHandler,
            YandexMapMode::Polyline => new PolylineStateHandler,
            YandexMapMode::Polygon => new PolygonStateHandler,
            default => throw new InvalidArgumentException('Unknown mode: '.$mode->value),
        };
    }
}
