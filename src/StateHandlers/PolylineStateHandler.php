<?php

namespace Kpebedko22\FilamentYandexMap\StateHandlers;

use Clickbar\Magellan\Data\Geometries\LineString as MagellanLineString;
use InvalidArgumentException;

final class PolylineStateHandler implements StateHandler
{
    private PlacemarkStateHandler $placemarkStateHandler;

    public function __construct()
    {
        $this->placemarkStateHandler = new PlacemarkStateHandler;
    }

    public function formatMagellanState(mixed $state): array
    {
        if ($state instanceof MagellanLineString) {
            return array_map(
                [$this->placemarkStateHandler, 'formatMagellanState'],
                $state->getPoints()
            );
        }

        throw new InvalidArgumentException(
            sprintf('State must be a [%s]', MagellanLineString::class)
        );
    }

    public function formatJsonState(array $state): array
    {
        return array_map(
            [$this->placemarkStateHandler, 'formatJsonState'],
            $state
        );
    }

    public function dehydrateMagellanState(array $state): MagellanLineString
    {
        $points = array_map(
            [$this->placemarkStateHandler, 'dehydrateMagellanState'],
            $state
        );

        return MagellanLineString::make($points);
    }

    public function dehydrateJsonState(array $state): array
    {
        return array_map(
            [$this->placemarkStateHandler, 'dehydrateJsonState'],
            $state
        );
    }

    public function usingLatLngAttributes(string $latAttr, string $lngAttr): StateHandler
    {
        $this->placemarkStateHandler->usingLatLngAttributes($latAttr, $lngAttr);

        return $this;
    }
}
