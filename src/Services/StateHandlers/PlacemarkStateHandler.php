<?php

namespace Kpebedko22\FilamentYandexMap\Services\StateHandlers;

use Clickbar\Magellan\Data\Geometries\Point as MagellanPoint;
use InvalidArgumentException;
use Kpebedko22\FilamentYandexMap\ValueObjects\Point;

final class PlacemarkStateHandler implements StateHandler
{
    private string $latAttr;

    private string $lngAttr;

    public function __construct()
    {
        $this->latAttr = 'lat';
        $this->lngAttr = 'lng';
    }

    public function formatMagellanState(mixed $state): array
    {
        if ($state instanceof MagellanPoint) {
            return Point::makeFromMagellan($state)->toArray();
        }

        throw new InvalidArgumentException(
            sprintf('State must be a [%s]', MagellanPoint::class)
        );
    }

    public function formatJsonState(array $state): array
    {
        return Point::makeFromArray($state, $this->latAttr, $this->lngAttr)
            ->toArray();
    }

    public function dehydrateMagellanState(array $state): MagellanPoint
    {
        $point = Point::makeFromArray($state);

        return MagellanPoint::makeGeodetic($point->lat, $point->lng);
    }

    public function dehydrateJsonState(array $state): array
    {
        $point = Point::makeFromArray($state);

        return [
            $this->latAttr => $point->lat,
            $this->lngAttr => $point->lng,
        ];
    }

    public function usingLatLngAttributes(string $latAttr, string $lngAttr): StateHandler
    {
        $this->latAttr = $latAttr;
        $this->lngAttr = $lngAttr;

        return $this;
    }
}
