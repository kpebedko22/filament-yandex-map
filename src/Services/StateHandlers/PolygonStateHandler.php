<?php

namespace Kpebedko22\FilamentYandexMap\Services\StateHandlers;

use Clickbar\Magellan\Data\Geometries\Polygon as MagellanPolygon;
use InvalidArgumentException;

final class PolygonStateHandler implements StateHandler
{
    private PolylineStateHandler $polylineStateHandler;

    public function __construct()
    {
        $this->polylineStateHandler = new PolylineStateHandler;
    }

    public function formatMagellanState(mixed $state): array
    {
        if ($state instanceof MagellanPolygon) {
            return array_map(
                [$this->polylineStateHandler, 'formatMagellanState'],
                $state->getLineStrings()
            );
        }

        throw new InvalidArgumentException(
            sprintf('State must be a [%s]', MagellanPolygon::class)
        );
    }

    public function formatJsonState(array $state): array
    {
        return array_map(
            [$this->polylineStateHandler, 'formatJsonState'],
            $state
        );
    }

    public function dehydrateMagellanState(array $state): MagellanPolygon
    {
        $points = array_map(
            [$this->polylineStateHandler, 'dehydrateMagellanState'],
            $state
        );

        return MagellanPolygon::make($points);
    }

    public function dehydrateJsonState(array $state): array
    {
        return array_map(
            [$this->polylineStateHandler, 'dehydrateJsonState'],
            $state
        );
    }

    public function usingLatLngAttributes(string $latAttr, string $lngAttr): StateHandler
    {
        $this->polylineStateHandler->usingLatLngAttributes($latAttr, $lngAttr);

        return $this;
    }
}
