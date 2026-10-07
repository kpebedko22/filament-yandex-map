<?php

namespace Kpebedko22\FilamentYandexMap\Services\StateHandlers;

use Clickbar\Magellan\Data\Geometries\MultiPolygon as MagellanMultiPolygon;
use InvalidArgumentException;

class MultiPolygonStateHandler implements StateHandler
{
    protected PolygonStateHandler $polygonStateHandler;

    public function __construct()
    {
        $this->polygonStateHandler = new PolygonStateHandler;
    }

    public function formatMagellanState(mixed $state): array
    {
        if ($state instanceof MagellanMultiPolygon) {
            return array_map(
                [$this->polygonStateHandler, 'formatMagellanState'],
                $state->getPolygons()
            );
        }

        throw new InvalidArgumentException(
            sprintf('State must be a [%s]', MagellanMultiPolygon::class)
        );
    }

    public function formatJsonState(array $state): array
    {
        return array_map(
            [$this->polygonStateHandler, 'formatJsonState'],
            $state
        );
    }

    public function dehydrateMagellanState(array $state): MagellanMultiPolygon
    {
        $polygons = array_map(
            [$this->polygonStateHandler, 'dehydrateMagellanState'],
            $state
        );

        return MagellanMultiPolygon::make($polygons);
    }

    public function dehydrateJsonState(array $state): array
    {
        return array_map(
            [$this->polygonStateHandler, 'dehydrateJsonState'],
            $state
        );
    }

    public function usingLatLngAttributes(string $latAttr, string $lngAttr): StateHandler
    {
        $this->polygonStateHandler->usingLatLngAttributes($latAttr, $lngAttr);

        return $this;
    }
}
