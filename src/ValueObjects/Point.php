<?php

namespace Kpebedko22\FilamentYandexMap\ValueObjects;

use Clickbar\Magellan\Data\Geometries\Point as MagellanPoint;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use InvalidArgumentException;

final readonly class Point implements Arrayable
{
    public function __construct(
        public float $lat,
        public float $lng,
    ) {}

    public static function makeFromArray(
        array $data,
        int|string $latAttr = 0,
        int|string $lngAttr = 1
    ): Point {
        $lat = Arr::get($data, $latAttr);
        $lng = Arr::get($data, $lngAttr);

        if (is_null($lat)) {
            throw new InvalidArgumentException("Key [$latAttr] for latitude in array is required.");
        }

        if (is_null($lng)) {
            throw new InvalidArgumentException("Key [$lngAttr] for longitude in array is required.");
        }

        return new self($lat, $lng);
    }

    public static function makeFromMagellan(MagellanPoint $point): Point
    {
        return new self($point->getLatitude(), $point->getLongitude());
    }

    public function toArray(): array
    {
        return [
            $this->lat,
            $this->lng,
        ];
    }
}
