<?php

namespace Database\Factories;

use App\Models\Region;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Region>
 */
final class RegionFactory extends Factory
{
    use HasBounds;

    private const float HALF_SIZE = 0.008;

    public function definition(): array
    {
        $first = [$this->lat(), $this->lng()];

        // The second contour must not touch the first one.
        do {
            $second = [$this->lat(), $this->lng()];
        } while (
            abs($first[0] - $second[0]) < self::HALF_SIZE * 3
            && abs($first[1] - $second[1]) < self::HALF_SIZE * 3
        );

        $polygons = [$this->square(...$first), $this->square(...$second)];

        return [
            'array_multipolygon' => array_map(
                static fn (array $ring): array => [$ring],
                $polygons
            ),
            'magellan_multipolygon' => MultiPolygon::make(array_map(
                static fn (array $ring): Polygon => Polygon::make([
                    LineString::make(array_map(
                        static fn (array $point): Point => Point::makeGeodetic($point[0], $point[1]),
                        $ring
                    )),
                ]),
                $polygons
            )),
        ];
    }

    /**
     * Closed ring around the center.
     *
     * @return array<int, array{float, float}>
     */
    private function square(float $lat, float $lng): array
    {
        $d = self::HALF_SIZE;

        return [
            [$lat - $d, $lng - $d],
            [$lat - $d, $lng + $d],
            [$lat + $d, $lng + $d],
            [$lat + $d, $lng - $d],
            [$lat - $d, $lng - $d],
        ];
    }
}
