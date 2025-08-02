<?php

namespace Database\Factories;

use App\Models\Area;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Area>
 */
final class AreaFactory extends Factory
{
    use HasBounds;

    public function definition(): array
    {
        $pointsCount = fake()->numberBetween(3, 6);

        $arr = [];
        $mag = [];

        $curLat = $this->lat();
        $curLng = $this->lng();

        for ($i = 0; $i < $pointsCount; $i++) {
            $lat = $this->lat();
            $lng = $this->lng();

            $arr[] = [$curLat, $curLng];
            $arr[] = [$lat, $lng];

            $mag[] = Point::makeGeodetic($curLat, $curLng);
            $mag[] = Point::makeGeodetic($lat, $lng);

            $curLat = $lat;
            $curLng = $lng;
        }

        $arr[] = $arr[0];
        $mag[] = $mag[0];

        return [
            'array_polygon' => [$arr],
            'magellan_polygon' => Polygon::make([LineString::make($mag)]),
        ];
    }
}
